<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\ProductSizeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{

    public function renderCategories($categories, $selected = [])
    {
        $html = '<ul style="list-style:none; padding-left:15px;">';
        foreach ($categories as $category) {
            $isChecked = in_array($category->id, $selected) ? 'checked' : '';
            $html .= '<li>';
            $html .= '<label style="cursor:pointer;">';
            $html .= '<input type="checkbox" class="category-checkbox" name="categories[]" value="' . $category->id . '" ' . $isChecked . '> ';
            $html .= '<span class="category-label" data-id="' . $category->id . '">' . $category->name . '</span>';
            $html .= '</label>';

            if ($category->children->count()) {
                $html .= $this->renderCategories($category->children, $selected); // Recursively render children
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function index()
    {
        $products = Product::with(['images', 'category', 'sizes' => function ($q) {
            $q->where('is_active', true); // 👈 sirf active sizes dikhayenge
        }])->get();

        // Sirf root categories + unke children recursive
        $categories = Category::with('children.children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $sizes              = ProductSizeItem::all();
        $renderedCategories = $this->renderCategories($categories, []);
        return view('admin.products.show-product', compact('products', 'categories', 'sizes', 'renderedCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:products,name',
            'categories'    => 'required|array',
            'categories.*'  => 'exists:categories,id',
            'desc'          => 'nullable|string',
            'main_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sizes.*.size'  => 'required|exists:product_size_items,id',
            'sizes.*.price' => 'required|numeric',
            // 'sizes.*.stock' => 'required|integer|min:0', // Validate stock
        ], [
            'name.unique' => 'Product already exists in the database.',
        ]);

        DB::beginTransaction();

        try {
            // Step 1: Save the product
            $product = Product::create([
                'name' => $request->name,
                'desc' => $request->desc,
            ]);

            // multiple categories attach
            if ($request->has('categories')) {
                $product->categories()->attach($request->categories);
            }

            // Step 2: Save sizes for the product with stock and price
            if ($request->has('sizes')) {
                foreach ($request->sizes as $size) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size_id'    => $size['size'],
                        'price'      => $size['price'],
                        'stock'      => 0,    // Store stock for each size
                        'is_active'  => true, // 👈 added for consistency
                    ]);
                }
            }

            // Step 3: Save main image
            if ($request->hasFile('main_image')) {
                $main     = $request->file('main_image');
                $mainName = time() . '_main.' . $main->extension();
                $main->storeAs('public/products', $mainName);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'products/' . $mainName,
                    'main_image' => 'products/' . $mainName,
                    'is_primary' => true,
                ]);
            }

            // Step 4: Save additional images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $imgName = time() . '_' . uniqid() . '.' . $img->extension();
                    $img->storeAs('public/products', $imgName);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'products/' . $imgName,
                        'is_primary' => false,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product added successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product.',
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            // ✅ Fetch the product with only active sizes, plus images and categories
            $product = Product::with([
                'images',
                'categories',
                'sizes' => function ($query) {
                    $query->where('is_active', true);
                },
            ])->findOrFail($id);

            // ✅ Get categories
            $categories = Category::with('children')->whereNull('parent_id')->get();
            $sizes      = ProductSizeItem::all();

            $selectedCategories = $product->categories->pluck('id')->toArray();

            // ✅ Pass selected sizes (include id, size_id and price for form binding)
            $selectedSizes = $product->sizes->map(function ($size) {
                return [
                    'id'      => $size->id,      // 👈 Important: we need ID to update later
                    'size_id' => $size->size_id, // Size reference
                    'price'   => $size->price,   // Price
                ];
            });

            $renderedCategories = $this->renderCategories($categories, $selectedCategories);

            return view('admin.products.form', compact('product', 'categories', 'sizes', 'selectedSizes', 'renderedCategories'));

        } catch (\Exception $e) {
            Log::error('Product Edit Error: ' . $e->getMessage());
            Alert::error('Error', 'Product not found.');
            return redirect()->route('products');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // ✅ Validation
            $request->validate([
                'name'          => 'required|string|max:255',
                'desc'          => 'nullable|string',
                'categories'    => 'required|array',
                'categories.*'  => 'exists:categories,id',
                'sizes'         => 'nullable|array',
                'sizes.*.size'  => 'required|exists:product_size_items,id',
                'sizes.*.price' => 'required|numeric|min:0',
                'main_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'images.*'      => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            // ✅ Find product
            $product = Product::with(['categories', 'sizes', 'images'])->findOrFail($id);

            // ✅ Update base fields
            $product->update([
                'name' => $request->name,
                'desc' => $request->desc,
            ]);

            // ✅ Sync categories
            $product->categories()->sync($request->categories);

            // ✅ Handle sizes
            $existingSizeIds = $product->sizes->pluck('id')->toArray();
            $submittedIds    = [];

            if ($request->has('sizes')) {
                foreach ($request->sizes as $sizeData) {
                    // Check by product_id + size_id (not only id)
                    $productSize = $product->sizes()
                        ->where('size_id', $sizeData['size'])
                        ->first();

                    if ($productSize) {
                        // Update existing (even if inactive before)
                        $productSize->update([
                            'price'     => $sizeData['price'],
                            'is_active' => true,
                        ]);
                        $submittedIds[] = $productSize->id;
                    } else {
                        // Create new if not exists at all
                        $newSize = $product->sizes()->create([
                            'size_id'   => $sizeData['size'],
                            'price'     => $sizeData['price'],
                            'stock'     => 0,
                            'is_active' => true,
                        ]);
                        $submittedIds[] = $newSize->id;
                    }
                }
            }

            // ✅ Deactivate removed sizes
            $toDeactivate = array_diff($existingSizeIds, $submittedIds);
            if (! empty($toDeactivate)) {
                $product->sizes()->whereIn('id', $toDeactivate)->update(['is_active' => false]);
            }

            // ✅ Replace main image if uploaded
            if ($request->hasFile('main_image')) {
                $path = $request->file('main_image')->store('products', 'public');
                $product->images()->updateOrCreate(
                    ['is_primary' => true],
                    ['image_path' => $path, 'is_primary' => true]
                );
            }

            // ✅ Add additional images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            return redirect()->route('products')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            Log::error('Product update error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to update product. Please try again later.');
        }
    }

    public function deleteImage($id)
    {
        try {
            $image = ProductImage::findOrFail($id);

            if (Storage::exists('public/' . $image->image_path)) {
                Storage::delete('public/' . $image->image_path);
            }

            $image->delete();

            return response()->json(['status' => 'success', 'message' => 'Image deleted']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error deleting image']);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($id);

            // Delete related images from storage
            foreach ($product->images as $image) {
                if (Storage::exists('public/' . $image->image_path)) {
                    Storage::delete('public/' . $image->image_path);
                }
            }

            // Delete product (images, sizes, categories will be handled if relations are set with cascade or manually)
            $product->delete();

            DB::commit();

            Alert::success('Success', 'Product deleted successfully!');
            return redirect()->route('products');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product Delete Error: ' . $e->getMessage());
            Alert::error('Error', 'Failed to delete product.');
            return back();
        }
    }

}

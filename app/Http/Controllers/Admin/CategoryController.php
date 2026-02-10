<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:categories')->only('index');
        $this->middleware('permission:category add')->only('store');
        $this->middleware('permission:category edit')->only('edit', 'update');
        $this->middleware('permission:category delete')->only('destroy');
    }

    public function index()
    {
        try {
            $categories = Category::with('parent')->get();
            $parents = Category::all();

            return view('admin.category.show-category', compact('categories', 'parents'));
        } catch (Exception $e) {
            Log::error('Category Index Error: ' . $e->getMessage());
            Alert::error('Error', 'Something went wrong while loading categories.');
            return back();
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'parent_id' => 'nullable|exists:categories,id'
            ]);

            Category::create($request->only('name', 'parent_id'));

            Alert::success('Success', 'Category Added Successfully!');
            return redirect()->route('categories');
        } catch (Exception $e) {
            Log::error('Category Store Error: ' . $e->getMessage());
            Alert::error('Error', 'Failed to add category.');
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            $parents = Category::all();

            return view('admin.category.form', compact('category', 'parents'));
        } catch (Exception $e) {
            Log::error('Category Edit Error: ' . $e->getMessage());
            Alert::error('Error', 'Category not found or failed to load edit form.');
            return redirect()->route('categories');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'parent_id' => 'nullable|exists:categories,id'
            ]);

            $category = Category::findOrFail($id);
            $category->update($request->only('name', 'parent_id'));

            Alert::success('Success', 'Category Updated Successfully!');
            return redirect()->route('categories');
        } catch (Exception $e) {
            Log::error('Category Update Error: ' . $e->getMessage());
            Alert::error('Error', 'Failed to update category.');
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Delete related products and their images
            foreach ($category->products as $product) {
                foreach ($product->images as $image) {
                    if (Storage::exists('public/' . $image->image_path)) {
                        Storage::delete('public/' . $image->image_path);
                    }
                    $image->delete();
                }
                $product->delete();
            }

            // Finally delete the category
            $category->delete();

            Alert::success('Success', 'Category and related products/images deleted successfully!');
            return redirect()->route('categories');
        } catch (\Exception $e) {
            Log::error('Category Delete Error: ' . $e->getMessage());
            Alert::error('Error', 'Failed to delete category.');
            return back();
        }
    }
}

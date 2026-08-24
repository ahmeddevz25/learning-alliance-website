@extends('admin.layouts')
@section('content')
    <html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
        data-assets-path="../assets/" data-template="vertical-menu-template-free">
    <style>
        .size-entry {
            position: relative;
        }

        .sk-wave {
            display: inline-block;
            position: relative;
            width: 50px;
            height: 10px;
        }

        .sk-wave-rect {
            display: inline-block;
            position: absolute;
            width: 6px;
            height: 100%;
            background-color: #007bff;
            animation: sk-wave 1.2s infinite ease-in-out;
        }

        .sk-wave-rect:nth-child(1) {
            left: 0;
            animation-delay: 0s;
        }

        .sk-wave-rect:nth-child(2) {
            left: 10px;
            animation-delay: 0.1s;
        }

        .sk-wave-rect:nth-child(3) {
            left: 20px;
            animation-delay: 0.2s;
        }

        .sk-wave-rect:nth-child(4) {
            left: 30px;
            animation-delay: 0.3s;
        }

        .sk-wave-rect:nth-child(5) {
            left: 40px;
            animation-delay: 0.4s;
        }

        @keyframes sk-wave {

            0%,
            100% {
                transform: scaleY(0.4);
            }

            50% {
                transform: scaleY(1);
            }
        }

        /* Position the cross icon inside the size entry field */
    </style>

    <body>
        @include('sweetalert::alert')
        <!-- Layout wrapper -->

        <div class="layout-wrapper layout-content-navbar">

            <div class="layout-container">
                <div class="layout-page">
                    <div class="card mt-5 shadow-sm rounded" style="margin: 31px;">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                            <h5 class="card-title mb-0 text-md-start text-center">All Products</h5>
                            @can('product add')
                                <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                    data-bs-target="#addProductModal">
                                    <i class="bx bx-plus icon-sm"></i>
                                    <span class="d-none d-sm-inline-block">Add Product</span>
                                </button>
                            @endcan
                        </div>


                        <!-- Table Loader Container -->
                        <div id="table-loader-container" style="min-height: 300px; display: flex; justify-content: center; align-items: center;">
                            <div class="text-center">
                                <div class="sk-wave mx-auto">
                                    <div class="sk-wave-rect"></div>
                                    <div class="sk-wave-rect"></div>
                                    <div class="sk-wave-rect"></div>
                                    <div class="sk-wave-rect"></div>
                                    <div class="sk-wave-rect"></div>
                                </div>
                                <div class="mt-3 text-primary fw-bold">Loading Products...</div>
                            </div>
                        </div>

                        <div class="table-responsive" id="product-table-wrapper" style="display: none;">
                            <table class="table table-hover align-middle table-striped border-top" id="example">
                                <thead class="table-light">
                                    <tr class="text-muted text-uppercase small">
                                        <th>Sr. No</th>
                                        <th>Name</th>
                                        <th>Sizes and Price</th>
                                        <th>Main Image</th>
                                        <th>Images</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $key => $product)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="fw-semibold">{{ $product->name }}</td>

                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm show-sizes"
                                                    data-product="{{ $product->id }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Click to view sizes">
                                                    View Sizes
                                                </button>

                                                <!-- Hidden HTML for Sizes -->
                                                <div class="sizes-html d-none">
                                                    <div class="d-flex flex-wrap gap-3">
                                                        @foreach ($product->sizes as $size)
                                                            <div class="size-card d-flex align-items-center gap-3 p-3 shadow-sm rounded-3 bg-light"
                                                                style="min-width: 200px; border: 1px solid #e0e0e0;">

                                                                <!-- Size Badge -->
                                                                <span class="badge bg-primary px-3 py-2"
                                                                    style="font-size: 0.9rem; border-radius: 12px;">
                                                                    {{ $size->sizeItem->size }}
                                                                </span>

                                                                <!-- Price Badge -->
                                                                <span class="badge bg-success px-3 py-2"
                                                                    style="font-size: 0.9rem; border-radius: 12px;">
                                                                    Rs. {{ number_format($size->price) }}
                                                                </span>

                                                                {{-- Stock (optional, if needed later) --}}
                                                                {{--
                <span class="badge {{ $size->stock > 0 ? 'bg-info' : 'bg-danger' }} px-3 py-2"
                      style="font-size: 0.9rem; border-radius: 12px;">
                    {{ $size->stock > 0 ? $size->stock . ' in stock' : 'Out of stock' }}
                </span>
                --}}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $mainImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                                                @endphp
                                                @if ($mainImage && $mainImage->image_path)
                                                    <img src="{{ Storage::disk('public')->url($mainImage->image_path) }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ $product->name }}"
                                                        alt="{{ $product->name }}" class="rounded-circle"
                                                        style="object-fit: cover; width: 60px; height: 60px;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>
                                                <ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                                                    @php
                                                        $otherImages = $product->images->filter(function ($img) use ($mainImage) {
                                                            return $mainImage ? $img->id !== $mainImage->id : !$img->is_primary;
                                                        });
                                                    @endphp
                                                    @foreach ($otherImages->take(3) as $image)
                                                        <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ $product->name }}">
                                                            <img src="{{ Storage::disk('public')->url($image->image_path) }}"
                                                                alt="{{ $product->name }}" class="rounded-circle"
                                                                style="object-fit: cover; width: 40px; height: 40px;">
                                                        </li>
                                                    @endforeach

                                                    @if ($otherImages->count() > 3)
                                                        <li class="avatar avatar-xs pull-up">
                                                            <span
                                                                class="avatar-initial bg-secondary text-white fs-6 fw-bold rounded-circle"
                                                                style="width: 32px; height: 32px;">
                                                                +{{ $otherImages->count() - 3 }}
                                                            </span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </td>

                                            <td>
                                                @forelse($product->categories as $cat)
                                                    <span class="badge bg-primary">{{ $cat->name }}</span>
                                                @empty
                                                    <span class="text-muted">—</span>
                                                @endforelse
                                            </td>


                                            <!-- Description -->
                                            <td>{!! Str::limit(strip_tags($product->desc), 50) !!}</td>

                                            <!-- Actions -->
                                            <!-- Actions -->
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">

                                                    @can('product edit')
                                                        <a href="{{ route('products.edit', $product->id) }}"
                                                            data-bs-toggle="tooltip" data-bs-offset="0,8"
                                                            data-bs-placement="top" data-bs-custom-class="tooltip-icon-info"
                                                            data-bs-original-title="Edit Product" class="text-primary fs-5 edit-product-btn">
                                                            <i class='bx bx-edit'></i>
                                                        </a>
                                                    @endcan

                                                    @can('product delete')
                                                        <a href="{{ route('products.delete', $product->id) }}"
                                                            data-bs-toggle="tooltip" data-bs-offset="0,8"
                                                            data-bs-placement="top" data-bs-custom-class="tooltip-icon-info"
                                                            data-bs-original-title="Delete Product"
                                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                                            class="text-danger fs-5">
                                                            <i class='bx bx-trash'></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No products available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Modal for Viewing Product Sizes -->
                        <div class="modal fade" id="productSizesModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content shadow-lg border-0 rounded-3">
                                    <!-- Header -->
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title text-white">
                                            <i class="bi bi-rulers me-2"></i> Product Sizes
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <!-- Body -->
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Size</th>
                                                        <th>Price (Rs.)</th>
                                                        <th>Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-sizes-table">
                                                    <!-- Sizes will be injected dynamically via JS -->
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted py-4">
                                                            <em>No sizes available</em>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle me-1"></i> Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Add Product Modal -->
                        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content shadow-lg border-0 rounded-3">
                                    <!-- Header -->
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white" id="addProductModalLabel">
                                            <i class="bx bx-plus-circle me-2"></i> Add New Product
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('products.store') }}" method="POST" id="product-form" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="row g-4">
                                                <!-- Left Column -->
                                                <div class="col-md-7">
                                                    <!-- Product Name -->
                                                    <div class="mb-4">
                                                        <label for="name" class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" class="form-control form-control-lg" placeholder="e.g. Summer Polo Shirt" required>
                                                    </div>

                                                    <!-- Product Description -->
                                                    <div class="mb-4">
                                                        <label for="desc" class="form-label fw-semibold">Product Description</label>
                                                        <textarea name="desc" id="desc" class="form-control" placeholder="Write a brief description...">{{ old('desc') }}</textarea>
                                                    </div>

                                                    <!-- Categories -->
                                                    <div class="mb-4">
                                                        <label class="form-label fw-semibold">Categories</label>
                                                        <div class="bg-light p-3 rounded border shadow-sm" style="max-height:220px; overflow-y:auto;">
                                                            {!! $renderedCategories !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Right Column -->
                                                <div class="col-md-5">
                                                    <!-- Main Image -->
                                                    <div class="mb-4">
                                                        <label for="main_image" class="form-label fw-semibold">Main Image <span class="text-danger">*</span></label>
                                                        <input type="file" name="main_image" class="form-control" accept="image/*" required>
                                                        <div class="form-text">Primary image shown on the shop page.</div>
                                                    </div>

                                                    <!-- Additional Images -->
                                                    <div class="mb-4">
                                                        <label for="images" class="form-label fw-semibold">Additional Images <span class="text-muted small">(Optional)</span></label>
                                                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                                                        <div class="form-text">Select multiple images for the gallery.</div>
                                                    </div>

                                                    <!-- Sizes and Prices -->
                                                    <div class="mb-4">
                                                        <label class="form-label fw-semibold">Sizes & Prices <span class="text-danger">*</span></label>
                                                        <div class="bg-light p-3 rounded border shadow-sm">
                                                            <div id="size-container">
                                                                <div class="size-entry mb-3 position-relative d-flex gap-2 align-items-center">
                                                                    <div class="flex-grow-1 d-flex gap-2">
                                                                        <select name="sizes[0][size]" class="form-select" required>
                                                                            <option value="" disabled selected>Select Size</option>
                                                                            @foreach ($sizes as $size)
                                                                                <option value="{{ $size->id }}" data-size="{{ $size->size }}">{{ $size->size }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text bg-white">Rs.</span>
                                                                            <input type="number" name="sizes[0][price]" class="form-control" placeholder="Price" step="0.01" required>
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-size-btn" style="display:none;" title="Remove Size">
                                                                        <i class="bx bx-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-outline-primary w-100" id="add-size-btn">
                                                                <i class="bx bx-plus"></i> Add Another Size
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer -->
                                        <div class="modal-footer bg-light border-top">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="bx bx-save me-1"></i> Save Product</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /Modal -->
                        <!-- Global Loader -->
                        <div id="ajax-loader"
                            style="
                                display: none;
                                position: fixed;
                                top: 0; left: 0;
                                width: 100%; height: 100%;
                                background: rgba(255,255,255,0.7);
                                z-index: 2000;
                                text-align: center;
                                padding-top: 200px;
                                font-size: 20px;
                                color: #333;
                            ">
                            <div class="spinner-border text-primary" role="status"></div>
                            <div>Saving product, please wait...</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                ClassicEditor
                    .create(document.querySelector('#desc'))
                    .catch(error => {
                        console.error(error);
                    });
                    
                // Fix for CKEditor 5 inside Bootstrap 5 Modal
                document.addEventListener('focusin', function (e) {
                    if (e.target.closest('.ck-editor__editable, .ck-editor__main, .ck-balloon-panel')) {
                        e.stopImmediatePropagation();
                    }
                });
            });
        </script>

        <script>
            document.getElementById('add-size-btn').addEventListener('click', function() {
                var sizeContainer = document.getElementById('size-container');
                var sizeEntries = sizeContainer.querySelectorAll('.size-entry');
                var index = sizeEntries.length; // Get the next index to ensure uniqueness

                // Create a new size entry div
                var newSizeEntry = document.createElement('div');
                newSizeEntry.classList.add('size-entry', 'mb-3', 'position-relative', 'd-flex', 'gap-2', 'align-items-center');
                newSizeEntry.setAttribute('data-index', index);

                // Create inner flex div for side-by-side layout
                var innerDiv = document.createElement('div');
                innerDiv.classList.add('flex-grow-1', 'd-flex', 'gap-2');

                // Add select element for size
                var select = document.createElement('select');
                select.name = `sizes[${index}][size]`;
                select.classList.add('form-select');
                select.setAttribute('required', 'true');

                // Add the default option
                var defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.disabled = true;
                defaultOption.selected = true;
                defaultOption.textContent = 'Select Size';
                select.appendChild(defaultOption);

                // Loop through the existing sizes and add options dynamically
                @foreach ($sizes as $size)
                    var option = document.createElement('option');
                    option.value = "{{ $size->id }}";
                    option.dataset.size = "{{ $size->size }}";
                    option.textContent = "{{ $size->size }}";
                    select.appendChild(option);
                @endforeach

                // Add input group for price
                var inputGroup = document.createElement('div');
                inputGroup.classList.add('input-group');

                var inputGroupText = document.createElement('span');
                inputGroupText.classList.add('input-group-text', 'bg-white');
                inputGroupText.textContent = 'Rs.';

                var inputPrice = document.createElement('input');
                inputPrice.type = 'number';
                inputPrice.name = `sizes[${index}][price]`;
                inputPrice.classList.add('form-control');
                inputPrice.placeholder = 'Price';
                inputPrice.step = '0.01';
                inputPrice.required = true;

                inputGroup.appendChild(inputGroupText);
                inputGroup.appendChild(inputPrice);

                // Create the remove button (trash icon)
                var removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.classList.add('btn', 'btn-outline-danger', 'btn-sm', 'remove-size-btn');
                removeBtn.title = 'Remove Size';
                removeBtn.innerHTML = "<i class='bx bx-trash'></i>"; 

                // Add the event listener for the remove button
                removeBtn.addEventListener('click', function() {
                    newSizeEntry.remove(); // Remove the size entry when the cross button is clicked
                    updateSizeOptions(); // Update dropdown options after removal
                });

                // Append select and price input to inner div
                innerDiv.appendChild(select);
                innerDiv.appendChild(inputGroup);

                // Append inner div and remove button to the new size entry div
                newSizeEntry.appendChild(innerDiv);
                newSizeEntry.appendChild(removeBtn);

                // Append the new size entry to the size container
                sizeContainer.appendChild(newSizeEntry);
            });
        </script>

        <script>
            // Run on any change in a size select dropdown
            document.addEventListener('change', function(e) {
                if (e.target.matches('select[name^="sizes"][name$="[size]"]')) {
                    updateSizeOptions();
                }
            });

            // Also run this when a new size field is added
            document.getElementById('add-size-btn').addEventListener('click', function() {
                setTimeout(() => {
                    updateSizeOptions();
                }, 100); // slight delay to let DOM update
            });

            function updateSizeOptions() {
                const allSelects = document.querySelectorAll('select[name^="sizes"][name$="[size]"]');
                const selectedValues = [];

                // First, collect all selected values
                allSelects.forEach(select => {
                    const val = select.value;
                    if (val) selectedValues.push(val);
                });

                // Then, update all selects
                allSelects.forEach(select => {
                    const currentVal = select.value;

                    // Re-enable all first
                    select.querySelectorAll('option').forEach(opt => {
                        opt.disabled = false;
                    });

                    // Disable all selected values except the one in the current select
                    selectedValues.forEach(val => {
                        if (val !== currentVal) {
                            const optionToDisable = select.querySelector(`option[value="${val}"]`);
                            if (optionToDisable) optionToDisable.disabled = true;
                        }
                    });
                });
            }

            // Function to handle the removal of a size field when the cross icon is clicked
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-size-btn')) {
                    const sizeField = e.target.closest('.size-entry');
                    if (sizeField) {
                        sizeField.remove(); // Remove the size field
                        updateSizeOptions(); // Update dropdown options after removal
                    }
                }
            });
            // Run once on page load (in case there are pre-filled values)
            document.addEventListener('DOMContentLoaded', updateSizeOptions);
        </script>

        <script>
            $(document).ready(function() {
                $('#product-form').on('submit', function(e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    // Show loader
                    $('#ajax-loader').show();

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#ajax-loader').hide();

                            if (response.success) {
                                toastr.success(response.message);
                                $('#addProductModal').modal('hide');
                                $('#product-form')[0].reset();
                                window.location.reload();
                            } else {
                                toastr.error(response.message || 'Something went wrong.');
                            }
                        },
                        error: function(xhr) {
                            $('#ajax-loader').hide();

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let firstError = Object.values(errors)[0][0];
                                toastr.error(firstError);
                            } else {
                                toastr.error('Something went wrong. Please try again.');
                            }
                        }
                    });
                });
            });
        </script>
        {{-- Script --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Category Name Click => Toggle Checkbox
                document.querySelectorAll(".category-label").forEach(function(label) {
                    label.addEventListener("click", function() {
                        let checkbox = this.closest("label").querySelector("input[type=checkbox]");
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event("change")); // trigger change event
                    });
                });

                // Parent Check => Auto Select Children
                document.querySelectorAll(".category-checkbox").forEach(function(checkbox) {
                    checkbox.addEventListener("change", function() {
                        let li = this.closest("li");
                        if (li) {
                            li.querySelectorAll("input[type=checkbox]").forEach(function(
                                childCheckbox) {
                                childCheckbox.checked = checkbox.checked;
                            });
                        }
                    });
                });

                // Hide Loader when DOM is ready
                setTimeout(function() {
                    let loader = document.getElementById('table-loader-container');
                    let tableWrapper = document.getElementById('product-table-wrapper');
                    if(loader) {
                        loader.style.display = 'none';
                    }
                    if(tableWrapper) {
                        tableWrapper.style.display = 'block';
                    }
                }, 300);
            });
        </script>

        <script>
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.show-sizes');
                if (!btn) return;

                const productId = btn.dataset.product;
                const tr = btn.closest('tr');
                const html = tr.querySelector('.sizes-html')?.innerHTML || '<div class="p-3">No sizes available.</div>';

                const modalEl = document.getElementById('productSizesModal');
                modalEl.querySelector('.modal-title').textContent = `Sizes for Product #${productId}`;
                modalEl.querySelector('.modal-body').innerHTML = html;

                const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                bsModal.show();
            });
        </script>

        <!-- Edit Product Modal Placeholder -->
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content shadow-lg border-0 rounded-3" id="editModalContent">
                    <!-- Dynamic AJAX Content -->
                    <div class="p-5 text-center">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div class="mt-2 text-muted">Loading product details...</div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Open Edit Modal via AJAX
            $(document).on('click', '.edit-product-btn', function(e) {
                e.preventDefault();
                let editUrl = $(this).attr('href');
                let modalEl = $('#editProductModal');
                let modalContent = $('#editModalContent');
                
                // Show modal with loading state
                modalContent.html(`
                    <div class="p-5 text-center">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div class="mt-2 text-muted">Loading product details...</div>
                    </div>
                `);
                modalEl.modal('show');

                // Fetch content
                $.get(editUrl, function(response) {
                    modalContent.html(response);
                }).fail(function() {
                    modalContent.html(`
                        <div class="p-4 text-center text-danger">
                            <i class="bx bx-error-circle fs-1 mb-2"></i>
                            <h5>Failed to load data.</h5>
                            <button class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                        </div>
                    `);
                });
            });
        </script>
    </body>

    </html>
@endsection

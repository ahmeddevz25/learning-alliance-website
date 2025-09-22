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


                        <div class="table-responsive">
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
                                                    $mainImage = $product->images->firstWhere('is_primary', true);
                                                @endphp
                                                @if ($mainImage && $mainImage->image_path)
                                                    <img src="{{ asset('storage/' . $mainImage->image_path) }}"
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
                                                        $otherImages = $product->images->filter(function ($img) {
                                                            return !$img->is_primary;
                                                        });
                                                    @endphp
                                                    @foreach ($otherImages->take(3) as $image)
                                                        <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ $product->name }}">
                                                            <img src="{{ asset('storage/' . $image->image_path) }}"
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
                                                            data-bs-original-title="Edit Product" class="text-primary fs-5">
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
                        <div class="modal fade" id="addProductModal" tabindex="-1"
                            aria-labelledby="addProductModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('products.store') }}" method="POST" id="product-form"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <!-- Product Name -->
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Product Name</label>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>

                                            <!-- Sizes and Prices -->
                                            <div class="mb-3">
                                                <label for="sizes" class="form-label">Product Sizes</label>
                                                <div id="size-container">
                                                    <div class="size-entry mb-3 position-relative">
                                                        <select name="sizes[0][size]" class="form-control mb-2" required>
                                                            <option value="" disabled selected>Select Size</option>
                                                            @foreach ($sizes as $size)
                                                                <option value="{{ $size->id }}"
                                                                    data-size="{{ $size->size }}">{{ $size->size }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="number" name="sizes[0][price]"
                                                            class="form-control mb-2" placeholder="Price (Rs.)"
                                                            step="0.01" required>
                                                        {{-- <input type="number" name="sizes[0][stock]"
                                                            class="form-control mb-2" placeholder="Stock Quantity"
                                                            required> --}}
                                                        <!-- Cross button to remove size -->
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-size-btn"
                                                            style="position: absolute; right: 5px; top: 5px; display:none;">&times;</button>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary" id="add-size-btn">Add
                                                    Size</button>
                                            </div>




                                            <!-- Product Description -->
                                            <div class="mb-3">
                                                <label for="desc" class="form-label">Product Description</label>
                                                <textarea name="desc" id="desc" class="form-control">{{ old('desc') }}</textarea>
                                            </div>

                                            <!-- Main Image -->
                                            <div class="mb-3">
                                                <label for="main_image" class="form-label">Main Image</label>
                                                <input type="file" name="main_image" class="form-control"
                                                    accept="image/*" required>
                                            </div>

                                            <!-- Additional Images -->
                                            <div class="mb-3">
                                                <label for="images" class="form-label">Additional Images</label>
                                                <input type="file" name="images[]" class="form-control"
                                                    accept="image/*" multiple>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Categories</label>
                                                <div
                                                    style="max-height:250px; overflow-y:auto; border:1px solid #ddd; padding:10px; border-radius:5px;">
                                                    {!! $renderedCategories !!}
                                                </div>
                                            </div>






                                            <!-- Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Add Product</button>
                                            </div>
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
            });
        </script>

        <script>
            document.getElementById('add-size-btn').addEventListener('click', function() {
                var sizeContainer = document.getElementById('size-container');
                var sizeEntries = sizeContainer.querySelectorAll('.size-entry');
                var index = sizeEntries.length; // Get the next index to ensure uniqueness

                // Create a new size entry div
                var newSizeEntry = document.createElement('div');
                newSizeEntry.classList.add('size-entry', 'mb-3', 'position-relative');
                newSizeEntry.setAttribute('data-index', index);

                // Add select element for size
                var select = document.createElement('select');
                select.name = `sizes[${index}][size]`;
                select.classList.add('form-control', 'mb-2'); // Added mb-2 here
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

                // Add price input field
                var inputPrice = document.createElement('input');
                inputPrice.type = 'number';
                inputPrice.name = `sizes[${index}][price]`;
                inputPrice.classList.add('form-control', 'mb-2');
                inputPrice.placeholder = 'Price (Rs.)';
                inputPrice.step = '0.01';
                inputPrice.required = true;

                // Add stock input field
                var inputStock = document.createElement('input');
                inputStock.type = 'number';
                inputStock.name = `sizes[${index}][stock]`;
                inputStock.classList.add('form-control', 'mb-2');
                inputStock.required = true;

                // Create the remove button (cross icon)
                var removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'remove-size-btn');
                removeBtn.style.position = 'absolute';
                removeBtn.style.right = '5px';
                removeBtn.style.top = '5px';
                removeBtn.style.display = 'block';
                removeBtn.innerHTML = '&times;'; // Cross icon

                // Add the event listener for the remove button
                removeBtn.addEventListener('click', function() {
                    newSizeEntry.remove(); // Remove the size entry when the cross button is clicked
                    updateSizeOptions(); // Update dropdown options after removal
                });

                // Append select, price input, stock input, and remove button to the new size entry div
                newSizeEntry.appendChild(select);
                newSizeEntry.appendChild(inputPrice);
                // newSizeEntry.appendChild(inputStock);
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
    </body>

    </html>
@endsection

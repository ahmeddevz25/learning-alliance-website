@extends('admin.layouts')
@section('content')
    <html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
        data-assets-path="../assets/" data-template="vertical-menu-template-free">
    <style>
        /* Force Toastr styles in case theme is overriding */

        .toast-success {
            background-color: #51A351 !important;
        }

        .toast-error {
            background-color: #BD362F !important;
        }

        .toast-info {
            background-color: #2F96B4 !important;
        }

        .toast-warning {
            background-color: #F89406 !important;
        }
    </style>

    <body>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">

                <div class="layout-page">


                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Update</span> Product</h4>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h2>Update Product</h2>

                                        @if (session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        {{-- Validation Errors --}}
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <form action="{{ route('products.update', $product->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            {{-- Product Name --}}
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Product Name</label>
                                                <input type="text" name="name" class="form-control" id="name"
                                                    value="{{ old('name', $product->name) }}" required>
                                            </div>

                                            {{-- Size and Price --}}
                                            <div class="mb-3">
                                                <label for="sizes" class="form-label">Product Sizes</label>

                                                <!-- Loop through existing sizes -->
                                                <div id="size-container">
                                                    @foreach ($product->sizes as $index => $size)
                                                        <div class="size-entry mb-3">
                                                            <div class="row align-items-end">
                                                                <!-- Hidden ID -->
                                                                <input type="hidden" name="sizes[{{ $index }}][id]"
                                                                    value="{{ $size->id }}">

                                                                <!-- Size Column -->
                                                                <div class="col-md-5">
                                                                    <label class="form-label">Size</label>
                                                                    <select name="sizes[{{ $index }}][size]"
                                                                        class="form-control" required>
                                                                        <option value="" disabled>Select Size</option>
                                                                        @foreach ($sizes as $availableSize)
                                                                            <option value="{{ $availableSize->id }}"
                                                                                {{ $size->size_id == $availableSize->id ? 'selected' : '' }}>
                                                                                {{ $availableSize->size }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <!-- Price Column -->
                                                                <div class="col-md-5">
                                                                    <label class="form-label">Price (Rs.)</label>
                                                                    <input type="number"
                                                                        name="sizes[{{ $index }}][price]"
                                                                        class="form-control" value="{{ $size->price }}"
                                                                        placeholder="Enter price" step="0.01" required>
                                                                </div>

                                                                <!-- Remove -->
                                                                <div class="col-md-2">
                                                                    <label class="form-label d-block">&nbsp;</label>
                                                                    <button type="button"
                                                                        class="btn btn-outline-danger w-100 remove-size-btn">Remove</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>

                                                <button type="button" class="btn btn-primary mt-2" id="add-size-btn">Add
                                                    Size</button>
                                            </div>



                                            <!-- Categories -->
                                            <div class="mb-3">
                                                <label class="form-label">Categories</label>
                                                <div
                                                    style="max-height:250px; overflow-y:auto; border:1px solid #ddd; padding:10px; border-radius:5px;">
                                                    {!! $renderedCategories !!}
                                                </div>


                                                {{-- Description --}}
                                                <div class="mb-3">
                                                    <label for="desc" class="form-label">Product Description</label>
                                                    <textarea name="desc" id="desc" class="form-control" rows="4">{{ old('desc', $product->desc) }}</textarea>
                                                </div>

                                                {{-- Existing Images Preview --}}
                                                @if ($product->images->count())
                                                    <div class="mb-3">
                                                        <label class="form-label">Existing Images</label>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @foreach ($product->images as $img)
                                                                <div style="position: relative; display: inline-block;">
                                                                    <img src="{{ asset('storage/' . $img->image_path) }}"
                                                                        alt="" class="rounded"
                                                                        style="width: 70px; height: 70px; object-fit: cover; border: 1px solid #ccc;">

                                                                    @if ($img->is_primary)
                                                                        <span
                                                                            class="badge bg-success position-absolute top-0 start-0 m-1">Primary</span>
                                                                    @endif

                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-image-btn"
                                                                        data-image-id="{{ $img->id }}"
                                                                        style="padding: 2px 6px; line-height: 1;">&times;</button>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- Upload Main Image --}}
                                                <div class="mb-3">
                                                    <label for="main_image" class="form-label">Select New Main Image</label>
                                                    <input type="file" name="main_image" class="form-control"
                                                        id="main_image" accept="image/*">
                                                    <small class="text-muted">Optional: this will replace the current main
                                                        image.</small>
                                                </div>

                                                {{-- Upload More Images --}}
                                                <div class="mb-3">
                                                    <label for="images" class="form-label">Upload More Images</label>
                                                    <input type="file" name="images[]" class="form-control"
                                                        id="images" multiple accept="image/*">
                                                    <small class="text-muted">These will be added as additional
                                                        images.</small>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Update Product</button>
                                        </form>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <script async defer src="https://buttons.github.io/buttons.js"></script>
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
            $(document).on('click', '.delete-image-btn', function() {
                var imageId = $(this).data('image-id');
                var button = $(this);

                // if (confirm('Are you sure you want to delete this image?')) {
                $.ajax({
                    url: "{{ url('/product-image') }}/" + imageId,
                    type: 'POST', // Use POST with _method=DELETE
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            button.closest('div').remove();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message || 'Failed to delete image');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('AJAX Error: ' + xhr.responseText);
                        console.log(xhr);
                    }
                });
                // }
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sizeContainer = document.getElementById('size-container');
                const addSizeBtn = document.getElementById('add-size-btn');

                // Add new size row
                addSizeBtn.addEventListener('click', function() {
                    const index = sizeContainer.querySelectorAll('.size-entry').length;

                    const newSizeEntry = document.createElement('div');
                    newSizeEntry.classList.add('size-entry', 'mb-3');

                    newSizeEntry.innerHTML = `
            <div class="row align-items-end">
                <!-- Size Column -->
                <div class="col-md-5">
                    <label class="form-label">Size</label>
                    <select name="sizes[${index}][size]" class="form-control" required>
                        <option value="" disabled selected>Select Size</option>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->size }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Column -->
                <div class="col-md-5">
                    <label class="form-label">Price (Rs.)</label>
                    <input type="number" name="sizes[${index}][price]"
                        class="form-control"
                        placeholder="Enter price"
                        step="0.01" required>
                </div>

                <!-- Remove Column -->
                <div class="col-md-2">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger w-100 remove-size-btn">
                        Remove
                    </button>
                </div>
            </div>
        `;

                    sizeContainer.appendChild(newSizeEntry);
                });

                // ✅ Single event delegation for ALL remove buttons
                sizeContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-size-btn')) {
                        e.preventDefault();
                        const entry = e.target.closest('.size-entry');
                        if (entry) entry.remove();
                    }
                });
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
                    e.preventDefault(); // Prevent the default form submission

                    let formData = new FormData(this); // Get the form data, including files

                    // Show the loader while the product is being added
                    $('#loader').show();

                    // Send the data to the server via AJAX
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        contentType: false, // Tell jQuery not to set content type
                        processData: false, // Tell jQuery not to process the data
                        success: function(response) {
                            // Hide the loader after the request is complete
                            $('#loader').hide();

                            // Show the success alert
                            alert('Product added successfully!');
                            $('#productModal').modal('hide');
                            window.location.reload();
                        },
                        error: function(xhr) {
                            // Hide the loader if there's an error
                            $('#loader').hide();

                            // Show the error alert
                            alert('Failed to add product.');
                        }
                    });
                });
            });
        </script>

    </body>

    </html>
@endsection

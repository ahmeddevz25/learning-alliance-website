<!-- Header -->
<div class="modal-header bg-primary">
    <h5 class="modal-title text-white">
        <i class="bx bx-edit-alt me-2"></i> Edit Product: {{ $product->name }}
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('products.update', $product->id) }}" method="POST" id="edit-product-form" enctype="multipart/form-data">
    @csrf
    <div class="modal-body p-4">
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-md-7">
                <!-- Product Name -->
                <div class="mb-4">
                    <label for="edit_name" class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control form-control-lg" value="{{ old('name', $product->name) }}" required>
                </div>

                <!-- Product Description -->
                <div class="mb-4">
                    <label for="edit_desc" class="form-label fw-semibold">Product Description</label>
                    <textarea name="desc" id="edit_desc" class="form-control">{{ old('desc', $product->desc) }}</textarea>
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
                    <label for="edit_main_image" class="form-label fw-semibold">Update Main Image <span class="text-muted small">(Optional)</span></label>
                    <input type="file" name="main_image" id="edit_main_image" class="form-control" accept="image/*">
                    <div class="form-text">Leave blank to keep the current main image.</div>
                </div>

                <!-- Additional Images -->
                <div class="mb-4">
                    <label for="edit_images" class="form-label fw-semibold">Add More Images <span class="text-muted small">(Optional)</span></label>
                    <input type="file" name="images[]" id="edit_images" class="form-control" accept="image/*" multiple>
                    <div class="form-text">Select multiple images to append to the gallery.</div>
                </div>

                <!-- Existing Images Preview -->
                @if ($product->images->count())
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Existing Images</label>
                        <div class="d-flex flex-wrap gap-2 p-2 bg-light rounded border shadow-sm">
                            @foreach ($product->images as $img)
                                <div class="position-relative d-inline-block" id="image-container-{{ $img->id }}">
                                    <img src="{{ Storage::disk('public')->url($img->image_path) }}" 
                                         alt="" class="rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #ccc;">

                                    @if ($img->is_primary)
                                        <span class="badge bg-success position-absolute bottom-0 start-0 m-1" style="font-size: 0.6rem;">Primary</span>
                                    @endif

                                    <button type="button" 
                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-image-btn" 
                                            data-image-id="{{ $img->id }}"
                                            style="padding: 2px 5px; line-height: 1; border-radius: 50%;" title="Remove Image">&times;</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Sizes and Prices -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Sizes & Prices <span class="text-danger">*</span></label>
                    <div class="bg-light p-3 rounded border shadow-sm">
                        <div id="edit-size-container">
                            @foreach ($product->sizes as $index => $psize)
                                <div class="size-entry mb-3 position-relative d-flex gap-2 align-items-center">
                                    <input type="hidden" name="sizes[{{ $index }}][id]" value="{{ $psize->id }}">
                                    <div class="flex-grow-1 d-flex gap-2">
                                        <select name="sizes[{{ $index }}][size]" class="form-select" required>
                                            <option value="" disabled>Select Size</option>
                                            @foreach ($sizes as $availableSize)
                                                <option value="{{ $availableSize->id }}" {{ $psize->size_id == $availableSize->id ? 'selected' : '' }}>
                                                    {{ $availableSize->size }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">Rs.</span>
                                            <input type="number" name="sizes[{{ $index }}][price]" class="form-control" value="{{ $psize->price }}" placeholder="Price" step="0.01" required>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-size-btn" title="Remove Size">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary w-100 mt-2" id="edit-add-size-btn">
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
        <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="bx bx-save me-1"></i> Update Product</button>
    </div>
</form>

<script>
    // Initialize CKEditor
    if (document.querySelector('#edit_desc')) {
        ClassicEditor.create(document.querySelector('#edit_desc')).catch(err => console.error(err));
    }

    // Delete image logic inside modal
    $('.delete-image-btn').on('click', function() {
        var imageId = $(this).data('image-id');
        var container = $('#image-container-' + imageId);
        
        $.ajax({
            url: "{{ url('/product-image') }}/" + imageId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(response) {
                if (response.status === 'success') {
                    container.remove();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Failed to delete image');
                }
            },
            error: function(xhr) {
                toastr.error('AJAX Error: ' + xhr.responseText);
            }
        });
    });

    // Add Size logic inside modal
    document.getElementById('edit-add-size-btn').addEventListener('click', function() {
        var sizeContainer = document.getElementById('edit-size-container');
        var sizeEntries = sizeContainer.querySelectorAll('.size-entry');
        // Find maximum index to avoid collisions with existing hidden IDs
        var maxIndex = 0;
        sizeEntries.forEach(function(entry) {
            var selectName = entry.querySelector('select').name;
            var match = selectName.match(/\[(\d+)\]/);
            if (match && parseInt(match[1]) > maxIndex) {
                maxIndex = parseInt(match[1]);
            }
        });
        var index = maxIndex + 1; 

        var newSizeEntry = document.createElement('div');
        newSizeEntry.classList.add('size-entry', 'mb-3', 'position-relative', 'd-flex', 'gap-2', 'align-items-center');
        
        var innerDiv = document.createElement('div');
        innerDiv.classList.add('flex-grow-1', 'd-flex', 'gap-2');

        var select = document.createElement('select');
        select.name = `sizes[${index}][size]`;
        select.classList.add('form-select');
        select.setAttribute('required', 'true');
        
        var defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.disabled = true;
        defaultOption.selected = true;
        defaultOption.textContent = 'Select Size';
        select.appendChild(defaultOption);

        @foreach ($sizes as $size)
            var option = document.createElement('option');
            option.value = "{{ $size->id }}";
            option.dataset.size = "{{ $size->size }}";
            option.textContent = "{{ $size->size }}";
            select.appendChild(option);
        @endforeach

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

        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.classList.add('btn', 'btn-outline-danger', 'btn-sm', 'remove-size-btn');
        removeBtn.title = 'Remove Size';
        removeBtn.innerHTML = "<i class='bx bx-trash'></i>"; 

        innerDiv.appendChild(select);
        innerDiv.appendChild(inputGroup);

        newSizeEntry.appendChild(innerDiv);
        newSizeEntry.appendChild(removeBtn);

        sizeContainer.appendChild(newSizeEntry);
    });

    // Remove size logic inside modal (delegation)
    document.getElementById('edit-size-container').addEventListener('click', function(e) {
        var removeBtn = e.target.closest('.remove-size-btn');
        if (removeBtn) {
            var entry = removeBtn.closest('.size-entry');
            if (entry) {
                entry.remove();
            }
        }
    });

    // Form submission logic inside modal
    $('#edit-product-form').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $('#ajax-loader').show();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#ajax-loader').hide();
                if (response.success || response.status === 'success' || !response.error) {
                    toastr.success('Product updated successfully!');
                    $('#editProductModal').modal('hide');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    toastr.error('Something went wrong.');
                }
            },
            error: function(xhr) {
                $('#ajax-loader').hide();
                toastr.error('Failed to update product.');
            }
        });
    });
</script>

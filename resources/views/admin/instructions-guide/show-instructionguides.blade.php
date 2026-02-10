@extends('admin.layouts')
@section('content')
        @include('sweetalert::alert')
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <div class="layout-page">
                    <div class="card mt-5 shadow-sm rounded" style="margin: 31px;">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                            <h5 class="card-title mb-0 text-md-start text-center">All Instructions Guide</h5>
                            @can('instruction add')
                                <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                    data-bs-target="#addGuideModal">
                                    <i class="bx bx-plus icon-sm"></i>
                                    <span class="d-none d-sm-inline-block">Add Image</span>
                                </button>
                            @endcan
                        </div>

                        @php
                            // type → label
                            $typeLabels = [
                                'size_guide' => 'Size Guide',
                                'washing_instructions' => 'Washing Instructions',
                            ];
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-striped border-top" id="example">
                                <thead class="table-light">
                                    <tr class="text-muted text-uppercase small">
                                        <th>Sr. No</th>
                                        <th>Image Type</th>
                                        <th>Image</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($guides as $key => $guide)
                                        <tr class="border-bottom">
                                            <td>{{ $key + 1 }}</td>

                                            {{-- type label --}}
                                            <td class="fw-semibold">
                                                {{ $typeLabels[$guide->type] ?? $guide->type }}
                                            </td>

                                            {{-- image preview --}}
                                            <td>
                                                @if (!empty($guide->image_path))
                                                    <img src="{{ asset('storage/' . $guide->image_path) }}"
                                                        alt="{{ $typeLabels[$guide->type] ?? '' }}"
                                                        style="height:60px;width:auto;border-radius:6px;object-fit:contain;">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>

                                            {{-- actions --}}
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    {{-- Edit --}}
                                                    @can('instruction edit')
                                                        <a href="{{ route('instructionguides.edit', $guide) }}"
                                                            class="text-primary fs-5" title="Edit">
                                                            <i class='bx bx-edit'></i>
                                                        </a>
                                                    @endcan
                                                    @can('instruction delete')
                                                        <a href="{{ route('instructionguides.delete', $guide->id) }}"
                                                            class="text-danger fs-5" title="Delete this Image"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            onclick="return confirm('Are you sure you want to delete this image?')">
                                                            <i class='bx bx-trash'></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Add InstructionGuid Modal --}}
                        <div class="modal fade" id="addGuideModal" tabindex="-1" aria-labelledby="addGuideModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('instructionguides.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addGuideModalLabel">Add New Image</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            {{-- Type (static dropdown) --}}
                                            <div class="mb-3">
                                                <label class="form-label">Image Type</label>
                                                <select name="type" class="form-select" required>
                                                    <option value="" disabled selected>Select type</option>
                                                    <option value="size_guide">Size Guide</option>
                                                    <option value="washing_instructions">Washing Instructions</option>
                                                </select>
                                                @error('type')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Image file --}}
                                            <div class="mb-3">
                                                <label class="form-label">Image</label>
                                                <input type="file" name="image" class="form-control"
                                                    accept=".jpg,.jpeg,.png,.webp" required>
                                                @error('image')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Add Image</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
@endsection

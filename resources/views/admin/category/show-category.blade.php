@extends('admin.layouts')
@section('content')
        @include('sweetalert::alert')
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <div class="layout-page">
                    <div class="card mt-5 shadow-sm rounded" style="margin: 31px;">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                            <h5 class="card-title mb-0 text-md-start text-center">All Categories</h5>
                            @can('category add')
                                <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                    data-bs-target="#addCategoryModal">
                                    <i class="bx bx-plus icon-sm"></i>
                                    <span class="d-none d-sm-inline-block">Add Category</span>
                                </button>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-striped border-top" id="example">
                                <thead class="table-light">
                                    <tr class="text-muted text-uppercase small">
                                        <th>Sr. No</th>
                                        <th>Category Name</th>
                                        <th>Parent Category</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($categories as $key => $category)
                                        <tr class="border-bottom">
                                            <td>{{ $key + 1 }}</td>
                                            <td class="fw-semibold">{{ $category->name }}</td>
                                            <td>
                                                {{ $category->parent ? $category->parent->name : '—' }}
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    @can('category edit')
                                                        <a href="{{ route('categories.edit', $category->id) }}" title="Edit"
                                                            class="text-primary fs-5">
                                                            <i class='bx bx-edit'></i>
                                                        </a>
                                                    @endcan

                                                    @can('category delete')
                                                        <a href="{{ route('categories.delete', $category->id) }}" title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this category?')"
                                                            class="text-danger fs-5">
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
                        <!-- Add Category Modal -->
                        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('categories.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            {{-- Category Name --}}
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Category Name</label>
                                                <input type="text" name="name" class="form-control" id="name"
                                                    required>
                                            </div>

                                            {{-- Parent Category --}}
                                            <div class="mb-3">
                                                <label for="parent_id" class="form-label">Parent Category (Optional)</label>
                                                <select name="parent_id" id="parent_id" class="form-select">
                                                    <option value="">-- None (Top-level) --</option>
                                                    @foreach ($parents as $parent)
                                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Add Category</button>
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

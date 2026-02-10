@extends('admin.layouts')
@section('content')
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">

                <div class="layout-page">


                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Update</span> Category</h4>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h2>Update Category</h2>

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

                                        <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                            @csrf

                                            {{-- Category Name --}}
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Category Name</label>
                                                <input type="text" name="name" class="form-control" id="name"
                                                    value="{{ old('name', isset($category) ? $category->name : '') }}"
                                                    placeholder="Enter category name" required>

                                            </div>

                                            {{-- Parent Category --}}
                                            <div class="mb-3">
                                                <label for="parent_id" class="form-label">Parent Category (Optional)</label>
                                                <select name="parent_id" id="parent_id" class="form-select">
                                                    <option value="">-- None (Top-level) --</option>
                                                    @foreach ($parents as $parent)
                                                        {{-- Prevent selecting self as parent in edit, but skip in create --}}
                                                        @if (!isset($category) || $parent->id != $category->id)
                                                            <option value="{{ $parent->id }}"
                                                                {{ old('parent_id', optional($category)->parent_id) == $parent->id ? 'selected' : '' }}>
                                                                {{ $parent->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- Submit --}}
                                            <button type="submit" class="btn btn-primary">Update Category</button>
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
@endsection

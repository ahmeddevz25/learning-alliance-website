@extends('admin.layouts')
@section('content')
    @include('sweetalert::alert')
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="layout-page">
                <div class="card mt-5 shadow-sm rounded" style="margin: 31px;">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                        <h5 class="card-title mb-0 text-md-start text-center">All User</h5>
                        @can('role add')
                            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                data-bs-target="#addRoleModal">
                                <i class="bx bx-plus icon-sm"></i>
                                <span class="d-none d-sm-inline-block">Add User</span>
                            </button>
                        @endcan
                    </div>


                    <!-- Roles Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-striped border-top" id="example">
                            <thead class="table-light">
                                <tr class="text-muted text-uppercase small">
                                    <th>Sr. No</th>
                                    <th>Role Name</th>
                                    <th>Permissions</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $key => $role)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="fw-semibold">{{ $role->name }}</td>
                                        <td>
                                            @if ($role->permissions->isNotEmpty())
                                                @foreach ($role->permissions as $permission)
                                                    <span class="badge bg-info text-dark me-1 mb-1">
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">No Permissions</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                @can('role edit')
                                                    <a href="{{ route('roles.edit', $role->id) }}" title="Edit"
                                                        class="text-primary fs-5">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                @endcan

                                                @can('role delete')
                                                    <a href="{{ route('roles.delete', $role->id) }}" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this permission?')"
                                                        class="text-danger fs-5">
                                                        <i class='bx bx-trash'></i>
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No roles available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Role Modal -->
                    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route('roles.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        {{-- Role Name --}}
                                        <div class="mb-3">
                                            <label class="form-label">Role Name</label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>

                                        {{-- Permissions --}}
                                        <div class="mb-3">
                                            <label class="form-label">Assign Permissions</label>

                                            <!-- Select All Permissions -->
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="select_all_permissions">
                                                <label class="form-check-label fw-bold" for="select_all_permissions">
                                                    Select All Permissions
                                                </label>
                                            </div>

                                            <!-- Permissions Group -->
                                            <div class="row">
                                                @foreach ($groupedPermissions as $group => $perms)
                                                    @php
                                                        $groupKey = Str::slug($group, '_');
                                                        $groupLabel = Str::headline($group);
                                                        $actions = $perms->filter(function ($p) use ($group) {
                                                            return strtolower($p->name) !== strtolower($group);
                                                        });
                                                        $childIds = $actions
                                                            ->pluck('id')
                                                            ->map(fn($id) => "perm_$id")
                                                            ->implode(',');
                                                        $actionsChecked = $actions
                                                            ->filter(
                                                                fn($p) => in_array($p->name, old('permissions', [])),
                                                            )
                                                            ->count();
                                                        $parentChecked =
                                                            $actions->count() > 0 &&
                                                            $actionsChecked === $actions->count();
                                                    @endphp

                                                    <div class="col-12 mb-2">
                                                        <div class="border rounded p-3">
                                                            <div class="row align-items-center">
                                                                {{-- Parent Permission (e.g. categories) --}}
                                                                <div class="col-md-3 col-sm-4">
                                                                    <div class="form-check">
                                                                        <input type="checkbox"
                                                                            class="form-check-input group-parent"
                                                                            id="group_{{ $groupKey }}"
                                                                            data-group="{{ $groupKey }}"
                                                                            data-children="{{ $childIds }}"
                                                                            name="permissions[]" value="{{ $group }}"
                                                                            @checked($parentChecked)>
                                                                        <label class="form-check-label fw-semibold"
                                                                            for="group_{{ $groupKey }}">
                                                                            {{ $groupLabel }}
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                {{-- Children Permissions --}}
                                                                <div class="col-md-9 col-sm-8">
                                                                    <div class="d-flex flex-wrap gap-4">
                                                                        @foreach ($actions as $permission)
                                                                            @php
                                                                                $action = Str::after(
                                                                                    $permission->name,
                                                                                    $group,
                                                                                );
                                                                                $prettyAction = Str::headline(
                                                                                    trim($action),
                                                                                );
                                                                            @endphp
                                                                            <div class="form-check">
                                                                                <input type="checkbox" name="permissions[]"
                                                                                    value="{{ $permission->name }}"
                                                                                    class="form-check-input permission-checkbox"
                                                                                    id="perm_{{ $permission->id }}"
                                                                                    data-group="{{ $groupKey }}"
                                                                                    @checked(in_array($permission->name, old('permissions', [])))>
                                                                                <label class="form-check-label"
                                                                                    for="perm_{{ $permission->id }}">
                                                                                    {{ $prettyAction }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Create Role</button>
                                    </div>
                                </form>
                            </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectAll = document.getElementById('select_all_permissions');
            const childChecks = Array.from(document.querySelectorAll('.permission-checkbox')); // sirf children
            const groupParents = Array.from(document.querySelectorAll('.group-parent')); // base/parent

            function updateSelectAll() {
                const total = childChecks.length;
                const checked = childChecks.filter(c => c.checked).length;
                selectAll.checked = (total > 0 && checked === total);
                selectAll.indeterminate = (checked > 0 && checked < total);
            }

            function updateGroupParent(groupKey) {
                const kids = childChecks.filter(c => c.dataset.group === groupKey);
                const parent = document.querySelector(`.group-parent[data-group="${groupKey}"]`);
                if (!parent) return;

                const total = kids.length;
                const checked = kids.filter(c => c.checked).length;

                parent.checked = (checked === total && total > 0);
                parent.indeterminate = (checked > 0 && checked < total);
            }

            // Select All
            selectAll.addEventListener('change', () => {
                childChecks.forEach(cb => cb.checked = selectAll.checked);
                groupParents.forEach(p => {
                    p.checked = selectAll.checked;
                    p.indeterminate = false;
                });
            });

            // Parent → children
            groupParents.forEach(parent => {
                parent.addEventListener('change', () => {
                    const ids = (parent.dataset.children || '').split(',').filter(Boolean);
                    ids.forEach(id => {
                        const cb = document.getElementById(id);
                        if (cb) cb.checked = parent.checked;
                    });
                    parent.indeterminate = false;
                    updateSelectAll();
                });
            });

            // Child → parent & global
            childChecks.forEach(cb => {
                cb.addEventListener('change', () => {
                    updateGroupParent(cb.dataset.group);
                    updateSelectAll();
                });
            });
        });
    </script>
@endsection

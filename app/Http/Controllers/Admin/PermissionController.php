<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permission management')->only('index');
        $this->middleware('permission:permission add')->only('store');
        $this->middleware('permission:permission edit')->only('edit', 'update');
        $this->middleware('permission:permission delete')->only('destroy');
    }

    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.show-permissions', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);

        DB::beginTransaction();
        try {
            Permission::create(['name' => $request->name]);
            DB::commit();
            return back()->with('success', 'Permission created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Permission Store Error: " . $e->getMessage());
            return back()->with('error', 'Failed to create permission.');
        }
    }

    public function edit($id)
    {
        try {
            // dd($id/);
            $permission = Permission::findOrFail($id);
            return view('admin.permissions.form', compact('permission'));
        } catch (\Exception $e) {
            Log::error("Permission Edit Error: " . $e->getMessage());
            return redirect()->route('permissions')->with('error', 'Permission not found.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:permissions,name,' . $id]);

        DB::beginTransaction();
        try {
            $permission = Permission::findOrFail($id);
            $permission->update(['name' => $request->name]);
            DB::commit();
            return redirect()->route('permissions')->with('success', 'Permission updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Permission Update Error: " . $e->getMessage());
            return back()->with('error', 'Failed to update permission.');
        }
    }

    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            return back()->with('success', 'Permission deleted successfully!');
        } catch (\Exception $e) {
            Log::error("Permission Delete Error: " . $e->getMessage());
            return back()->with('error', 'Failed to delete permission.');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role management')->only('index');
        $this->middleware('permission:role add')->only('create', 'store');
        $this->middleware('permission:role edit')->only('edit', 'update');
        $this->middleware('permission:role delete')->only('destroy');
    }

    public function index()
    {
        // Fetch roles and their permissions
        $roles = Role::with('permissions')->get();
        $permissions = Permission::orderBy('name')->get();

        // Actions like add, edit, delete, etc.
        $actions = ['add', 'edit', 'delete', 'view', 'create', 'update', 'store', 'destroy'];

        // Normalizer for permission names
        $norm = function (string $s) {
            $s = strtolower($s);
            $s = preg_replace('/\s+/', ' ', $s);
            return trim($s);
        };

        // Base permissions (those without actions like "categories")
        $baseNames = [];
        foreach ($permissions as $p) {
            $n = $norm($p->name);
            if (!preg_match('/\s+(?:' . implode('|', $actions) . ')$/', $n)) {
                $baseNames[$n] = true;
            }
        }

        // Group permissions by derived base (parent -> child logic)
        $groupedPermissions = $permissions->groupBy(function ($p) use ($norm, $baseNames, $actions) {
            $n = $norm($p->name);

            // If it's a base permission (no actions)
            if (isset($baseNames[$n])) return $n;

            // If it's a child permission (e.g. "category add")
            if (preg_match('/^(.*)\s+(' . implode('|', $actions) . ')$/', $n, $m)) {
                $prefix = $norm($m[1]);

                // Check if it's a valid parent (base permission)
                if (isset($baseNames[$prefix])) return $prefix;

                // Fallback to plural or management
                $plural = $norm(Str::plural($prefix));
                if (isset($baseNames[$plural])) return $plural;

                $mgmt = $norm($prefix . ' management');
                if (isset($baseNames[$mgmt])) return $mgmt;

                return $prefix; // fallback to prefix
            }

            return $n; // if it's custom (unknown pattern)
        })->sortKeys();

        return view('admin.roles.show-role', compact('roles', 'groupedPermissions', 'permissions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions($request->permissions);

            DB::commit();
            return redirect()->route('roles')->with('success', 'Role created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role Store Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $all = Permission::orderBy('name')->get();

        // common actions (zarurat ho to aur add kar sakte hain)
        $actions = ['add', 'edit', 'delete', 'view', 'create', 'update', 'store', 'destroy'];

        // normalizer
        $norm = function (string $s) {
            $s = strtolower($s);
            $s = preg_replace('/\s+/', ' ', $s);
            return trim($s);
        };

        // 1) Base names set (wo permissions jo action pe end nahi hotin)
        $baseNames = [];
        foreach ($all as $p) {
            $n = $norm($p->name);
            if (!preg_match('/\s+(?:' . implode('|', $actions) . ')$/', $n)) {
                $baseNames[$n] = true;
            }
        }

        // 2) Group by derived base (pure dynamic, no map)
        $groupedPermissions = $all->groupBy(function ($p) use ($norm, $baseNames, $actions) {
            $n = $norm($p->name);

            // already a base?
            if (isset($baseNames[$n])) return $n;

            // child? "<prefix> <action>"
            if (preg_match('/^(.*)\s+(' . implode('|', $actions) . ')$/', $n, $m)) {
                $prefix = $norm($m[1]);

                // direct: "user management add" -> "user management" (agar aisi naming hai)
                if (isset($baseNames[$prefix])) return $prefix;

                // plural match: "category add" -> "categories"
                $plural = $norm(Str::plural($prefix));
                if (isset($baseNames[$plural])) return $plural;

                // management suffix: "user add" -> "user management"
                $mgmt = $norm($prefix . ' management');
                if (isset($baseNames[$mgmt])) return $mgmt;

                // fallback: group by prefix itself
                return $prefix;
            }

            // unknown pattern → apne naam par group
            return $n;
        })->sortKeys();

        // role ke selected perms (lowercased)
        $rolePerms = $role->permissions->pluck('name')->map(fn($v) => strtolower($v))->toArray();

        return view('admin.roles.form', [
            'role' => $role,
            'groupedPermissions' => $groupedPermissions,
            'rolePerms' => $rolePerms,
        ]);
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'array',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();

            $role->syncPermissions($request->permissions);

            DB::commit();
            return redirect()->route('roles')->with('success', 'Role updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role Update Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles')->with('success', 'Role deleted successfully!');
    }
}

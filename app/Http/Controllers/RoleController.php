<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return[
        new Middleware('permission:view roles',only:['index']),
        new Middleware('permission:edit roles',only:['edit']),
        new Middleware('permission:create roles',only:['create']),
        new Middleware('permission:destroy roles',only:['destroy']),
        ];
    }
    public function index(){
        $roles = Role::orderBy('name','ASC')->paginate(10);

        return view('roles.list',[
            'roles'=>$roles
        ]);
    }
    public function create(){
        $permissions = Permission::orderBy('name','ASC')->get();
        return view('roles.create',[
            'permissions' => $permissions
        ]);
        
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:roles,name'
        ]);
    
        if ($validator->passes()) {
            // Create a new role and assign it to $role
            $role = Role::create(['name' => $request->name]);
    
            // Check if permissions exist and assign them to the role
            if (!empty($request->permission)) {
                foreach ($request->permission as $name) {
                    $role->givePermissionTo($name); // Corrected function name
                }
            }
    
            return redirect()->route('roles.index')->with('success', 'Role added successfully!');
        } else {
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
    }
    public function edit(Request $request, $id)
    {
        $role = Role::findById($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name', 'ASC')->get();
        
    
        $categorizedPermissions = [
            'users' => $permissions->filter(fn($permission) => str_contains($permission->name, 'users')),
            'roles' => $permissions->filter(fn($permission) => str_contains($permission->name, 'roles')),
            'permissions' => $permissions->filter(fn($permission) => str_contains($permission->name, 'permissions')),
            'articles' => $permissions->filter(fn($permission) => str_contains($permission->name, 'articles')),
        ];

        return view('roles.edit', [
            'permissions' => $permissions,
            'categorizedPermissions' => $categorizedPermissions,
            'hasPermissions' => $hasPermissions,
            'role' => $role
        ]);
    }
    
    
 
      public function update($id, request $request){

        $role =Role::findById($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:roles,name,'. $id .',id'
        ]);
    
        if ($validator->passes()) {
            $role->name = $request->name;
            $role->save();
    
            // Check if permissions exist and assign them to the role
            if (!empty($request->permission)) {
                $role->syncPermissions($request->permission);
            }
            else{
                $role->syncPermissions([]);

            }
    
            return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
        } else {
            return redirect()->route('roles.edit',$id)->withInput()->withErrors($validator);
        }
    }
    public function destroy(string $id){
        $data = Role::where('id',$id)->delete();
        return redirect()->route('roles.index')->with('success', 'Data deleted successfully.');
    }
}

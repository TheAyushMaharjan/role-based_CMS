<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
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
    public function edit(request $request,$id){
        $role = Role::findById($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name','ASC')->get();

        return view('roles.edit',[
            'permissions'=>$permissions,
            'hasPermissions'=>$hasPermissions,
            'role'=>$role
        ]);
    }
 
    public function destroy(string $id){
        $data = Role::where('id',$id)->delete();
        return redirect()->route('roles.index')->with('success', 'Data deleted successfully.');
    }
}

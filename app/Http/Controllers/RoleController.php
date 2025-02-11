<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(){

    }
    public function create(){
        $permissions = Permission::orderBy('name','ASC')->get();
        return view('roles.create',[
            'permissions' => $permissions
        ]);
        
    }
    public function store(request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3,'

        ]);
        if ($validator->passes()) {
            Role::create([
                'name' => $request->name
            ]);
            return redirect()->route('permissions.index')->with('success', 'Permission added successfully!');
        } else {
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }
    }
   
}

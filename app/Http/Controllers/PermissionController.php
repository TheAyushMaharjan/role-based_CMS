<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    //this will show permission page
    public function index()
    {
        $permissions =Permission::orderBy('created_at','DESC')->paginate(10);
        return view('permissions.list',[
            'permissions' =>$permissions
        ]);

    }
    public function create()
    {
        return view('permissions.create');
    }
    public function store(request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3,'

        ]);
        if ($validator->passes()) {
            Permission::create([
                'name' => $request->name
            ]);
            return redirect()->route('permissions.index')->with('success', 'Permission added successfully!');
        } else {
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }
    }
    public function edit(string $id)
    {
        $permission =Permission::findOrFail($id);

        return view('permissions.edit',compact('permission'));
    }

public function update(Request $request, $id)
{
    $permission = Permission::findOrFail($id);
    
    $validator = Validator::make($request->all(), [
        'name' => 'required|min:3|unique:permissions,name,' . $id . ',id', // Ensure the table is plural
    ]);

    if ($validator->passes()) {
        $permission->name = $request->name;
        $permission->save();

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully!');
    } else {
        return redirect()->route('permissions.edit', $id)->withInput()->withErrors($validator); // Corrected the route to pass the ID
    }
}

public function destroy(string $id){
    $data = Permission::where('id',$id)->delete();
    return redirect()->route('permissions.index')->with('success', 'Data deleted successfully.');
}
}

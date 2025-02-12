<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return[
        new Middleware('permission:view users',only:['index']),
        new Middleware('permission:edit users',only:['edit']),
        new Middleware('permission:create permission',only:['create']),
        new Middleware('permission:destroy permission',only:['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.list',[
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name','ASC')->get();
        return view('users.create',[
            'roles'=> $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email', // Corrected unique check to check for 'email'
            'password' => 'required|min:6|same:password_confirmation', // Ensures password confirmation
            'password_confirmation' => 'required' // Ensures password confirmation

        ]);
    
        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->route('users.create')->withInput()->withErrors($validator);
        }
    
        // Create a new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); // Store the encrypted password
        $user->save();
    
        $user->syncRoles($request->role);
        // Redirect back with success message
        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }
    

 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = User::findOrFail($id);
        $roles = Role::orderBy('name','ASC')->get();

        $hasRoles = $users->roles->pluck('id');
        return view('users.edit',[
            'users'=>$users,
            'roles'=>$roles,
            'hasRoles'=>$hasRoles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $validator  =Validator::make($request->all(),[
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users,email,'.$id.',id'
        ]);
        if($validator->fails()){
            return redirect()->route('users.edit',$id)->withInput()->withErrors($validator);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $user->syncRoles($request->role);
        return redirect()->route('users.index',$id)->with('success','User Updated Successfully.');

    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       User::where('id',$id)->delete();
       return redirect()->route('users.index')->with('success','User deleted successfully');

    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        //create/edit/delete/read permissions
        $this->middleware(['permission:read_users'])->only('index');
        $this->middleware(['permission:create_users'])->only('create');
        $this->middleware(['permission:update_users'])->only('update');
        $this->middleware(['permission:delete_users'])->only('destroy');
    }
    public function index(Request $request)
    {
        //get all role
       // $user = User::all();
        //get admin in this role
       //$users = User::whereRoleIs('admin')->get();
        //get users with role admin and make search after
        $users = User::whereRoleIs('admin')->where(function($q) use ($request) {
            return $q->when($request->search, function ($query) use ($request) {
                return $query->where('first_name', 'like', '%' . $request->search . '%')->orwhere('last_name', 'like', '%' . $request->search . '%');
            });
            })->latest()->paginate(5);



            return view('dashboard.users.index', compact('users'));
        }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        //validate data
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'image' => 'required',
            'password' => 'required|confirmed',
            'permissions' => 'required|min:1',


        ]);
        //get data except password
        $request_data = $request->except(['password','password_confirmation','permissions','image']);
        //encrypt password
        $request_data['password'] = bcrypt($request->password);
        if($request->image){
            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();// keep relation between width and height
                //hashname get image name and make encrypt for name return unique name
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));
            $request_data['image'] = $request->image->hashName();
        }
        $user = User::create($request_data);

        //session message
        $user->attachRole('admin');
        $user->syncPermissions($request->permissions);
        session()->flash('success', __('site.added_successfully'));
        //return view('dashboard.users.index');

        return redirect()->route('dashboard.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //dd($request->all());
        //validate data
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            //check exists email in db ignore that user
            'email' => ['required', Rule::unique('users')->ignore($user->id)],
            'image' => 'required',
            'permissions' => 'required|min:1',


        ]);
        //get data except password
        $request_data = $request->except(['permissions','image']);
        //encrypt password
        if($request->image){
            //check image default to delete users image when deleted
            if($user->image != 'default.jpg'){
                //delete public_uploads exist in file system
                Storage::disk('public_uploads')->delete('/user_images/' . $user->image);
            }//end inner if
            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();// keep relation between width and height
                //hashname get image name and make encrypt for name return unique name
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));
            $request_data['image'] = $request->image->hashName();
        }//end external if

        $user->update($request_data);
        $user->syncPermissions($request->permissions);
        session()->flash('success', __('site.edit_successfully'));
        //return view('dashboard.users.index');

        return redirect()->route('dashboard.users.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //check image default to delete users image when deleted
        if($user->image != 'default.jpg'){
            //delete public_uploads exist in file system
            Storage::disk('public_uploads')->delete('/user_images/' . $user->image);
        }

        $user->delete();
        session()->flash('success', __('site.deleted_successfully'));
        //return view('dashboard.users.index');

        return redirect()->route('dashboard.users.index');
    }
}

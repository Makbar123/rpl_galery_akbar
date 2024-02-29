<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function index()
    {
        return view('login');
    }
    public function register()
    {
        return view('register');
    }
    public function postlogin(Request $request)
    {
        $login= $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);
        if(Auth::attempt($login))
        {
            Session::put('user_id',auth()->user()->id);
            Session::put('name',auth()->user()->name);
            return redirect()->intended('/galery');
        }
        return back()->withErrors([
            'errors'=>'username dan password keliru'
        ]);
        return redirect('/login');
    }
    public function postregister(Request $request)
    {
        $register= $request->validate([
            'name'=>'required',
            'username'=>'required',
            'email'=>'required',
            'password'=>'required',
            'repassword'=>'required',
            'terms'=>'required',
        ]);
        if($request->password == $request->repassword)
        {
            $ins=User::create([
                'name'=>$request->name,
                'username'=>$request->username,
                'email'=>$request->email,
                'password'=>bcrypt($request->password)

            ]);
            $login= $request->validate([
                'username'=>'required',
                'password'=>'required'
            ]);
            if(Auth::attempt($login))
            {
                Session::put('user_id',auth()->user()->id);
                Session::put('name',auth()->user()->name);
                return redirect()->intended('/login');
            }
            return redirect('/login');
        }
    }
    public function logout()
    {
        Auth::logout();
        Session::forget('user_id');
        Session::forget('name');
        return redirect('/login');
    }
}



<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Mail,Hash,File,Auth,DB,Helper,Exception,Session,Redirect,Validator;
use Carbon\Carbon;
use App\Models\Notification;
use App\Models\NotificationUser;

class AdminUserController extends Controller 
{
    //========================= User Member Funcations ========================//
    
    public function index() {
        try {
            return view('admin.users.index');
        } catch (\Throwable $e) {
            dd($e);
        }
    }
    
    public function getallUser(Request $request) {
        try {
            $users = User::where('role', 'user')->orderBy('id', 'desc')->get();
            return response()->json(['data' => $users]);
        } catch (\Throwable $e) {
            dd($e);
        }
    }
   
    public function userStatus(Request $request) {
        try
        {
            $userid = $request->userid;
            $status = $request->status;
            $user = User::find($userid);
            $user->status = $status;
            $user->save();
            return response()->json(['success' => true]);

        }catch(Exception $e){
            return response()->json(['success' => false,'message' => $e->getMessage()]);
        }
    }


    public function profile(){
        try {
            $user = Auth::user();
            return view('web.auth.profile',compact('user'));
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    public function show($id) {
        try
        {
            $user = User::find($id);
            if($user){
                return view('admin.users.show', compact('user'));
            }else{
                return redirect()->route('admin.users.index')->withError('User not found!');
            }

        }catch(Exception $e){
            return back()->withError($e->getMessage());
        }
    }

    

}

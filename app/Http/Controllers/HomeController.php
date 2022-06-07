<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\ConnectionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $connectionIds = Connection::where('user_id',Auth::id())->pluck('connection_id')->toArray();
        $requestConnectionIds = ConnectionRequest::where('request_from',Auth::id())->pluck('request_to')->toArray();
        $suggestions = User::whereNotIn('id',$connectionIds)->whereNotIn('id',$requestConnectionIds)->where('id','!=',Auth::id())->get();

        $connectionRequests = ConnectionRequest::where('request_from',Auth::id())->get();
        $connectionRequests->filter(function($value){
            $user = User::whereId($value->request_to)->first();
            if($user){
                $value->name = $user->name;
                $value->email = $user->email;
            }
            return true;
        });

        $receivedConnectionRequests = ConnectionRequest::where('request_to',Auth::id())->get();
        $receivedConnectionRequests->filter(function($value){
            $user = User::whereId($value->request_from)->first();
            if($user){
                $value->name = $user->name;
                $value->email = $user->email;
            }
            return true;
        });
        $connections = Connection::where('user_id',Auth::id())->get();
        $connections->filter(function($value){
            $user = User::whereId($value->connection_id)->first();
            if($user){
                $value->name = $user->name;
                $value->email = $user->email;
            }
            return true;
        });
        return view('home',compact('suggestions','connectionRequests','receivedConnectionRequests','connections'));
    }
}

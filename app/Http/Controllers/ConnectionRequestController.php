<?php

namespace App\Http\Controllers;

use App\Models\ConnectionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionRequestController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            ConnectionRequest::create([
                'request_from' => Auth::id(),
                'request_to' => $request->suggestionId
            ]);

            return response()->json([
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            ConnectionRequest::whereId($id)->delete();
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Get sent request data from database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_sent_request(Request $request)
    {
        try {
            $connectionRequests = ConnectionRequest::where('request_from', Auth::id())->paginate(10);
            $connectionRequests->filter(function ($value) {
                $user = User::whereId($value->request_to)->first();
                if ($user) {
                    $value->name = $user->name;
                    $value->email = $user->email;
                }
                return true;
            });
            $mode = 'sent';
            $receivedConnectionRequest = '';
            $data = '';
            foreach ($connectionRequests as $key => $connectionRequest) {
                $data .= view('components.request', compact('mode', 'connectionRequest', 'receivedConnectionRequest'))->render();
            }
            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Get received request data from database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_received_request(Request $request)
    {
        try {
            $receivedConnectionRequests = ConnectionRequest::where('request_to', Auth::id())->paginate(10);
            $receivedConnectionRequests->filter(function ($value) {
                $user = User::whereId($value->request_from)->first();
                if ($user) {
                    $value->name = $user->name;
                    $value->email = $user->email;
                }
                return true;
            });
            $mode = 'received';
            $connectionRequest = '';
            $data = '';

            foreach ($receivedConnectionRequests as $key => $receivedConnectionRequest) {
                $data .= view('components.request', compact('mode', 'connectionRequest', 'receivedConnectionRequest'))->render();
            }
            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

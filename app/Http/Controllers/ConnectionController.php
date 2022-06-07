<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\ConnectionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $connections = Connection::where('user_id', Auth::id())->paginate(10);
            $connections->filter(function ($value) {
                $user = User::whereId($value->connection_id)->first();
                $commonConnection = $this->mutualFriends($user->id);
                if ($user) {
                    $value->name = $user->name;
                    $value->email = $user->email;
                }
                $value->commonConnection = $commonConnection;
                return true;
            });
            $data = '';
            foreach ($connections as $key => $connection) {
                $data .= view('components.connection', compact('connection'))->render();
            }

            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => '',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Fetch common connection of a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function get_common_connection(Request $request)
    {
        try {
            $id = $request->connectionId;
            $connection = Connection::whereId($id)->first();
            $connectionIds = $this->getMutualFriendsIds($connection->connection_id);
            $connections = Connection::whereIn('id', $connectionIds)->paginate(10);
            $connections->filter(function ($value) {
                $user = User::whereId($value->connection_id)->first();
                $commonConnection = $this->mutualFriends($user->id);
                if ($user) {
                    $value->name = $user->name;
                    $value->email = $user->email;
                }
                $value->commonConnection = $commonConnection;
                return true;
            });
            $data = '';
            foreach ($connections as $key => $connection) {
                $data .= view('components.connection_in_common', compact('connection'))->render();
            }

            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => '',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Get mutual friend collection by user id
     *
     * @return \Illuminate\Http\Response
     */
    public function mutualFriends($id)
    {
        $user = User::where('id', $id)->first();
        $userConnections = $user->get_connections;
        $userConnectionsIds = [];
        foreach ($userConnections as $entry) {
            $userConnectionsIds[] = $entry->connection_id;
        }
        $loggedUserConnections = Auth::user()->get_connections->whereIn('connection_id', $userConnectionsIds)->where('connection_id', '!=', $user->id);
        return $loggedUserConnections;
    }
    /**
     * Get mutual friend ids by user id
     *
     * @return \Illuminate\Http\Response
     */
    public function getMutualFriendsIds($id)
    {
        $user = User::where('id', $id)->first();
        $userConnections = $user->get_connections;
        $userConnectionsIds = [];
        foreach ($userConnections as $entry) {
            $userConnectionsIds[] = $entry->connection_id;
        }
        $loggedUserConnectionsIds = Auth::user()->get_connections->whereIn('connection_id', $userConnectionsIds)->where('connection_id', '!=', $user->id)->pluck('id')->toArray();
        return $loggedUserConnectionsIds;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $requestConnection = ConnectionRequest::whereId($request->requestId)->first();
            DB::beginTransaction();
            $connectionArray = [[
                'user_id' => Auth::id(),
                'connection_id' => $requestConnection->request_from
            ],
            [
                'connection_id' => Auth::id(),
                'user_id' => $requestConnection->request_from
            ]];
            Connection::create($connectionArray);
            DB::commit();
            $requestConnection->delete();
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => true,
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
            DB::beginTransaction();
            Connection::whereId($id)->delete();
            DB::commit();
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Get the listion of suggetions.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_suggestions()
    {
        try {
            $connectionIds = Connection::where('user_id', Auth::id())->pluck('connection_id')->toArray();
            $requestConnectionIds = ConnectionRequest::where('request_from', Auth::id())->pluck('request_to')->toArray();
            $suggestions = User::whereNotIn('id', $connectionIds)->whereNotIn('id', $requestConnectionIds)->where('id', '!=', Auth::id())->paginate(10);
            $data = '';
            foreach ($suggestions as $key => $suggestion) {
                $data .= view('components.suggestion', compact('suggestion'))->render();
            }
            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

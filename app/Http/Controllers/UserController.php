<?php

namespace App\Http\Controllers;

use App\Http\Lib\ApiHelpers;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, UserService $serviceUser): JsonResponse
    {
        $return= $serviceUser->list($request);
        return response()->json([
            'success' => $return['success'],
            'message' => $return['message'],
            'data' =>    $return['data'],
            'code' =>    $return['code'],
        ], $return['code']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UserService $serviceUser): JsonResponse
    {
        $return= $serviceUser->create($request);
        return response()->json([
            'success' => $return['success'],
            'message' => $return['message'],
            'data' =>    $return['data'],
            'code' =>    $return['code'],
        ], $return['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, UserService $serviceUser): JsonResponse
    {
        $return= $serviceUser->get($request, $id);
        return response()->json([
            'success' => $return['success'],
            'message' => $return['message'],
            'data' =>    $return['data'],
            'code' =>    $return['code'],
        ], $return['code']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param  \App\Services\UserService  $serviceUser
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id, UserService $serviceUser): JsonResponse
    {
        $return= $serviceUser->delete($request, $id);
        return response()->json([
            'success' => $return['success'],
            'message' => $return['message'],
            'data' =>    $return['data'],
            'code' =>    $return['code'],
        ], $return['code']);
    }
}

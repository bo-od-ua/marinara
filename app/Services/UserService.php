<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Http\Lib\ApiHelpers;

class UserService
{
    use ApiHelpers;

    protected $rolesAllowed= [2, 3];
    protected $rolesAdmin=   [1, 2];

    public function list(Request $request): object
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            Log::notice("[".__METHOD__."]< ".print_r($request->all(), 1));
            $users = DB::table('users')
                ->when(!empty($request->search), function($query){
                    return $query->where('name', 'like', '%'.request()->search.'%')
                        ->orWhere('email', 'like', '%'.request()->search.'%');
                })
                ->orderBy(
                    ($request->sort ?? 'id'),
                    ($request->order ?? 'asc')
                )->paginate($request->rows);
            return (object)['success'=> 1, 'code'=> 200, 'message'=> 'Users Retrieved', 'data' => $users];
        }
        return (object)['success'=> 0, 'code'=> 401, 'message'=> 'Unauthorized Access', 'data' => []];
    }

    public function get(Request $request, $id): array
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $data = DB::table('users')->where('id', $id)->first();
            if (!empty($data)) {
                return ['success'=> 1, 'code'=> 200, 'message'=> 'Storage Retrieved', 'data' => $data];
            }
            return ['success'=> 0, 'code'=> 404, 'message'=> 'User Not Found', 'data' => []];
        }
        return ['success'=> 0, 'code'=> 401, 'message'=> 'Unauthorized Access', 'data' => []];
    }
    public function create(Request $request): array
    {
        $role= 3;
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $validator = Validator::make($request->all(), $this->validatedRules());
            if ($validator->passes()) {
                if(in_array((int)$request->input('role'), $this->rolesAllowed)) $role= (int)$request->input('role');

                User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'role' => $role,
                    'password' => Hash::make($request->input('password')),
                ]);
                $writerToken = $user->createToken('auth_token', ['subscriber'])->plainTextToken;
                return ['success'=> 1, 'code'=> 200, 'message'=> 'User Created With Subscriber Privilege', 'data' => $writerToken];
            }
            return ['success'=> 0, 'code'=> 400, 'message'=> $validator->errors(), 'data' => []];
        }
        return ['success'=> 0, 'code'=> 401, 'message'=> 'Unauthorized Access', 'data' => []];
    }

    public function delete(Request $request, $id): array
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $user = User::find($id); // Найдем id пользователя
            if ($user->role !== 1) {
                $user->delete(); // Удалим указанного пользователя
                if (!empty($user)) {
                    return ['success'=> 1, 'code'=> 200, 'message'=> 'User Deleted', 'data' => []];
                }
                return ['success'=> 0, 'code'=> 404, 'message'=> 'User Not Found', 'data' => []];
            }
        }
        return ['success'=> 0, 'code'=> 401, 'message'=> 'Unauthorized Access', 'data' => []];
    }
    protected function validatedRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}

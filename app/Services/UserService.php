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

    protected $rolesUsers=   [2=> 'writer', 3=> 'subscriber'];
    protected $rolesWriters= [1, 2];

    public function list(Request $request): object
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
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

    public function get(Request $request, $id): object
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $data = DB::table('users')->where('id', $id)->first();
            if (!empty($data)) {
                return (object)['success'=> 1, 'code'=> 200, 'message'=> 'Storage Retrieved', 'data' => $data];
            }
            return (object)['success'=> 0, 'code'=> 404, 'message'=> 'User Not Found', 'data' => []];
        }
        return (object)['success'=> 0, 'code'=> 401, 'message'=> 'Unauthorized Access', 'data' => []];
    }
    public function create(Request $request): object
    {
        $role= 3;
        $editor = $request->user();
        if ($this->isAdmin($editor)) {
            $validator= $this->validator($request);
            if ($validator->passes()) {
                if($this->rolesUsers[(int)$request->input('role')]) $role= (int)$request->input('role');

                $user= User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'notice' => $request->input('notice'),
                    'role' => $role,
                    'password' => Hash::make($request->input('password')),
                ]);
                $writerToken = $user->createToken('auth_token', [$this->rolesUsers[$role]])->plainTextToken;
                $user->bearer= $writerToken;
                $user->save();
                Log::notice("[".__METHOD__."]< ".print_r($request->all(), 1)."\n".$writerToken);
                return (object)['success'=> 1, 'code'=> 200, 'message'=> 'User Created With '.$this->rolesUsers[$role].' Privilege', 'data' => $writerToken];
            }
            return (object)['success'=> 0, 'code'=> 400, 'message'=> $validator->errors(), 'data' => []];
        }
        return (object)['success'=> 0, 'code'=> 401, 'message'=> 'Unauthorized Access', 'data' => []];
    }

    public function update(Request $request, $id): object
    {
        $editor = $request->user();
        if ($this->isAdmin($editor)) {
            $user = User::find($id);

            $validator= $this->validator($request, $user);
            if ($validator->passes()) {
                if($request->input('password')){
                    $request->merge(['password'=> Hash::make($request->password)]);
                    $user->update($request->all());
                }
                else{
                    $user->update($request->except('password'));
                }
                Log::notice("[".__METHOD__."]< ".print_r($request->all(), 1));
                return (object)['success'=> 1, 'code'=> 200, 'message'=> 'User Updated', 'data' => []];
            }

            return (object)['success'=> 0, 'code'=> 400, 'message'=> $validator->errors(), 'data' => []];
        }
        return (object)['success'=> 0, 'code'=> 401, 'message'=> 'Unauthorized Access', 'data' => []];
    }

    public function delete(Request $request, $id): object
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $user = User::find($id); // Найдем id пользователя
            if ($user->role !== 1) {
                $user->delete(); // Удалим указанного пользователя
                if (!empty($user)) {
                    return (object)['success'=> 1, 'code'=> 200, 'message'=> 'User Deleted', 'data' => []];
                }
                return (object)['success'=> 0, 'code'=> 404, 'message'=> 'User Not Found', 'data' => []];
            }
        }
        return (object)['success'=> 0, 'code'=> 401, 'message'=> 'Unauthorized Access', 'data' => []];
    }

    protected function validator(Request $request, $user= '')
    {
        $rules= [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if($user){
            if($user->email== $request->input('email')) unset($rules['email']);
            if(!$request->input('password')) unset($rules['password']);
        }

        return Validator::make($request->all(), $rules);
    }
}

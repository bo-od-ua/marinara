<?php

namespace App\Http\Controllers;

use App\Http\Lib\ApiHelpers;
use App\Models\Storage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;

class StorageController extends Controller
{
    use ApiHelpers;
    public function storage(Request $request): JsonResponse
    {
        if ($this->isAdmin($request->user())) {
//            $storage = DB::table('storages')->get();
//            $storage = Storage::paginate($request->rows)->orderBy('id', 'desc');
            Log::notice("[".__METHOD__."]< ".print_r($request->all(), 1));
            $storage = DB::table('storages')
                ->when(!empty($request->search), function($query){
                    return $query->where('name', 'like', '%'.request()->search.'%')
                        ->orWhere('phone', 'like', '%'.request()->search.'%')
                        ->orWhere('car_info', 'like', '%'.request()->search.'%');
                })
                ->orderBy(
                    ($request->sort ?? 'id'),
                    ($request->order ?? 'asc')
                )->paginate($request->rows);

            return $this->onSuccess($storage, 'Storage Retrieved');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    public function singleStorage(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user) || $this->isSubscriber($user)) {
            $storage = DB::table('storages')->where('id', $id)->first();
            if (!empty($storage)) {
                return $this->onSuccess($storage, 'Storage Retrieved');
            }
            return $this->onError(404, 'Storage Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    public function createStorage(Request $request): JsonResponse
    {
        Log::notice("[".__METHOD__."] ".print_r($request->all(), 1));
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $validator = Validator::make($request->all(), $this->storageValidationRules());
            if ($validator->passes()) {
                // Создание нового сообщения

                $storage= Storage::create($request->all());

//                $storage = new Storage();
//                $storage->name = $request->input('name');
//                $storage->name_alt = $request->input('name') ?? 'aaaa';
////                $storage->slug = Str::slug($request->input('title'));
//                $storage->phone = $request->input('phone');
//                $storage->car_info = $request->input('car_info') ?? '?';
//                $storage->storage_time = $request->input('storage_time') ?? '2022-01-01';
//                $storage->sum = $request->input('sum') ?? 0;
//                $storage->descr_category = $request->input('descr_category') ?? '';
//                $storage->descr_name = $request->input('descr_name') ?? '';
//                $storage->descr_amount = $request->input('descr_amount') ?? 0;

//                $storage->fill($request->all());
//                $storage->save();

                return $this->onSuccess($storage, 'Storage Created');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    public function updateStorage(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $validator = Validator::make($request->all(), $this->storageValidationRules());
            if ($validator->passes()) {
                // Обновление сообщения
                $storage = Storage::find($id);
                $storage->fill($request->all());
                $storage->save();
                return $this->onSuccess($storage, 'Storage Updated');
            }
            Log::notice("[".__METHOD__."] ".print_r($request->all(), 1));
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    public function deleteStorage(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $storage = Storage::find($id); // Найдем id сообщения
            $storage->delete(); // Удаляем указанное сообщение
            if (!empty($storage)) {
                return $this->onSuccess($storage, 'Storage Deleted');
            }
            return $this->onError(404, 'Storage Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    public function createWriter(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->passes()) {
                // Создаем нового Автора
                User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'role' => 2,
                    'password' => Hash::make($request->input('password')),
                ]);
                $writerToken = $user->createToken('auth_token', ['writer'])->plainTextToken;
                return $this->onSuccess($writerToken, 'User Created With Writer Privilege');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    public function createSubscriber(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->passes()) {
                // Создаем нового Подписчика
                User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'role' => 3,
                    'password' => Hash::make($request->input('password')),
                ]);
                $writerToken = $user->createToken('auth_token', ['subscriber'])->plainTextToken;
                return $this->onSuccess($writerToken, 'User Created With Subscriber Privilege');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    public function deleteUser(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $user = User::find($id); // Найдем id пользователя
            if ($user->role !== 1) {
                $user->delete(); // Удалим указанного пользователя
                if (!empty($user)) {
                    return $this->onSuccess('', 'User Deleted');
                }
                return $this->onError(404, 'User Not Found');
            }
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}

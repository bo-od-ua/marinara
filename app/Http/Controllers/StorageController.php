<?php

namespace App\Http\Controllers;

use App\Http\Lib\ApiHelpers;
use App\Models\Storage;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

use App\Services\UserService;

use Illuminate\Support\Facades\Log;

class StorageController extends Controller
{
    use ApiHelpers;
    public function storage(Request $request): JsonResponse
    {
        if (Auth::check()) {
            Log::notice("[".__METHOD__."]< ".print_r($request->all(), 1));
            $storage = DB::table('storages')
                ->when(!empty($request->search), function($query){
                    return $query->where('name', 'like', '%'.request()->search.'%')
                        ->orWhere('phone', 'like', '%'.request()->search.'%')
                        ->orWhere('article', 'like', '%'.request()->search.'%')
                        ->orWhere('storage_time', 'like', '%'.request()->search.'%')
                        ->orWhere('car_info', 'like', '%'.request()->search.'%');
                })
                ->orderBy(
                    ($request->sort ?? 'id'),
                    ($request->order ?? 'asc')
                )->paginate($request->rows);

            return $this->onSuccess($storage, 'Storage Retrieved');
        }
        return $this->onError(403, 'Unauthorized Access');
    }
    public function singleStorage(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        if (Auth::check()) {
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

    public function pdfStorage(Request $request, $id)
    {
        $user = $request->user();
        if ($user) {
            $storage = Storage::find($id); // Найдем id сообщения

            if (!empty($storage)) {
                $pdf = PDF::loadView('blank', compact('id', 'storage'));
                return $pdf->stream('invoice.pdf');
            }
            return $this->onError(404, 'Storage Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}

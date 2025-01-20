<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;

class ProfileServiceImpl implements ProfileService
{
    public function editProfile(ProfileRequest $request): JsonResponse
    {
        try {
            $arrUpate = [
                'name'     => $request->name,
                'email'    => $request->email,
            ];

            if ($request->new_password) {
                $arrUpate['password'] = Crypt::encrypt($request->new_password);
            }

            User::where("id", $request->id)->update($arrUpate);

            return response()->json(
                [
                    'message' => 'profile berhasil disimpan',
                    'data'    => ''
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }
}

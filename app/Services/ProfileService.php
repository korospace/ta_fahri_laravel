<?php

namespace App\Services;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\JsonResponse;

interface ProfileService
{
    public function editProfile(ProfileRequest $request): JsonResponse;
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $dService;

    public function __construct(ProfileService $dService)
    {
        $this->dService = $dService;
    }

    /**
     * View - Profile Page
     *
     * - show dashboard profile page
     * -----------------------------
     */
    public function profilePage(Request $request)
    {
        $data = [
            'metaTitle' => 'Profile',
            'headTitle' => 'Edit Profile',
            'user'      => $request->user,
            'dataTab'   => $request->dataTab
        ];

        return view('pages/dashboard_profile', $data);
    }

    /**
     * API - Edit Profile
     * ---------------------------
     */
    public function editProfile(ProfileRequest $request)
    {
        try {
            return $this->dService->editProfile($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => $th->getCode(),
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }
}

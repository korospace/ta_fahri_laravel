<?php

namespace App\Http\Controllers;

use App\Services\UploadDataService;
use App\Http\Requests\UploadDataRequest;
use Illuminate\Http\Request;

class UploadDataController extends Controller
{
    protected $mainService;

    public function __construct(UploadDataService $mainService)
    {
        $this->mainService = $mainService;
    }

    /**
     * View - Dashboard - Upload
     *
     * - show dashboard upload page
     * -------------------------------
     */
    public function uploadDataPage(Request $request)
    {
        $data = [
            'metaTitle' => 'Upload',
            'user'      => $request->user,
            'dataTab'   => $request->dataTab
        ];

        return view('pages/dashboard_upload/dashboard_upload', $data);
    }

    /**
     * API - Update Tab
     * ---------------------------
     */
    public function updateTab(UploadDataRequest $request)
    {
        try {
            return $this->mainService->updateTab($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Set Active Tab
     * ---------------------------
     */
    public function setActiveTab(UploadDataRequest $request)
    {
        try {
            return $this->mainService->setActiveTab($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Update Progress Status
     * ----------------------------
     */
    public function updateProgressStatus(UploadDataRequest $request)
    {
        try {
            return $this->mainService->updateProgressStatus($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Import Followers & Following
     * ----------------------------------
     */
    public function importFollowersAndFollowing(UploadDataRequest $request)
    {
        try {
            return $this->mainService->importFollowersAndFollowing($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Generate Mutual
     * ---------------------------
     */
    public function generateMutual(Request $request)
    {
        try {
            return $this->mainService->generateMutual($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Scrape Mutual Detail
     * ---------------------------
     */
    public function scrapeMutualDetail(Request $request, $username)
    {
        try {
            return $this->mainService->scrapeMutualDetail($request, $username);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Scrape Mutual Followers
     * ---------------------------
     */
    public function scrapeMutualFollowers(Request $request, $username)
    {
        try {
            return $this->mainService->scrapeMutualFollowers($request, $username);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Generate Nodes & Edges
     * ----------------------------
     */
    public function generateNodeEdge(Request $request)
    {
        try {
            return $this->mainService->generateNodeEdge($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }
}

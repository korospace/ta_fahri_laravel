<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DataSnapService;

class DataSnapController extends Controller
{
    protected $mainService;

    public function __construct(DataSnapService $mainService)
    {
        $this->mainService = $mainService;
    }

    /**
     * View - Dashboard - Data Snap
     *
     * - show dashboard data snap page
     * -------------------------------
     */
    public function dataSnapPage(Request $request)
    {
        $data = [
            'metaTitle' => 'Data Snap',
            'user'      => $request->user,
            'dataTab'   => $request->dataTab
        ];

        return view('pages/dashboard_data/dashboard_data', $data);
    }

    /**
     * API - List Followers
     * ---------------------------
     */
    public function listFollowers(Request $request)
    {
        try {
            return $this->mainService->listFollowers($request);
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
     * API - List Following
     * ---------------------------
     */
    public function listFollowing(Request $request)
    {
        try {
            return $this->mainService->listFollowing($request);
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
     * API - List Mutual
     * ---------------------------
     */
    public function listMutual(Request $request)
    {
        try {
            return $this->mainService->listMutual($request);
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
     * API - List Mutual Followers
     * ---------------------------
     */
    public function listMutualFollowers(Request $request)
    {
        try {
            return $this->mainService->listMutualFollowers($request);
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
     * API - Get Nodes & Edges
     * ---------------------------
     */
    public function getNodesEdges(Request $request)
    {
        try {
            return $this->mainService->getNodesEdges($request);
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
     * API - Get Centrality Measure
     * ----------------------------
     */
    public function getCentralityMeasure(Request $request)
    {
        try {
            return $this->mainService->getCentralityMeasure($request);
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

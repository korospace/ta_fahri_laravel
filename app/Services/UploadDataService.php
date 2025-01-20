<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UploadDataRequest;

interface UploadDataService
{
    // get tab
    public function getTab(): array;

    // update tab
    public function updateTab(UploadDataRequest $request): JsonResponse;
    
    // set active tab
    public function setActiveTab(UploadDataRequest $request): JsonResponse;
    
    // update progress status
    function updateProgressStatus(UploadDataRequest $request): JsonResponse;

    // import file followers & following
    public function importFollowersAndFollowing(UploadDataRequest $request): JsonResponse;
    
    // generate mutual
    public function generateMutual(): JsonResponse;
    
    // scrape mutual detail
    public function scrapeMutualDetail(Request $request, $username): JsonResponse;
    
    // scrape mutual detail
    public function scrapeMutualFollowers(Request $request, $username): JsonResponse;
    
    // generate nodes & edges
    public function generateNodeEdge(Request $request): JsonResponse;
}

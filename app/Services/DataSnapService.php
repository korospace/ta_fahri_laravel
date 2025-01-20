<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface DataSnapService
{
    // list followers
    function listFollowers(Request $request): JsonResponse;
    
    // list following
    function listFollowing(Request $request): JsonResponse;
    
    // list mutual
    function listMutual(Request $request): JsonResponse;
    
    // list mutual followers
    function listMutualFollowers(Request $request): JsonResponse;
    
    // get nodes & edges
    function getNodesEdges(Request $request): JsonResponse;
    
    // get centrality measure
    function getCentralityMeasure(Request $request): JsonResponse;
}

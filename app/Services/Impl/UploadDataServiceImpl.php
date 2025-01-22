<?php

namespace App\Services\Impl;

use App\Models\ProgressUpload;
use Illuminate\Http\JsonResponse;
use App\Exceptions\GeneralException;
use App\Services\UploadDataService;
use Illuminate\Support\Facades\File;
use App\Http\Requests\UploadDataRequest;
use App\Models\Mutual;
use App\Models\Followers;
use App\Models\Following;
use App\Models\MutualFollowers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UploadDataServiceImpl implements UploadDataService
{
    // get tab
    public function getTab(): array
    {
        try {
            return [
                'message' => 'tab berhasil didapatkan',
                'data'    => ProgressUpload::where("id", 1)->first()
            ];
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    // update tab
    public function updateTab(UploadDataRequest $request): JsonResponse
    {
        try {
            $arrUpate = [
                $request->tab_name => $request->tab_status,
            ];

            ProgressUpload::where("id", 1)->update($arrUpate);

            return response()->json(
                [
                    'message' => 'tab berhasil diupdate',
                    'data'    => ''
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    // set active tab
    public function setActiveTab(UploadDataRequest $request): JsonResponse
    {
        try {
            $arrUpate = [
                'tab_active' => $request->tab_name,
            ];

            ProgressUpload::where("id", 1)->update($arrUpate);

            return response()->json(
                [
                    'message' => 'tab active berhasil diupdate',
                    'data'    => ''
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    // update progress status
    public function updateProgressStatus(UploadDataRequest $request): JsonResponse
    {
        try {
            $arrUpate = [];

            if ($request->progress_status == "completed") {
                $arrUpate = [
                    'progress_status'       => $request->progress_status,
                    'tab_active'            => 'tab_upload_data',
                    'tab_upload_data'       => 'disabled',
                    'tab_mutual'            => 'disabled',
                    'tab_mutual_detail'     => 'disabled',
                    'tab_mutual_followers'  => 'disabled',
                    'tab_node_graph'        => 'disabled',
                ];
            } else {
                $arrUpate = [
                    'progress_status' => $request->progress_status,
                ];
            }

            ProgressUpload::where("id", 1)->update($arrUpate);

            return response()->json(
                [
                    'message' => 'status progress berhasil diupdate',
                    'data'    => ''
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    // import file followers & following
    public function importFollowersAndFollowing(UploadDataRequest $request): JsonResponse
    {
        try {
            Followers::truncate();
            Following::truncate();

            $file_followers = $request->file('file_followers');
            $file_following = $request->file('file_following');
            $followersData = readJsonFile($file_followers);
            $followingData = readJsonFile($file_following);
            
            foreach ($followersData as $follower) {
                foreach ($follower['string_list_data'] as $row) {
                    Followers::create([
                        'href'      => $row['href'],
                        'username'  => $row['value'],
                        'timestamp' => $row['timestamp'],
                    ]);
                }
            }

            if ($followingData['relationships_following']) {
                foreach ($followingData['relationships_following'] as $following) {
                    foreach ($following['string_list_data'] as $row) {
                        Following::create([
                            'href'      => $row['href'],
                            'username'  => $row['value'],
                            'timestamp' => $row['timestamp'],
                        ]);
                    }
                }
            }

            return response()->json(
                [
                    'message' => 'import berhasil',
                    'data'    => ''
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }
    
    // generate mutual
    public function generateMutual(): JsonResponse
    {
        try {
            Mutual::truncate();
            $listFollowing = Following::get();

            foreach ($listFollowing as $row) {
                $exist = Followers::where("username", $row->username)->first();

                if ($exist) {
                    Mutual::create([
                        'username' => $row->username
                    ]);
                }
            }

            return response()->json(
                [
                    'message' => 'generate mutual berhasil',
                    'data'    => ''
                ],
                200
            );
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }
    
    // scrape mutual detail
    public function scrapeMutualDetail(Request $request, $username): JsonResponse
    {
        try {
            $apiUrl = env('API_PYTHON_URL') . '/instagram/scrape_mutual_detail?username=' . $username;

            $response = Http::get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                Mutual::where('username', $username)->update([
                    'posts'         => $data['data']['posts'],
                    'followers'     => $data['data']['followers'],
                    'following'     => $data['data']['following'],
                    'bio'           => $data['data']['bio'],
                    'is_verified'   => $data['data']['is_verified'],
                    'is_private'    => $data['data']['is_private'],
                    'is_scraped'    => 1,
                ]);

                return response()->json(
                    [
                        'message' => 'Scraping mutual detail berhasil',
                        'data'    => [
                            'scraping_status' => 'success',
                            'username'        => $username,
                            'result'          => $data['data'], // Data dari API Python
                        ]
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'message' => 'Scraping mutual detail gagal',
                        'data'    => [
                            'scraping_status' => 'failed',
                            'username'        => $username,
                            'error'           => $response->body(), // Detail error
                        ]
                    ],
                    $response->status()
                );
            }
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    // scrape mutual followers
    public function scrapeMutualFollowers(Request $request, $username): JsonResponse
    {
        try {
            // get mutual id
            $mutual = Mutual::where('username', $username)->first();
            MutualFollowers::where('mutual_id', $mutual->id)->delete();

            $rules  = $mutual->posts >= 6 && ($mutual->followers >= 3500 && $mutual->followers <= 9000) && $mutual->followers > $mutual->following && $mutual->is_private == 0;
            $rules  = $mutual->followers != null && $mutual->followers > 0;
            // $rules  = true;

            if ($rules == false) {
                return response()->json(
                    [
                        'message' => 'skip scraping',
                        'data'    => [
                            'scraping_status' => 'success',
                            'username'        => $username,
                        ]
                    ],
                    200
                );
            } else {
                $apiUrl   = env('API_PYTHON_URL') . '/instagram/scrape_mutual_followers?username=' . $username;
                $response = Http::get($apiUrl);
    
                if ($response->successful()) {
                    $data = $response->json();
    
                    // update status is_followers_scraper
                    $mutual->update([
                        'is_followers_scraped' => 1,
                    ]);
                    // insert mutual_followers
                    foreach ($data['data']['followers'] as $followerUsername) {
                        MutualFollowers::create([
                            'mutual_id' => $mutual->id,
                            'username'  => $followerUsername
                        ]);
                    }
    
                    return response()->json(
                        [
                            'message' => 'Scraping mutual followers berhasil',
                            'data'    => [
                                'scraping_status' => 'success',
                                'username'        => $username,
                                'result'          => $data['data'], // Data dari API Python
                            ]
                        ],
                        200
                    );
                } else {
                    return response()->json(
                        [
                            'message' => 'Scraping mutual followers gagal',
                            'data'    => [
                                'scraping_status' => 'failed',
                                'username'        => $username,
                                'error'           => $response->body(), // Detail error
                            ]
                        ],
                        $response->status()
                    );
                }
            }
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    // generate node & edge
    public function generateNodeEdge(Request $request): JsonResponse
    {
        try {
            $nodes_edges = [
                'nodes' => [],
                'edges' => []
            ];

            // create nodes
            $listMutual = Mutual::where("is_followers_scraped", 1)->get();
            foreach ($listMutual as $mutual) {
                $rules  = $mutual->posts >= 6 && ($mutual->followers >= 3500 && $mutual->followers <= 9000) && $mutual->followers > $mutual->following && $mutual->is_private == 0;
                $rules  = $mutual->followers != null && $mutual->followers > 0;
                // $rules  = true;

                if ($rules) {
                    $nodes_edges['nodes'][] = $mutual->username;
    
                    $listMutualFollowers = MutualFollowers::where('mutual_id', $mutual->id)->get();
                    foreach ($listMutualFollowers as $mutualFollower) {
                        $nodes_edges['nodes'][] = $mutualFollower->username;
                        $nodes_edges['edges'][] = [$mutual->username,$mutualFollower->username];
                    }
                }
            }

            // save json
            $folder   = "data"; // folder: database/data
            $fileName = "nodes_edges.json";
            $filePath = database_path($folder . '/' . $fileName);

            if (!File::exists(database_path($folder))) {
                File::makeDirectory(database_path($folder), 0755, true);
            }

            File::put($filePath, json_encode($nodes_edges, JSON_PRETTY_PRINT));

            return response()->json(
                [
                    'message' => 'node & edge berhasil digenerate',
                    'data'    => ''
                ],
                200
            );
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function generateCentralityMeasure($jsonData)
    {
        try {
            $graph = buildAdjacencyList($jsonData['nodes'], $jsonData['edges']);

            // Calculate centrality measures
            $degreeCentrality       = calculateDegreeCentrality($graph);
            $betweennessCentrality  = calculateBetweennessCentrality($jsonData['nodes'], $jsonData['edges'], $graph);
            $closenessCentrality    = calculateClosenessCentrality($graph);
            $eigenvectorCentrality  = calculateEigenvectorCentrality($graph);

            // Combine results
            $result = [
                'degree_centrality'         => $degreeCentrality,
                'betweenness_centrality'    => $betweennessCentrality,
                'closeness_centrality'      => $closenessCentrality,
                'eigenvector_centrality'    => $eigenvectorCentrality,
            ];

            // save json
            $folder   = "data"; // folder: database/data
            $fileName = "result.json";
            $filePath = database_path($folder . '/' . $fileName);

            if (!File::exists(database_path($folder))) {
                File::makeDirectory(database_path($folder), 0755, true);
            }

            File::put($filePath, json_encode($result, JSON_PRETTY_PRINT));
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }
}

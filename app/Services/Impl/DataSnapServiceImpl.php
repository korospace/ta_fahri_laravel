<?php

namespace App\Services\Impl;

use Carbon\Carbon;
use App\Models\Mutual;
use Illuminate\Http\Request;
use App\Services\DataSnapService;
use Illuminate\Http\JsonResponse;
use App\Exceptions\GeneralException;
use App\Models\Followers;
use App\Models\Following;
use App\Models\MutualFollowers;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class DataSnapServiceImpl implements DataSnapService
{
    // list followers
    public function listFollowers(Request $request): JsonResponse
    {
        try {
            $no    = 1;
            $query = Followers::query();

            // filter
            if ($request->search['value']) {
                $query->where('username', 'LIKE', '%' . $request->search['value'] . '%');
            }
            $rows = $query->select('id', 'username', 'href', 'timestamp')->orderBy('id', 'DESC')->get();

            return datatables()->of($rows)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('timestamp_formatted', function ($row) {
                    $timezone = env('APP_TIMEZONE', 'Asia/Jakarta');
                    return Carbon::createFromTimestamp($row->timestamp, $timezone)->format('Y-m-d H:i:s');
                })
                ->addColumn('href_html', function ($row) {
                    $html = "<a href='$row->href' target='_blank'>profile link</a>";
                    return $html;
                })
                ->rawColumns(['href_html'])
                ->toJson();
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    // list following
    public function listFollowing(Request $request): JsonResponse
    {
        try {
            $no    = 1;
            $query = Following::query();

            if ($request->search['value']) {
                $query->where('username', 'LIKE', '%' . $request->search['value'] . '%');
            }
            $rows = $query->select('id', 'username', 'href', 'timestamp')->orderBy('id', 'DESC')->get();

            return datatables()->of($rows)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('timestamp_formatted', function ($row) {
                    $timezone = env('APP_TIMEZONE', 'Asia/Jakarta');
                    return Carbon::createFromTimestamp($row->timestamp, $timezone)->format('Y-m-d H:i:s');
                })
                ->addColumn('href_html', function ($row) {
                    $html = "<a href='$row->href' target='_blank'>profile link</a>";
                    return $html;
                })
                ->rawColumns(['href_html'])
                ->toJson();
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    // list mutual
    public function listMutual(Request $request): JsonResponse
    {
        try {
            $no    = 1;
            $query = Mutual::query();

            if ($request->search['value']) {
                $query->where('username', 'LIKE', '%' . $request->search['value'] . '%');
            }
            $rows = $query->select('id', 'username', 'posts', 'followers', 'following', 'bio', 'is_verified', 'is_scraped', 'is_followers_scraped')->orderBy('id', 'DESC')->get();

            return datatables()->of($rows)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('posts_html', function ($row) {
                    $html = $row->posts 
                        ? "<span class='$row->id posts'>$row->posts</span>" 
                        : "<span class='$row->id posts'>-</span>";

                    return $html;
                })
                ->addColumn('followers_html', function ($row) {
                    $html = $row->followers 
                        ? "<span class='$row->id followers'>$row->followers</span>" 
                        : "<span class='$row->id followers'>-</span>";

                    return $html;
                })
                ->addColumn('following_html', function ($row) {
                    $html = $row->following 
                        ? "<span class='$row->id following'>$row->following</span>" 
                        : "<span class='$row->id following'>-</span>";

                    return $html;
                })
                ->addColumn('bio_html', function ($row) {
                    $html = $row->bio 
                        ? "<span class='$row->id bio'>$row->bio</span>" 
                        : "<span class='$row->id bio'>-</span>";

                    return $html;
                })
                ->addColumn('is_verified_html', function ($row) {
                    $html = $row->is_verified 
                        ? "<span class='$row->id is_verified text-info'><i class='fas fa-check-circle'></i></span>" 
                        : "<span class='$row->id is_verified'>-</span>";

                    return $html;
                })
                ->addColumn('is_scraped_html', function ($row) {
                    $html = $row->is_scraped 
                        ? "<span class='$row->id is_scraped text-secondary'><i class='fas fa-check-circle'></i></span>" 
                        : "<span class='$row->id is_scraped text-secondary'><i class='fas fa-times-circle'></i></span>";

                    return $html;
                })
                ->addColumn('is_followers_scraped_html', function ($row) {
                    $html = $row->is_followers_scraped 
                        ? "<span class='$row->id is_followers_scraped text-secondary'><i class='fas fa-check-circle'></i></span>" 
                        : "<span class='$row->id is_followers_scraped text-secondary'><i class='fas fa-times-circle'></i></span>";

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = $row->is_followers_scraped 
                        ? "<button class='{$row->id} btn btn-info show-followers' onclick='showModalFollowers({$row->id}, \"{$row->username}\")'><i class='fas fa-users'></i></button>"
                        : "<button class='{$row->id} btn btn-info show-followers' onclick='showModalFollowers({$row->id}, \"{$row->username}\")' disabled><i class='fas fa-users'></i></button>";

                    return $html;
                })
                ->rawColumns(['posts_html', 'followers_html', 'following_html', 'bio_html', 'is_verified_html','is_scraped_html','is_followers_scraped_html','action'])
                ->toJson();
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    // list mutual followers
    public function listMutualFollowers(Request $request): JsonResponse
    {
        try {
            $no    = 1;
            $query = MutualFollowers::query();

            if ($request->mutual_id) {
                $query->where("mutual_id", $request->mutual_id);
            }
            if ($request->search['value']) {
                $query->where('username', 'LIKE', '%' . $request->search['value'] . '%');
            }
            $rows = $query->select('id', 'mutual_id', 'username')->orderBy('id', 'DESC')->get();

            return datatables()->of($rows)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->toJson();
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    // get nodes & edges
    public function getNodesEdges(Request $request): JsonResponse
    {
        try {
            $folder   = "data"; // folder: database/data
            $fileName = "nodes_edges.json";
            $filePath = database_path($folder . '/' . $fileName);

            if (!File::exists($filePath)) {
                return response()->json(
                    [
                        'message' => 'nodes & edges tidak ditemukan',
                        'data'    => null
                    ],
                    404
                );
            } else {
                $fileContent = File::get($filePath);
                $jsonData    = json_decode($fileContent, true);

                return response()->json(
                    [
                        'message' => 'nodes & edges berhasil ditampilkan',
                        'data'    => $jsonData
                    ],
                    200
                );
            }
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    // get centrality measure
    public function getCentralityMeasure(Request $request): JsonResponse
    {
        try {
            $folder   = "data"; // folder: database/data
            $fileName = "result.json";
            $filePath = database_path($folder . '/' . $fileName);

            if (!File::exists($filePath)) {
                return response()->json(
                    [
                        'message' => 'centrality measure tidak ditemukan',
                        'data'    => null
                    ],
                    404
                );
            }

            // Membaca dan memproses file JSON
            $fileContent = File::get($filePath);
            $jsonData    = json_decode($fileContent, true);

            // Menyusun data dengan format yang diinginkan
            $nodes = [];
            foreach ($jsonData['degree_centrality'] as $username => $degreeCentrality) {
                $nodes[] = [
                    'username' => $username,
                    'degree_centrality' => number_format($degreeCentrality, 2),
                    'betweenness_centrality' => number_format($jsonData['betweenness_centrality'][$username] ?? 0, 2),
                    'closeness_centrality' => number_format($jsonData['closeness_centrality'][$username] ?? 0, 2),
                    'eigenvector_centrality' => number_format($jsonData['eigenvector_centrality'][$username] ?? 0, 2),
                ];
            }

            // Mengurutkan berdasarkan eigenvector_centrality (desc)
            usort($nodes, function ($a, $b) {
                return $b['eigenvector_centrality'] <=> $a['eigenvector_centrality'];
            });

            // Menambahkan nomor urut
            foreach ($nodes as $index => &$node) {
                $node['no'] = $index + 1;
            }

            return DataTables::of($nodes)
                ->make(true);
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

}

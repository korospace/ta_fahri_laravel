<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OverviewController extends Controller
{
    // protected $mainService;

    // public function __construct(ProgressUploadService $mainService)
    // {
    //     $this->mainService = $mainService;
    // }

    /**
     * View - Dashboard - Overview
     *
     * - show dashboard overview page
     * -------------------------------
     */
    public function overviewPage(Request $request)
    {
        $data = [
            'metaTitle' => 'Overview',
            'user'      => $request->user,
            'dataTab'   => $request->dataTab
        ];

        return view('pages/dashboard_overview/dashboard_overview', $data);
    }
}

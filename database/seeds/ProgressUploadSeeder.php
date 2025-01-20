<?php

use App\Models\ProgressUpload;
use Illuminate\Database\Seeder;

class ProgressUploadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            [
                'id' => 1,
                "progress_status" => 'completed',
                "tab_active" => 'tab_upload_data',
                "tab_upload_data" => 'disabled',
                "tab_mutual" => 'disabled',
                "tab_mutual_detail" => 'disabled',
                "tab_mutual_followers" => 'disabled',
                "tab_node_graph" => 'disabled'
            ],
        ];

        foreach ($rows as $row) {
            ProgressUpload::firstOrCreate($row);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use App\Events\BatchProcessingProgressUpdated;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{

    public function index()
    {
        $allBatches = self::getAllBatchs();

        return view('upload-file', ['allBatches' => $allBatches]);
    }

    public function uploadCsv()
    {
        if (request()->has('mycsv')) {
            $batch = Bus::batch([])->then(function (Batch $batch) {
                $progress = Bus::findBatch($batch->id);

                event(new BatchProcessingProgressUpdated($progress->toArray()));
            })->dispatch();

            $data = file(request()->mycsv);
            $chunks = array_chunk($data, 1000);
            $header = [];

            foreach ($chunks as $key => $chunk) {
                $data = array_map('str_getcsv', $chunk);
                if ($key === 0) {
                    $header = array_shift($data);
                }
                $batch->add(new SalesCsvProcess($data, $header, $batch->id));
            }
        }
    }

    public function getAllBatchs()
    {
        return DB::table('job_batches')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
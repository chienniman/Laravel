<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use App\Events\BatchProcessingProgressUpdated;

class SalesController extends Controller
{
    public function index()
    {
        return view('upload-file');
    }

    public function uploadCsv()
    {
        if (request()->has('mycsv')) {
            $batch = Bus::batch([])->then(function (Batch $batch) {
                $progress=Bus::findBatch($batch->id);
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
                $batch->add(new SalesCsvProcess($data, $header,$batch->id));
            }
        }
    }
}
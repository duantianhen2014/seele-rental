<?php

namespace App\Jobs;

use App\Models\HashResult;
use App\Seele\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class HashQueryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $hashResult;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(HashResult $hashResult)
    {
        $this->hashResult = $hashResult;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = new Request;

        DB::beginTransaction();
        try {
            $result = $request->queryHash($this->hashResult->tx_hash);

            $this->hashResult->result = json_encode($result);
            $this->hashResult->save();

            $method = 'handler' . $this->hashResult->request_type;
            $this->hashResult->{$method}();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }
}

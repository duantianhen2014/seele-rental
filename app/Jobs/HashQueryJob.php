<?php

namespace App\Jobs;

use Exception;
use App\Models\HashResult;
use App\Models\Rental;
use App\Seele\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        DB::beginTransaction();
        try {
            $result = (new Request)->queryHash($this->hashResult->tx_hash);
            Log::info($result);

            if (isset($result['code'])) {
                $this->hashResult->result = $result;
                $this->hashResult->save();

                // Rental has revert
                Rental::removeHash($this->hashResult);
            } else {
                $this->hashResult->result = json_encode($result);
                $this->hashResult->save();

                $method = 'handler' . $this->hashResult->request_type;
                $this->hashResult->{$method}();
            }

            DB::commit();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
        }
    }
}

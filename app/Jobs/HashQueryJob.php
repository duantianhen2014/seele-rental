<?php

namespace App\Jobs;

use App\Notifications\ErrorMessageNotification;
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
            $returnData = (new Request)->payloadDecode($result['result']);
            $returnData = array_map('hexdec', $returnData);
            $isError = $returnData[0];

            if ($isError) {
                $this->hashResult->result = json_encode($result);
                $this->hashResult->save();
                Rental::removeHash($this->hashResult);

                $this->hashResult->user->notify(new ErrorMessageNotification($this->hashResult->request_type, $isError));
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

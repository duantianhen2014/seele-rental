<?php

namespace App\Console\Commands;

use App\Models\HashResult;
use Illuminate\Console\Command;

class HashQueryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hash:query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'hash query.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $hashUnHandlerRecords = HashResult::whereNull('result')->orderBy('created_at')->get();
        foreach ($hashUnHandlerRecords as $hashUnHandlerRecord) {
            
        }
    }
}

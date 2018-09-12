<?php

namespace App\Console\Commands;

use App\Seele\Request;
use App\Seele\Seele;
use App\Seele\User;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test.';

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
        $user = new User(
            '0x038d4a24162145a459be86b268e9b9e70a3b5691',
            '0x4d9bcb3563269329fc8eeb14348cca47b8e4e1994cc96cdd820287dc18098e1b'
        );
//
//        $seele = new Seele($user);
//        var_dump($seele->queryBalance());

//        $result = (new Request)->payloadDecode('0x00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001');
//        $result = array_map('hexdec', $result);
//        dd($result);
    }
}

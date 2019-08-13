<?php

namespace App\Console\Commands;

use App\Http\Common;
use App\Models\ShopToken;
use Illuminate\Console\Command;

class CheckOrderIn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CheckOrderIn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检测订单';

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
        //获取每个店铺的订单写入order表，同时更新variant stock
        foreach (ShopToken::class as $shop_token){
            $response = Common::getOrder($shop_token);
            $response = json_decode($response, true);

            var_dump($response);
        }
    }
}
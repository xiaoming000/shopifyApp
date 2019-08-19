<?php

namespace App\Console\Commands;

use App\Http\Common;
use App\Http\Controllers\Order\OrderController;
use App\Models\ShopToken;
use Illuminate\Console\Command;

/**
 * 定时任务, 定时更新shopify产生的订单数据
 *
 * @author dengweixiong
 */
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
    protected $description = '同步获取订单';

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
        // 定时执行任务
        $order_controller = new OrderController();
        $order_controller->getOrderAndTemp();
    }
}

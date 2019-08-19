<?php
namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class  GoodsConfigController extends Controller{

    public function autoPush(Request $request){

        return view('config.goods');
    }

//    public function autoPushSet(Request $request){
//
//
//    }


}
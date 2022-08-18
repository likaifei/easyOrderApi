<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function error($msg){
        return response()->json([
            'code' => -1,
            'msg' => $msg
        ]);
    }
    public function success($data = [], $msg = ''){
        return response()->json([
            'code' => 1,
            'msg' => $msg,
            'data' => $data
        ]);
    }
}

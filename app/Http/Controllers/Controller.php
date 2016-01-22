<?php namespace App\Http\Controllers;

//use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
//use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	//use ValidatesRequests;
    public function responseJson($data,$msg,$code){
        if(!is_string($msg)){
            $msg=json_decode(json_encode($msg));
        }
        return response()->json([
            'data' => $data,
            'msg' => $msg,
            'code' => $code
        ]);
    }

}

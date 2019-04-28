<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SendController extends Controller
{
	public function index()
	{
		
	}
	
    public function reg()
    {
    	if (request()->isMethod('post')&&request()->ajax()) {
    		$val=request()->val;
    		$type=request()->type;
    		$code=rand(1000,9999);
    		if ($type=='email') {
    			$this->sendMail($val,'注册验证','emails.test',['data'=>$code]);
    		}else if($type=='phone'){
    			$this->sendSms($val,$code);
    		}
    		request()->session()->put('reg',['id'=>$val,'code'=>$code]);
    	}
    }


    public function sendMail($address,$subject,$view,$arrdata){
        //$arrdata为数组
        return \Mail::send($view,$arrdata,
            function($message)use($address,$subject){
              $to = $address;//收件人
              $message ->to($to)->subject($subject);//主题
         });
    }

    /** 短信发送 */
    public function sendSms($phone,$code,$templateId='TP1711063'){
        $host = "http://dingxin.market.alicloudapi.com";
        $path = "/dx/sendSms";
        $method = "POST";
        $appcode = "e36fd5ed3abe4199b11990c2526c9aff";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "mobile={$phone}&param=code%3A{$code}&tpl_id={$templateId}";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

         $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        return curl_exec($curl);
    }


    
}

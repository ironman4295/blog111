<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\UsersModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Volidateuser;

class LoginController extends Controller
{
    //登陆+验证
    public function index()
    {
    	if (request()->isMethod('post')) {
    		$str='';
    		$name=request()->name;
    		$password=request()->password;
    		if (preg_match('/^\d{11}$/',$name)) {
    			$str='photo';
    		}else{
    			$str='email';
    		}
            $user = Auth::user();
            if (Auth::attempt(['email' => $name, 'password' => $password])) {
             // 认证通过...
             return redirect()->intended('/');
            }


           // if (Auth::attempt([$str => $name, 'password' => $password])) {

           //      return redirect()->intended('/');
           //  }
            return view('index.login', ['error' => '账号或密码不正确']);
    	}
    	return view('index.login');
    }

    //注册
    public function add()
    {
    	return view('index.reg');
    }

    //验证注册
    public function doadd(Volidateuser $request)
    {
    	if (request()->isMethod('post')&&request()->ajax()) {
    		$type=request()->type;
    		$id=request()->id;
    		$code=request()->code;
    		$pwd=request()->pwd;
    		$psd=request()->psd;
    		$regcode=session('reg');
            if ($type=='email'){
                $reg='/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/';
            }else if($type=='phone'){
                $reg='/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/';
            }
            if (!preg_match($reg,$id)){
                echo json_encode(['code' => 0]);die;
            }
            if($pwd!=$psd){
                echo json_encode(['code' => 0]);die;
            }
            if($regcode['id']==$id&&$regcode['code']==$code){
                $data=[
                    $type=>$id,
                    'password'=>Hash::make($pwd),
                    'code'=>$code
                ];
                $res=UsersModel::insert($data);
                if ($res){
                    echo json_encode(['code' => 1]);
                    session(['reg' => null]);
                }else{
                    echo json_encode(['code' => 0]);die;
                }
            }else{
                echo json_encode(['code' => 0]);die;
            }
    	}
    }

     //注销
    public function logout()
    {
        Auth:: logout();
        return view('index.login');
    }

    //验证唯一
    public function unique()
    {
        if (request()->isMethod('post') && request()->ajax()) {
            $type = request()->type;
            $val = request()->val;
            $res = UsersModel::where($type, $val)->count();
            if ($res) {
                echo 1;
            } else {
                echo 0;
            }
        }


    }

    public function islogin(){
        if ($this->is_login()){
            return 1;
        }else{
            return 0;
        }
    }

    /** 验证是否登陆 */
    public function is_login()
    {
        return $res = Auth::user();
    }
}

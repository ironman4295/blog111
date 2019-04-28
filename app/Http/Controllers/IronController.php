<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Volidateuser;
use Illuminate\Support\Facades\Hash;
use App\Model\UsersModel;

class IronController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function first(){
        $res=cache('users');
        cache(['aaaaaa'=>1],5);
        return view('iron.first');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function reg()
    {
        return view('iron.reg');
    }

    public function doreg(Volidateuser $request)
    {
        if (request()->isMethod('post')&&request()->ajax()) {
            $email=request()->email;
            $id=request()->id;
            $code=request()->code;
            $pwd=request()->pwd;
            $psd=request()->psd;
            $regcode=session('reg');
            if (empty($id)) {
                echo json_encode(['code' => 0,'msg'=>'账号必填']);die;
            }
            if($pwd!=$psd){
                echo json_encode(['code' => 0,'msg'=>'验证密码不一致']);die;
            }
            
            if($regcode['id']==$id&&$regcode['code']==$code){
                $data=[
                    'email'=>$id,
                    'password'=>Hash::make($pwd),
                    'code'=>$code
                ];
                $user=new UsersModel;
                $res=$user->insert($data);
                if ($res){
                    echo json_encode(['code' =>1,'msg'=>'注册成功']);die;
                    session(['reg' => null]);
                }else{
                    echo json_encode(['code' => 0,'msg'=>'注册失败']);die;
                }
            }else{
                echo json_encode(['code' => 0,'msg'=>'账号验证码不匹配']);die;
            }

        }

    }

    public function login()
    {
        if (request()->isMethod('post')) {
            $email=request()->email;
            $password=request()->password;

            if (empty($email)) {
                echo json_encode(['code' => 0,'msg'=>'邮箱必填']);die;
            }
            if (empty($password)) {
                echo json_encode(['code' => 0,'msg'=>'密码必填']);die;
            }

            $model=new UsersModel;
            $userInfo=$model->where('email',$email)->first();

            if (!empty($userInfo)) {
                $now=time();
                $last_error_time=$userInfo['last_error_time'];
                $error_num=$userInfo['error_num'];
                $model=new UsersModel;
                $where=['id'=>$userInfo['id']];
            

                $user = Auth::user();
                if (Auth::attempt(['email' => $email, 'password' => $password])) {
                   
                    // 认证通过...
                    cache(['users'=>$email],300);
                    
                    if ($error_num>=5&&$now-$last_error_time<3600) {
                        
                        $secode=60-ceil((time()-$last_error_time)/60);
                        echo json_encode(['code' => 0,'msg'=>'账号已锁定，请'.$second.'分钟后重新登录']);die;

                        //错误次数清零 时间改为null
                        $updateInfo=[
                            'error_num'=>0,
                            'last_error_time'=>null
                        ];
                        DB::table('users')->where($where)->update($updateInfo);
                    }
                    return redirect()->intended('/iron/first');
                }else{
                    if ($now-$last_error_time>3600) {
                        $updateInfo=[
                            'error_num'=>1,
                            'last_error_time'=>$now
                        ];
                        $res=DB::table('users')->where($where)->update($updateInfo);
                        if ($res) {
                            echo json_encode(['code' => 0,'msg'=>'账号或密码有误']);die;
                        }
                    }else{
                       if ($error_num>=5) {
                            $second=60-ceil((time()-$last_error_time)/60);
                            echo json_encode(['code' => 0,'msg'=>'账号已锁定，请'.$second.'分钟后重新登录']);die;
                        }else{
                            $updateInfo=[
                            'error_num'=>$error_num+1,
                            'last_error_time'=>$now
                            ];
                            $res=DB::table('users')->where($where)->update($updateInfo);
                            if ($res) {
                                $count=5-($error_num+1);
                                echo json_encode(['code' => 0,'msg'=>'账号已锁定，请'.$count.'分钟后重新登录']);die;
                            }
                        } 
                    }
                }  
            }else{
                echo json_encode(['code' => 0,'msg'=>'账号或密码有误']);die;
            }
        }else{
            return view('iron.login');
        }
        
    }

    public function uniques()
    {
        if (request()->isMethod('post')&&request()->ajax()) {
        
            $email=request()->email;

            if (!$email) {
                echo 1;
            }
            $where=['email'=>$email];

            $count=DB::table('users')->where($where)->count();
            if ($count) {
                echo 1;
            }else{
                echo 2;
            }
        }
    }

    public function send()
    {
        if (request()->isMethod('post')&&request()->ajax()) {
            $email=request()->email;
            $code=rand(1000,9999);
            $this->sendMail($email,'注册验证','emails.test',['data'=>$code]);

            request()->session()->put('reg',['id'=>$email,'code'=>$code]);
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
}
?>
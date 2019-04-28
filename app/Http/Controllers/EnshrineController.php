<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\EnshrineModel;
use App\Model\GoodsModel;
use Illuminate\Support\Facades\Auth;

class EnshrineController extends Controller
{

    public function index()
    {

            $user_id=$this->getUserId();
            $enshrine = EnshrineModel::leftjoin('goods', 'goods.good_id', '=', 'enshrine.good_id')->where('user_id', $user_id)->get()->toArray();
            cache(['enshrine_enshrine' . $user_id=>$enshrine],config('app.cache_time'));
        return view('index.enshrine',['enshrine'=>$enshrine]);
    }

    //添加/删除
    public function add()
    {
        if (!$this->is_login()){
            echo json_encode(['code' => 0, 'msg' => '登陆后才可收藏']);die;
        }
        if (request()->isMethod('post')&&request()->ajax()){
            $good_id=request()->good_id;
            $user_id=$this->getUserId();
            $where=[
                'user_id'=>$user_id,
                'good_id'=>$good_id
            ];
            $res=EnshrineModel::where($where)->first();
            if ($res){
                $del=EnshrineModel::where($where)->delete();
                if ($del){
                    echo json_encode(['code'=>1,'msg'=>'取消收藏成功']);die;
                }else{
                    echo json_encode(['code' => 0, 'msg' => '操作失败']);die;
                }
            }else{
                $insert=EnshrineModel::insert($where);
                if ($insert){
                    echo json_encode(['code' => 1, 'msg' => '收藏成功']);die;
                }else{
                    echo json_encode(['code' => 0, 'msg' => '操作失败']);die;
                }
            }
        }else{
            echo json_encode(['code' => 0, 'msg' => '操作非法']);die;
        }
    }

    /** 验证是否登陆 */
    public function is_login()
    {
        return $res = Auth::user();
    }

    /** 获得登陆用户id */
    public function getUserId()
    {
        return $res = Auth::user()->id;
    }
}

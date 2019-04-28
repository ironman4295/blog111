<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\GoodsModel;
use App\Model\GoodscartModel;
use Illuminate\Support\Facades\Auth;

class GoodCarController extends Controller
{

    public function index(){

    		$user_id=$this->getUserId();
            $where = [
                'user_id' => $user_id,
                'goods.is_del' => 1,
                'goodcart.is_del' => 1
            ];
            //$carInfo=GoodscartModel::where($where)->orderBy('add_time','desc')->get();
            if (!$carInfo = cache('carInfo' .$user_id)) {
                $carInfo = GoodscartModel::leftjoin('goods', 'goods.good_id', '=', 'goodcart.good_id')->where($where)->get();
                if ($carInfo) {
                    foreach ($carInfo as $k => $v) {
                        $carInfo[$k]['total'] = $v['buy_num'] * $v['good_onprice'];
                    }
                }
                cache(['carInfo' .$user_id=> $carInfo], 60 * 10);
            }

            //注解：判断数据库对象是否为空，需进行过滤取值，json对象可过滤数据库对象属性为json纯净数组，反格式取php数组
            $json_obj = json_encode($carInfo);
            $flag = json_decode($json_obj);

    	return view('index.car',['delcart'=>1,'carInfo'=>$carInfo,'flag'=>$flag]);
    }

    //添加购物车
    public function addcar(){
        //code：0未登陆，2非法传值，3已下架，4超出库存，1
        if (request()->isMethod('post')&&request()->ajax()){
            $good_id=request()->good_id;
            $buy_num=request()->good_buynum;//购买数量

            if (!$this->is_login()) {
                echo json_encode(['code'=>0,'msg'=>'请登录...']);die;
            }else{
                    if (!$good_num = cache('good_num' . $good_id)) {
                        $good_num = GoodsModel::where(['good_id' => $good_id, 'is_on_sale' => 1])->value('good_num');
                        cache(['good_num' . $good_id => $good_num], 60 * 10);
                    }
                if ($good_num){
                    $res=$this->addCartDb($good_id,$buy_num,$good_num);
                }else{
                    echo json_encode(['code'=>3,'msg'=>'商品已下架']);die;
                }
            }
            if ($res) {
                echo json_encode(['code'=>1,'msg'=>'加入购物车成功']);die;
            }else{
                echo json_encode(['code'=>4,'msg'=>'超出库存']);die;
            }
        }
        echo json_encode(['code'=>2,'msg'=>'非法传值']);die;

    }

    //获取小计/总价//价格相关禁用缓存
    public function gettotal(){
        if (request()->ajax()&&request()->isMethod('post')){
            $good_id=request()->good_id;
            $good_id=explode(',',$good_id);
            $user_id=$this->getUserId();
            $Moneytotal=0;
            $where=[
                'is_on_sale'=>1,
                'goods.is_del'=>1,
                'goodcart.is_del'=>1,
                'user_id'=>$user_id
            ];
            $carInfo = GoodscartModel::leftjoin('goods','goods.good_id','=','goodcart.good_id')
            ->where($where)
            ->whereIn('goods.good_id',$good_id)
            ->orderBy('cart_id','desc')
            ->select('good_onprice','buy_num')
            ->get();

            foreach ($carInfo as $k => $v) {
                $Moneytotal+=$v['good_onprice']*$v['buy_num'];
            }
            return $Moneytotal;
        }
    }
    //更新购买数量
    public function savecar(){
        if (request()->ajax()&&request()->isMethod('post')){
            $good_id=request()->good_id;
            $buy_num=request()->buy_num;

            $user_id=$this->getUserId();
            $where=[
                'good_id'=>$good_id,
                'user_id'=>$user_id
            ];
            GoodscartModel::where($where)->update(['buy_num'=>$buy_num,'save_time'=>time()]);
            cache(['carInfo' .$user_id=> ''],0);
        }
    }
    //删除购物车（软）
    public function cartdel(){
        if (request()->ajax()&&request()->isMethod('post')){
            $good_id=request()->good_id;
            $good_id=explode(',',$good_id);
            $user_id=$this->getUserId();

            $res=GoodscartModel::where('user_id',$user_id)->whereIn('good_id',$good_id)->update(['is_del'=>2]);
            if ($res) {
                cache(['carInfo' .$user_id=> ''],0);
                echo 1;
            }else{
                echo 0;
            }
        }
    }
    //操作添加Db购物车
    public function addCartDb($good_id,$buy_num,$good_num)
    {
    	$user_id=$this->getUserId();
        $where = [
            'good_id' => $good_id,
            'user_id' => $user_id,
            'is_del' => 1
        ];

        $res = GoodscartModel::where($where)->first();

        if ($res) {
            //有-修改
            if (($res['buy_num']+$buy_num)>$good_num) {
                //大于库存
                return false;
            }else{
                $num=$res['buy_num']+$buy_num;
                $result=GoodscartModel::where($where)->update(['buy_num'=>$num,'save_time'=>time()]);
                if ($result) {
                    cache(['carInfo' .$user_id=> ''],0);
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            //无-添加
            if ($buy_num>$good_num) {
                //大于库存
                return false;
            }else{
                $data=[
                    'good_id'=>$good_id,
                    'buy_num'=>$buy_num,
                    'user_id'=>$user_id,
                    'add_time'=>time()
                ];
                $result=GoodscartModel::insert($data);
                if ($result) {
                    cache(['carInfo' .$user_id=> ''],0);
                    return true;
                }else{
                    return false;
                }
            }
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

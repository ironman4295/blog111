<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\GoodsModel;
use App\Model\GoodscartModel;
use App\Model\AddressModel;
use App\Model\AreaModel;
use App\Model\OrderModel;
use App\Model\Order_addressModel;
use App\Model\Order_detailModel;
use Illuminate\Support\Facades\Auth;
use \DB;//引入Db为使用事务

class OrderController extends Controller
{
    public function lists()
    {
        if (request()->ajax() && request()->isMethod('post')) {
            $type = request()->type;
        }
        $user_id=$this->getUserId();
        $where = [
            'user_id' => $user_id,
            'pay_status' => 0,
            'is_del' => 1
        ];
        if (!$order=cache('order'.$user_id)){
            $order = OrderModel::where($where)->orderBy('order_id','desc')->get()->toArray();
            cache(['order'.$user_id=>$order],config('app.cache_time'));
        }

        $order = $this->createSonTree($order);
        return view('index.orderlist', ['order' => $order]);
    }

    //删除
    public function orderdel()
    {
        if (request()->ajax() && request()->isMethod('post')) {
            $order_id = request()->order_id;
            $user_id=$this->getUserId();
            if ($order_id) {
                $where = [
                    'user_id' => $user_id,
                    'order_id' => $order_id
                ];
                $res = OrderModel::where($where)->update(['is_del' => 2]);
                if ($res) {

                    cache(['order'.$user_id=>''],0);
                    echo json_encode(['code' => 1, 'msg' => '取消订单成功']);die;
                    
                } else {
                    echo json_encode(['code' => 0, 'msg' => '取消订单失败']);die;
                    
                }
            } else {
                
                echo json_encode(['code' => 0, 'msg' => '非法数据']);die;
            }
        }
    }

    public function orderstatus()
    {
        if (request()->isMethod('post') && request()->ajax()) {
            $type = request()->type;
            $user_id=$this->getUserId();
            $where = [
                'user_id' => $user_id,
                'order_status' => $type,
                'is_del' => 1
            ];
            if (!$order=cache('order'.$user_id.$type)) {
                $order = OrderModel::where($where)->get()->toArray();
                cache(['order'.$user_id.$type=>$order],config('app.cache_time'));
            }
            if ($order) {
                $order = $this->createSonTree($order);
            } else {
                echo json_encode(['code' => 0, 'msg' => '此分类无数据']);die;
                
            }
            return view('index.orderlistdiv', ['order' => $order]);
        } else {
            echo json_encode(['code' => 0, 'msg' => '非法数据']);die;
        }
    }

    //成功提示页
    public function success()
    {
        $order_id = request()->order_id;
        if (empty($order_id)) {
            echo "<script>alert('商品信息有误');</script>";
        }
        if (!$order=cache('order'.$order_id)){
            $order = OrderModel::where('order_id', $order_id)->first()->toArray();
            cache(['order'.$order_id=>$order],config('app.cache_time'));
        }
//        dd($order);
        return view('index.success', ['order' => $order]);

    }

    //添加页面
    public function add($good_id = 0, $buy_num = 0)
    {
        if (empty($good_id)) {
            echo '<script>alert("非法操作");location.href="/index/prolist"</script>';
        }
        $user_id=$this->getUserId();
        //展示数据
        if ($buy_num == 0) {
            $type = 0;
            //查询传递全部id
            $where=[
                'is_on_sale'=>1,
                'goods.is_del'=>1,
                'goodcart.is_del'=>1,
                'user_id'=>$user_id
            ];
            $arr_good_id = explode(',', $good_id);
            $Moneytotal = 0;
            $goodInfo = GoodscartModel::leftjoin('goods', 'goods.good_id', '=', 'goodcart.good_id')
                ->where($where)
                ->whereIn('goods.good_id', $arr_good_id)
                ->orderBy('cart_id', 'desc')
                ->get();
            foreach ($goodInfo as $k => $v) {
                $goodInfo[$k]['total'] = $v['good_onprice'] * $v['buy_num'];
                $Moneytotal += $v['good_onprice'] * $v['buy_num'];
            }
        } else {
            $type = 1;
            //根据id和数量计算
            $where = [
                'is_on_sale' => 1,
                'good_id' => $good_id
            ];
            $goodInfo = GoodsModel::where($where)->get();
            foreach ($goodInfo as $k => $v) {
                $goodInfo[$k]['add_time'] = time();
                $goodInfo[$k]['buy_num'] = $buy_num;
                $goodInfo[$k]['total'] = $v['good_onprice'] * $buy_num;
                $Moneytotal = $v['good_onprice'] * $v['buy_num'];
            }
        }
        //地址数据
        if (!$address = cache('address' .$user_id)) {
            $address = AddressModel::where('user_id', $user_id)->get()->toArray();
            cache(['address' .$user_id=> $address], config('app.cache_time'));
        }

        if ($address) {
            foreach ($address as $k => $v) {
                if (!$address[$k]['province'] = cache('area' .$v['province'])) {
                    $address[$k]['province'] = AreaModel::where('id', $v['province'])->value('name');
                    cache(['area' .$v['province']=> $address[$k]['province']], config('app.cache_time'));
                }
                if (!$address[$k]['city'] = cache('area' .$v['city'])) {
                    $address[$k]['city'] = AreaModel::where('id', $v['city'])->value('name');
                    cache(['area' .$v['city']=> $address[$k]['city']], config('app.cache_time'));
                }
                if (!$address[$k]['area'] = cache('area' .$v['area'])) {
                    $address[$k]['area'] = AreaModel::where('id', $v['area'])->value('name');
                    cache(['area' .$v['area']=> $address[$k]['area']], config('app.cache_time'));
                }
            }
        } else {
            $address = '';
        }
        return view('index.order', ['buy_num' => $buy_num, 'goodInfo' => $goodInfo, 'Moneytotal' => $Moneytotal, 'type' => $type, 'address' => $address, 'good_id' => $good_id]);
    }

    //验证并添加
    public function orderdoadd()
    {
        if (request()->isMethod('post') && request()->ajax()) {
            $good_id = request()->good_id;

            $type = request()->type;//详情页路径提交用(区分)
            $buy_num=request()->buy_num;//详情页路径提交用

//            $ordermoney=\request()->ordermoney;
            $address_id = request()->address_id;
            $user_id = $this->getUserId();
            $pay = 1;//@ 1为支付宝
            DB::beginTransaction();
            try {
                if (empty($good_id)) {
                    throw new \Exception('至少选择一个商品');
                }
                if (empty($address_id)) {
                    throw new \Exception('至少选择一个收货地址');
                }
                if (empty($pay)) {
                    throw new \Exception('至少选择一种支付方式');
                }
                //订单表Order  id  订单号  订单金额  支付方式  支付状态  订单状态  订单留言  user_id
                //获得总价↓
                //订单表order   // 查询商品总价
                $totalmoney = json_decode($this->ordermoney($good_id));
                $totalmoney = $totalmoney->msg;
                $order_on = $this->Ordernumber();

                $data = [
                    'order_on' => $order_on,
                    'totalmoney' => $totalmoney,
                    'pay' => $pay,
                    'user_id' => $user_id,
                    'create_time' => time()
                ];
                $res1 = OrderModel::create($data);


                $order_id = $res1->order_id;
                if (empty($order_id)) {
                    throw new \Exception('订单写入失败');
                }
                //订单详情order_detail  id  商品名称  价格  购买数量  订单id
                if ($type == 0) {
                    $good = $this->getgood($good_id);
                    if (empty($good)) {
                        throw new \Exception('商品信息错误');
                    }
                } else {
                    $good = GoodsModel::where('good_id', $good_id)->select(['good_name', 'good_id', 'good_onprice', 'good_num', 'good_img'])->get()->toArray();

                    $good[0]['user_id'] = $user_id;

                    $good[0]['buy_num'] = $buy_num;
                }

                foreach ($good as $k => $v) {
                    $v['order_id'] = $order_id;
                    $v['order_on'] = $order_on;
                    $v['user_id'] = $user_id;
                    $v['create_time'] = time();
                    $res2 = Order_detailModel::create($v);

                    if (empty($res2)) {
                        throw new \Exception('订单详情写入失败');
                    }
                }

                //订单地址order_address  id 姓名  电话  邮编  省市区  详细地址  订单id
                $Address = AddressModel::where('id', $address_id)->first()->toArray();
                unset($Address['id']);
                $Address['order_id'] = $order_id;
                $Address['order_on'] = $order_on;
                $Address['create_time'] = time();

                $res3 = Order_addressModel::create($Address);
                if (empty($res3)) {
                    throw new \Exception('订单地址写入失败');
                }
                if ($type == 0) {

                    //删除购物车
                    $where = [
                        'user_id' => $user_id,
                        'is_del' => 1
                    ];
                    $good_id = explode(',', $good_id);
                    $res4 = GoodscartModel::where($where)->whereIn('good_id', $good_id)->update(['is_del' => 2]);

                    if (empty($res4)) {
                        throw new \Exception('删除购物车失败');
                    }else{
                        cache(['carInfo' .$user_id=> ''],0);
                    }
                }

                //减少库存
                foreach ($good as $k => $v) {
                    $good_num = $v['good_num'] - $v['buy_num'];
                    $res5 = GoodsModel::where('good_id', $v['good_id'])->update(['good_num' => $good_num]);
                    if (empty($res5)) {
                        throw new \Exception('减少库存失败');
                    }else{
                        cache(['goodlist'.$v['good_id']=> ''],0);
                    }
                }
                // 提交事务
                DB::commit();

                cache(['order'.$user_id=>''],0);

                $arr = [
                    'code' => 1,
                    'msg' => '下单成功',
                    'order_id' => $order_id
                ];
                echo json_encode($arr);
            } catch (\Exception $e) {

                // 回滚事务
                DB::rollBack();
                echo json_encode(['code' => 0, 'msg' => $e->getMessage()]);die;
                
            }
        } else {
            echo json_encode(['code' => 0, 'msg' => '非法操作']);die;
        }
    }

    //计算order总价
    public function ordermoney($good_id_all = 0, $type = 0, $kfc = 0, $deduction = 0, $caringmoney = 0)
    {
        $type = request()->type;
        $buy_num = request()->buy_num;
        if (!$good_id_all) {
            $good_id_all = request()->good_id_all;
        }

        $kfc = request()->kfc;
        if ($kfc == 0) {
            $kfc = 10;
        }
        $kfc = $kfc * 0.1;

        $deduction = request()->deduction;
        $caringmoney = request()->caringmoney;
        //格式化折扣


        if ($type == 0) {
            //查询传递全部id
            $good_id_all = explode(',', $good_id_all);
            $Moneytotal = 0;
            $user_id=$this->getUserId();
            $where=[
                'is_on_sale'=>1,
                'user_id'=>$user_id
            ];
            $goodInfo = GoodscartModel::leftjoin('goods', 'goods.good_id', '=', 'goodcart.good_id')
                ->where($where)
                ->whereIn('goods.good_id', $good_id_all)
                ->select('good_onprice', 'buy_num')
                ->get()
                ->toArray();
            foreach ($goodInfo as $k => $v) {
                $Moneytotal += $v['good_onprice'] * $v['buy_num'];
            }
            $ordermoney = $Moneytotal * $kfc - $deduction + $caringmoney;
            if ($ordermoney <= 0) {
                return json_encode(['code' => 1, 'msg' => 0]);
                ok('0');
            } else {
                return json_encode(['code' => 1, 'msg' => $ordermoney]);
                ok($ordermoney);
            }
        } else {
            $where = [
                'is_on_sale' => 1,
                'good_id' => $good_id_all
            ];
            $goodInfo = GoodsModel::where($where)->first()->toArray();
            $Moneytotal = $goodInfo['good_onprice'] * $buy_num;
            $ordermoney = $Moneytotal * $kfc - $deduction + $caringmoney;
            if ($ordermoney <= 0) {
                return json_encode(['code' => 1, 'msg' => 0]);
                ok('0');
            } else {
                return json_encode(['code' => 1, 'msg' => $ordermoney]);
                ok($ordermoney);
            }
        }
    }

    //订单号生成
    public function Ordernumber()
    {
        $user_id=$this->getUserId();
        return date('Ymdhis') . $user_id . rand(1000, 9999);
    }

    //获取商品信息
    public function getgood($good_id)
    {
        $user_id=$this->getUserId();
        $where = [
            'is_on_sale' => 1,
            'goods.is_del' => 1,
            'goodcart.is_del'=>1,
            'user_id' => $user_id
        ];
        $good_id = explode(',', $good_id);
        $good = GoodscartModel::leftjoin('goods', 'goods.good_id', '=', 'goodcart.good_id')
            ->where($where)
            ->whereIn('goods.good_id', $good_id)
            ->orderBy('cart_id', 'desc')
            ->select(['user_id', 'good_name', 'goods.good_id', 'good_onprice', 'buy_num', 'good_num', 'good_img'])
            ->get()
            ->toArray();
        if (!empty($good)) {
            return $good;
        } else {
            return false;
        }
    }

    public function createSonTree($order)
    {
        if ($order) {
            foreach ($order as $k => $v) {
                $order[$k]['son'] = Order_detailModel::leftjoin('goods', 'goods.good_id', '=', 'order_detail.good_id')
                    ->where('order_id', $v['order_id'])->get()->toArray();
            }
            return $order;
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

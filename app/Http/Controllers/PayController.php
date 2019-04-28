<?php

namespace App\Http\Controllers;

use \DB;
use Illuminate\Http\Request;
use App\Model\OrderModel;
use \Log;

class PayController extends Controller
{
     //去支付
    public function alipay($id){
        //总价格
        $allprice = DB::table('order')->where('order_on',$id)->value('totalmoney');



        if(!$allprice){
            echo "<script>alert('没有此订单信息');location.href='/index/car'</script>";die;
        }
        if($allprice<=0){
            echo "<script>alert('此订单无效');location.href='/index/car'</script>";die;
        }

    //        require_once app_path(dirname(__FILE__)).'/config.php';
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');

        require_once app_path('libs/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php');
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no =$id;


        //订单名称，必填
        $subject = 'IRON MAN';


        //付款金额，必填
        $total_amount = $allprice;


        //商品描述，可空
        $body = '';

        $config=config('alipay');

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($config);


        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->pagePay($payRequestBuilder,config('alipay.return_url'),config('alipay.notify_url'));
           dd($response);
        //输出表单
        var_dump($response);


    }
        

    //同步跳转
    public function treturn()
    {
        $arr=$_GET;
        $out_trade_no = trim($_GET['out_trade_no']);//订单号
        $total_amount = trim($_GET['total_amount']);//订单金额
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');
        $alipaySevice = new \AlipayTradeService(config('alipay'));
        $result = $alipaySevice->check($arr);


        $data=DB::table('order')->where(['order_on'=>$out_trade_no,'totalmoney'=>$total_amount])->first();
        if(!$data){
            echo "<script>alert('付款错误，此订单不存在');location.href='/index/car'</script>";
        }
        if(trim($_GET['seller_id']) !=config('alipay.seller_id') || trim($_GET['app_id']) != config('alipay.app_id')){
            echo "<script>alert('付款错误，商家或买家错误');location.href='/index/car'</script>";
        }
        Log::channel('alipay')->info("//验证成功<br />支付宝交易号：".$out_trade_no);
        return redirect('/index/car');
    }


    //异步跳转
    public function notify()
    {
        $config=config('alipay');
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');
        $arr=$_POST;
        $alipaySevice=new \AlipayTradeService(config($config));
        $alipaySevice->writeLog(var_export($_POST),true);

        $result=$alipaySevice->check($arr);
        if ($result) {
            //验证成功
            $res=json_encode($arr);

            //商户订单号
            $out_trade_no=$_POST['out_trade_no'];
            $total_amount=$_POST['total_amount'];
            $trade_no=$_POST['trade_no'];
            $app_id=$_POST['app_id'];
            $seller_id=$_POST['seller_id'];
            $order=$this->checkorder($out_trade_no);
            $user_id=$this->getUserId();
            if ($order==flase||$order['totalmoney'] != $total_amount) {
                Log::channel('alipay')->info('异步-错误：订单信息或订单价格有误'.$res.'/'.'支付宝交易号：'.$trade_no);
                echo "fail";die;
            }
            //判断商户id&&app_id
            if ($seller_id != $config['seller_id']&&$app_id != $config['app_id']) {
                Log::channel('alipay')->info('异步-错误：seller_id||app_id有误'.$res.'/'.'支付宝交易号：'.$trade_no);
                echo "fail";die;
            }

            OrderModel::where('order_on',$out_trade_no)->update(['pay_status'=>1,'order_status'=>5]);
            Log::channer('alipay')->info('异步-成功：支付宝交易号：'.$trade_no);
            cache(['order'.$user_id=>''],0);
            echo "success";//请不要修改或删除
        }else{
            //验证失败
            echo "fail";
        }

    }

    /** 获得登陆用户id */
    public function getUserId()
    {
        return $res = Auth::user()->id;
    }
}

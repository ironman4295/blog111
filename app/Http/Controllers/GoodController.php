<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\GoodsModel;
use App\Model\CateModel;
use App\Model\HistoryModel;
use App\Model\EnshrineModel;
use Illuminate\Support\Facades\Auth;


class GoodController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        if (request()->isMethod('post')&&request()->ajax()){

            $sousuo=request()->sousuo;
            $cate_id=request()->cate_id;
            $type=request()->type;
            $floor=request()->floor;

            $floor=($floor-1)*5;
            $GoodsModel=new GoodsModel();
            $where=[
                ['is_on_sale','=',1]
            ];

            if (!empty($sousuo)){
                $where[]=['good_name','like',"%{$sousuo}%"];
            }

            if($type==1){
                $ordername='out_num';$order='desc';
            }else if($type==2){
                $ordername='is_new';$order='desc';
            }else if($type==3){
                $ordername='good_onprice';$order='asc';
            }else{
                echo 0;die;
            }

            if (!empty($cate_id)){
                //id找子类
                $allcate_id=CateModel::where('is_show',1)->select('cate_id','parent_id')->get()->toArray();//全部id
                $sonid=getCateId($allcate_id,$cate_id);//筛选出所有子级id
                $whereIn=['cate_id',$sonid];
            }else{
                $goodInfo=$GoodsModel->where($where)->orderBy($ordername,$order)->skip($floor)->take(5)->get();
                return view('index.goodjiazai',['goodInfo'=>$goodInfo]);
            }

            $goodInfo=$GoodsModel->where($where)->whereIn('cate_id',$sonid)->orderBy($ordername,$order)->skip($floor)->take(5)->get();
            return view('index.goodjiazai',['goodInfo'=>$goodInfo]);
        }


        //首次加载-↓
        $catewhere=[
            'is_show'=>1,
            'is_nav_show'=>1,
        ];
        $cateIfo=CateModel::where($catewhere)->select('cate_id','cate_name')->get();
        //目标：商品数据
        $goodInfo=GoodsModel::where('is_on_sale',1)->orderBy('out_num','desc')->limit(5)->get();
        return view('index.prolist',['goodInfo'=>$goodInfo,'cateIfo'=>$cateIfo]);
    }

    public function lists($good_id=null)
    {
        if (empty($good_id)){
            echo "<script>alert('商品信息错误');location.href='/index/prolist';</script>";
        }
        //商品信息
        $goodInfo=GoodsModel::where('good_id',$good_id)->first();
        if (empty($goodInfo)){
            echo "<script>alert('商品信息错误');location.href='/index/prolist';</script>";
        }

        //添加浏览历史
        if ($this->is_login()) {
            //添加浏览历史
            $this->historyDB($good_id);

            //查看是否收藏
            $user_id=$this->getUserId();
            $where = [
                'user_id' => $user_id,
                'good_id' => $good_id
            ];
            if (!$res = cache('enshrine'.$user_id.$good_id)) {
                $res = EnshrineModel::where($where)->first();
                cache(['enshrine'.$user_id.$good_id => $res], 60 * 10);
            }

            if ($res) {
                $enshrine = true;
            } else {
                $enshrine = false;
            }
        } else {
            $enshrine = false;
        }
        return view('index.proinfo', ['addcart' => 1, 'goodInfo' => $goodInfo, 'total_money' => $goodInfo['good_onprice'], 'enshrine' => $enshrine]);

    }

    //计算商品总价
    public function gettotalmoney()
    {
        if (request()->isMethod('post') && request()->ajax()) {
            $good_id = request()->good_id;
            $good_buynum = request()->good_buynum;
            $good_onprice = GoodsModel::where('good_id', $good_id)->value('good_onprice');
            if ($good_onprice) {
                return $total = $good_onprice * $good_buynum;
            } else {
                return false;
            }

        }
    }

    //写入浏览历史(数据库)
    public function historyDB($good_id){
        $user_id=$this->getUserId();
        $data=[
                'user_id'=>$user_id,
                'good_id'=>$good_id,
                'look_time'=>time(),
        ];
        HistoryModel::insert($data);
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


?>
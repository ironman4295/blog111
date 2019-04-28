<?php

namespace App\Http\Controllers;
use App\Model\GoodsModel;
use App\Model\VisitCountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index(){
        //推荐处理
        if (\request()->isMethod('post')&& \request()->ajax()){
            $type=\request()->type;
            $where=[['is_on_sale','=',1]];
            if ($type==1){
                $where[]=['is_best','=',1];
            }else if($type==2){
                $where[]=['is_new','=',1];
            }else if($type==3){
                $where[]=['is_hot','=',1];
            }else if($type==4){
                $good=GoodsModel::where(['is_on_sale'=>1])->limit(6)->orderBy('good_onprice','asc')->get();
            }
            if ($type!=4){
                $good=GoodsModel::where($where)->limit(6)->orderBy('out_num','desc')->get();
            }
            return view('index.indexdiv',['good'=>$good]);
        }
        //验证是否登陆
        $user=$this->is_login();
        //商品数目
        $goodallnum=GoodsModel::where(['is_on_sale'=>1])->count();
        //总交易销量
        $alloutnum=GoodsModel::sum('out_num');
        //总访问次数
        $visitcount=VisitCountModel::sum('visit_num');
        //轮播图
        $slideshow=GoodsModel::where(['is_on_sale'=>1,'is_best'=>1])->limit(6)->orderBy('good_id','desc')->get(['good_id','good_img']);
        //判断查询商品（limit 8）::默认精品
            //大图商品
        $good=GoodsModel::where(['is_on_sale'=>1,'is_best'=>1])->limit(6)->orderBy('good_id','desc')->get();
            //小图商品::销量排序
        $good2=GoodsModel::where(['is_on_sale'=>1])->limit(4)->orderBy('out_num','desc')->get();
        return view('index.index',[
            'user'=>$user,
            'good'=>$good,
            'good2'=>$good2,
            'goodallnum'=>$goodallnum,
            'alloutnum'=>$alloutnum,
            'visitcount'=>$visitcount,
            'slideshow'=>$slideshow
        ]);
    }

    /** 验证是否登陆 */
    public function is_login()
    {
        return $res = Auth::user();
    }

}

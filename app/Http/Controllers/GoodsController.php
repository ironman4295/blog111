<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GoodsBlogPost;
use Illuminate\Foundation\Http\FormRequest;
use \DB;

class GoodsController extends Controller
{
	//展示
    public function index()
    {
    	$query=request()->all();

    	$where=[
    		['show','=',1]
    	];
    	$uname=$query['uname']??'';
		if ($uname) {
			$where[]=['uname','like',"%$uname%"];
		}
		$pageSize=config('pageSize',5);
    	$data=DB::table('good')->where($where)->orderBy('id','desc')->paginate($pageSize);
    	return view('goods/first',compact('data','uname','query'));
    }

    //添加
    public function add()
    {

    	return view('goods/add');
    }

    public function store(GoodsBlogPost $request)
    {
    	
    	$post=$request->all();
    	

    	unset($post['_token']);
    	if ($request->hasFile('logo')) {
    		$post['logo']=$this->upload($request,'logo');
    	}

    	$res=DB::table('good')->insert($post);

    	return redirect('goods/first')->with('msg','添加成功');
    }

    //删除
    public function del($id)
    {
    	$res=DB::table('good')->delete($id);
    	return redirect('goods/first');
    }

    //修改
    public function edit($id)
    {
    	if ($id) {
    		$data=DB::table('good')->where('id',$id)->first();
    		if (!$data) {
    			return redirect('goods/first');
    		}else{
    			return view('goods/edit',['data'=>$data]);
    		}
    	}
    }
    public function update(GoodsBlogPost $request,$id)
    {
    	$post=$request->all();
    	unset($post['_token']);
    	if ($request->hasFile('edit_logo')) {
    		$post['logo']=$this->upload($request,'edit_logo');
    		unset($post['edit_logo']);
    	}

    	$res=DB::table('good')->where('id',$id)->update($post);

    	return redirect('/goods/first')->with('msg','修改成功');
    }

    //验证唯一性
    public function checkName()
    {
        $id=request()->id;
    	$uname=request()->uname;

    	if (!$uname) {
    		return ['code'=>0,'msg'=>'请填写网站名称'];
    	}
        $where=[
            ['id','!=',$id],
            ['uname','=',$uname]
        ];

    	$count=DB::table('good')->where($where)->count();

    	if ($count) {
    		return ['code'=>0,'msg'=>'网站名称已存在'];
    	}else{
    		return ['code'=>1,'msg'=>'网站名称名可用'];
    	}
    }


    //文件上传
    public function upload(Request $request,$name)
    {
    	if ($request->file($name)->isValid()) {
			$photo = $request->file($name);
			
			// $store_result = $photo->store('img');
			$extension=$request->$name->extension();
			$store_result = $photo->storeAs(date('Ymd'),date('YmdHis').rand(100,999).'.'.$extension);
			
			return $store_result;
		}
		exit('未获取到上传文件或上传过程出错');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\StoreBlogPost;
use App\Model\Users;
// use Illuminate\Support\Facades\DB;
use \DB;

class UserController extends Controller
{
	public function index()
	{
		$query=request()->all();
		// dd($query);

		$where=[];
		$username=$query['username']??'';
		if ($username) {
			$where[]=['username','like',"%$username%"];
		}
		$age=$query['age']??'';
		if ($age) {
			$where[]=['age','like',"%$age%"];
		}

		$pageSize=config('pageSize',5);
		$data=DB::table('user')->where($where)->orderBy('id','desc')->paginate($pageSize);

		return view('users/first',compact('data','username','age'));
	}

    public function store(StoreBlogPost $request)
    {
    	//打印的两种方式
    	// dd($request->input());
    	// dump($request->input());
    	// 接收所有的值
    	// $post=$request->input();
    	// $post=$request->all();
    	// $post=request()->input();
    	// $post=request()->all();
    	
    	// 接受所有post 值
    	$post=$request->only(['username','age']);
    	if ($request->hasFile('head')) {
    		$post['head']=$this->upload($request,'head');
    	}
    	// dd($request->head);
    	// 接受单个值
    	// $post=$request->post('username');
    	// $post=$request->username;
    	// $post=$request->input('username');
    	
    	//原生sql
    	// $res=Db::insert("insert into user (username,age) value(?,?)",["$post[username]",$post['age']]);
    	

    	// // 表单验证  第一种
    	// $vali_date=$request->validate([
    	// 	'username'=>'required|unique:user|max:30|min:3',
    	// 	'age'=>'required|integer'
    	// ],[
    	// 	'username.required'=>'用户名不能为空',
    	// 	'age.required'=>'年龄不能为空',
    	// 	'age.integer'=>'年龄必须为纯数字',
    	// 	'username.unique'=>'用户名存在',
    	// 	'username.max'=>'用户名不能超过30字符',
    	// 	'username.min'=>'用户名不得少于3字符',
    	// ]);

    	//表单验证  第二种
    	

    	//验证表单  第三种
   //  	$validator = Validator::make($request->all(), [
			// 'username'=>'required|unique:user|max:30|min:3',
   //          'age'=>'required|integer'
		 // ],[
		 // 	'username.required'=>'用户名不能为空',
   //          'age.required'=>'年龄不能为空',
   //          'age.integer'=>'年龄必须为纯数字',
   //          'username.unique'=>'用户名存在',
   //          'username.max'=>'用户名不能超过30字符',
   //          'username.min'=>'用户名不得少于3字符',
		 // ]);
		 // if ($validator->fails()) {
			//  return redirect('user/add')
			// ->withErrors($validator)
			// ->withInput();
		 // }


    	$res=DB::table('user')->insertGetId($post);
    	
    	if ($res) {
    		return redirect('/user/first')->with('msg','添加成功');
    	}
    	
    }

    //编辑
    public function edit($id)
    {
    	if ($id) {
    		// 原生
    		// $data=DB::select('select * from user where id=:id',['id'=>$id]);
    		//查询构造器
    		// $data=Db::table('user')->where('id',$id)->first();
    		// ORM
    		$user_model=new Users;
    		$data = $user_model->find($id);

    		if (!$data) {
    			return redirect('/user/first');
    		}else{
    			return view('users.edit',['data'=>$data]);
    		}
    	}
    }

    // 修改
    public function update(StoreBlogPost $request,$id)
    {
    	// $post=$request->except('_token');
    	$post=$request->only(['username','age']);
    	
    	if ($request->hasFile('edit_head')) {
    		// unlink();
    		$post['head']=$this->update($request,'edit_head');
    		unset($post['edit_head']);
    	}
    	
    	//原生sql 原生
    	// $res=DB::update('update user set username=:username,age=:age,head=:head where id='.$id,['username'=>$post['username'],'age'=>$post['age'],'head'=>$post['head']]);

    	//查询构造器
    	$res=DB::table('user')->where('id',$id)->update($post);


    	// ORM
    	// $user=new Users;
    	// $user->username=$post['username'];
    	// $user->age=$post['age'];
    	// $user->head=$post['head'];
    	// $res=$user->save();
    	
    	dd($res);
    }


    //删除
    public function del($id)
    {
    	if ($id) {
    		//原生
    		// $res=DB::delete('delete from user where id='.$id);
    		
    		//查询构造器
    		// $res=DB::table('user')->delete($id);
    		
    		//ORM
    		$res=Users::where('id',$id)->delete();
    		if ($res) {
    			return redirect('user/first');
    		}
    	}
    }

    public function checkName()
    {
    	$username=request()->username;

    	if (!$username) {
    		return ['code'=>0,'msg'=>'请填写用户名'];
    	}

    	$count=DB::table('user')->where('username',$username)->count();

    	if ($count) {
    		return ['code'=>0,'msg'=>'用户名已存在'];
    	}else{
    		return ['code'=>1,'msg'=>'用户名可用'];
    	}
    }


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

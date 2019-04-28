<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//闭包路由  返回视图
// Route::get('/', function () {
//     // return view('test',['name'=>'dadada']);
//     return view('welcome');
// });
// Route::view('/','test',['name'=>'lisdd']);

//闭包路由  返回值
// Route::get('/', function () {
//     return 123;
// });
// Route::get('/index','IndexController@index');

// Route::get('/form',function(){
// 	// return '<from action="/do" method="post">'.csrf_field().'<input type="text" name="name"><button>提交</button></from>';
// 	return '<from action="/token" method="post"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="text" name="name"><button>提交</button></from>';
// });

//支持多种路由
// Route::match(['post'],'/do','IndexController@doadd');
// Route::any('/do','IndexController@doadd');

//闭包函数传参  必填
//Route::get('/goods/{id}',function($id){
//	echo "ID is:".$id;
//});

//可选传参
//Route::get('/goods/{id}','IndexController@goods');
//Route::get('/goods/{id?}','IndexController@goods')->where('id','\d+');

// Route::prefix('goods')->group(function(){
// 	Route::get('first','IndexController@first');
// 	Route::get('update','IndexController@update');
// 	Route::get('del','IndexController@del');
// 	Route::get('add','IndexController@add');
// });

// Route::get('/aa',function(){
// 	return redirect('/form');
// });

// Route::post('/token','UserController@store')->middleware('checktoken');

// Route::prefix('users')->group(function(){
// 	Route::get('add',function(){
// 		return view('users/add');
// 	});
// 	Route::post('doadd','UserController@store')->name('adduser');
// 	Route::get('first','UserController@index');
// 	Route::post('checkname','UserController@checkName');
// 	Route::post('update/{id}','UserController@update');
// 	Route::get('edit/{id}','UserController@edit')->name('edituser');
// 	Route::get('del/{id}','UserController@del')->name('deluser');
// });

// Route::get('/test',function(){
// 	return response('Hello',200)->cookie('class','1810A',3);
// });

// Route::get('/get',function(){
// 	echo request()->cookie('class');
// });


// Route::prefix('goods')->group(function(){
// 	Route::get('add','GoodsController@add');
// 	Route::get('first','GoodsController@index');
// 	Route::post('doadd','GoodsController@store')->name('addgoods');
// 	Route::get('edit/{id}','GoodsController@edit')->name('editgoods');
// 	Route::get('del/{id}','GoodsController@del')->name('delgoods');
// 	Route::post('update/{id}','GoodsController@update');
// 	Route::post('checkname','GoodsController@checkName');
// 	Route::post('send','GoodsController@send');
// });

// Route::get('/text',function(){
//     request()->session()->put('id',123);
// });

// Route::get('/login',function(){
// 	return view('goods.login');
// });



// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/','IndexController@index');
Route::prefix('index')->group(function(){
	Route::any('index','IndexController@index');

	//用户路由
	Route::any('user','UserController@index');
	//发送信息路由
	Route::any('send','SendController@index');

	//验证用户路由
	Route::any('login','LoginController@index')->name('login');
	Route::any('reg','LoginController@add');//注册
	Route::any('islogin','LoginController@islogin');//验证是否登陆
	Route::any('unique','LoginController@unique');//注册唯一验证
	Route::any('doreg','LoginController@doadd');//添加
	Route::any('logout','LoginController@logout');//退出

	//商品路由
	Route::any('prolist','GoodController@index');//全部商品
	Route::any('proinfo/{good_id}','GoodController@lists');//商品详情
	Route::post('gettotalmoney','GoodController@gettotalmoney');//计算商品总价
	Route::any('car','GoodCarController@index');//购物车
	Route::any('addcar','GoodCarController@addcar');//购物车
    Route::post('savecar','GoodCarController@savecar');//更新购买数量
    Route::post('gettotal','GoodCarController@gettotal');//获取小计/总价
    Route::post('cartdel','GoodCarController@cartdel');//删除商品（软）

	//订单路由
    Route::get('order/{good_id?}/{buy_num?}','OrderController@add');
    Route::get('orderlist','OrderController@lists');
    Route::post('ordermoney','OrderController@ordermoney');
    Route::post('orderdoadd','OrderController@orderdoadd');
    Route::post('orderdel','OrderController@orderdel');
    Route::post('orderstatus','OrderController@orderstatus');
    Route::any('success/{order_id?}','OrderController@success');

    //收货地址
    Route::any('address','AddressController@index');//展示页
    Route::any('add_address','AddressController@add_address');//添加/修改
    Route::post('doadd_address','AddressController@doadd_address');//验证添加
    Route::post('del_address','AddressController@del_address');//删除
    Route::post('is_default','AddressController@is_default');//默认

    //收藏路由
    Route::any('enshrine','EnshrineController@index');//展示
    Route::any('addenshrine','EnshrineController@add');//添加or删除
});

//支付
Route::get('pay/{order_on}','PayController@alipay');//支付页
Route::get('treturn','PayController@treturn');//支付同步通知路由
Route::get('notify','PayController@notify');//支付异步通知路由
//发送信息路由
Route::prefix('send')->group(function(){
    Route::any('reg','SendController@reg');
    Route::any('send','SendController@index');
    
});

//练习的
Route::prefix('iron')->group(function(){
	Route::any('first','IronController@first');

	Route::any('login','IronController@login');//登陆
	Route::any('reg','IronController@reg');//注册
	Route::any('doreg','IronController@doreg');//添加
	Route::any('uniques','IronController@uniques');//注册唯一验证
	Route::any('send','IronController@send');
});


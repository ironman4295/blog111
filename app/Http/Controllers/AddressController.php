<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\AddressModel;
use App\Model\AreaModel;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    //首页展示
    public function index()
    {
        $user_id=$this->getUserId();
        $where = [
            'user_id' => $user_id,
            'is_del' => 1
        ];

        $address = AddressModel::where($where)->orderBy('default', 'desc')->get()->toArray();
        foreach ($address as $k => $v) {
            $address[$k]['province'] = AreaModel::where('id', $v['province'])->value('name');
            $address[$k]['city'] = AreaModel::where('id', $v['city'])->value('name');
            $address[$k]['area'] = AreaModel::where('id', $v['area'])->value('name');
        }

        return view('index.address', ['address' => $address]);
    }

    //添加页面
    public function add_address()
    {
        if (Request()->Ajax() && Request()->isMethod('post')) {
            $id = request()->id;
            if (!empty($id)) {
                $area = $this->getArea($id);
                echo json_encode($area);
                die;
            }
        }
        $area = $this->getArea(0);
        return view('index.add_address', ['area' => $area]);
    }

    //确认添加/修改
    public function doadd_address()
    {
        if (Request()->Ajax() && Request()->isMethod('post')) {
            $data = request()->post();
            if (!preg_match("/^1[34578]\d{9}$/", $data['tel'])) {
                echo json_encode(['code' => 0, 'msg' => '手机号错误']);
                die;
            }
            $data['user_id'] = $this->getUserId();

            if (empty($data['id'])) {
                $data['add_time'] = time();
                $user_id=$this->getUserId();
                $where = [
                    'user_id' =>$user_id,
                    'is_del' => 1,
                    'default' => 1
                ];
                $address = AddressModel::where($where)->first();
                if ($address) {
                    $res = AddressModel::create($data);
                    if ($res) {
                        echo json_encode(['code' => 1, 'msg' => '添加成功']);die;
                    } else {
                        echo json_encode(['code' => 0, 'msg' => '添加失败']);die;
                    }
                } else {
                    $data['default'] = 1;
                    $res = AddressModel::create($data);
                    if ($res) {
                        echo json_encode(['code' => 1, 'msg' => '添加成功']);die;
                    } else {
                        echo json_encode(['code' => 0, 'msg' => '添加失败']);die;
                    }
                }
            } else {
                $data['save_time'] = time();
                $res = AddressModel::where('id', $data['id'])->update($data);
                if ($res) {
                        echo json_encode(['code' => 1, 'msg' => '修改成功']);die;
                    } else {
                        echo json_encode(['code' => 0, 'msg' => '修改失败']);die;
                    }
            }

        } else {
            echo json_encode(['code' => 0, 'msg' => '非法数据']);die;
        }
    }

    //删除
    public function del_address()
    {
        if (Request()->isMethod('post') && Request()->Ajax()) {
            $id = request()->id;
            $user_id=$this->getUserId();
            $where = [
                'user_id' => $user_id,
                'id' => $id
            ];
            $res = AddressModel::where($where)->update(['is_del' => 2]);
            if ($res) {
                echo json_encode(['code' => 1, 'msg' => '删除成功']);die;
            } else {
                echo json_encode(['code' => 0, 'msg' => '删除失败']);die;
            }
        } else {
            echo json_encode(['code' => 0, 'msg' => '非法数据']);die;
        }
    }

    //获取地区
    public function getArea($id)
    {

            $res = AreaModel::where('pid', $id)->get()->toArray();
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    //设置默认
    public function is_default()
    {
        if (Request()->isMethod('post') && Request()->Ajax()) {
            $id = request()->id;
            $user_id=$this->getUserId();
            $where = [
                'user_id' => $user_id,
                'id' => $id,
                'is_del' => 1
            ];
            $res1 = AddressModel::where('user_id', $user_id)->update(['default' => 0]);
            $res = AddressModel::where($where)->update(['default' => 1]);
            if ($res && $res1) {
                echo json_encode(['code' => 1, 'msg' => '设置成功']);die;
            } else {
                echo json_encode(['code' => 0, 'msg' => '设置失败']);die;
            }
        } else {
            echo json_encode(['code' => 0, 'msg' => '非法数据']);die;
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

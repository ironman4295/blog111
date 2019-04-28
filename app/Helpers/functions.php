<?php
/**
 * Created by PhpStorm.
 * User: moTzxx
 * Date: 2019/4/8
 * Time: 14:50
 */
/**
 * 公用的方法  返回json数据，进行信息的提示
 * @param $status 状态
 * @param string $message 提示信息
 * @param array $data 返回数据
 */

/**图片上传*/
function uploads($img)
{
    if (request()->hasFile($img) && request()->file($img)->isValid()) {
        $photo = request()->file($img);
//            $extension = $photo->extension();//后缀
        $store_result = $photo->store(date('Ymd'));//路径
//            $output = [
//                'extension' => $extension,
//                'store_result' => $store_result
//            ];
        return (string)$store_result;
    }
    return null;
}

/**成功---失败*/
function ok($res)
{
    echo json_encode(['code' => 1, 'msg' => $res]);
}

function no($res)
{
    echo json_encode(['code' => 0, 'msg' => $res]);die;
}

/** 获取父级id下的所有子级并同级排序  无限极*/
function createTree($data, $field = '', $parent_id = 0, $level = 1)
{
    static $result = [];
    if ($data) {
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $v['level'] = $level;
                $result[] = $v;
                unset($data[$k]);
                createTree($data, $field, $v[$field], $level + 1);
            }
        }
        return $result;
    }
}

/** 获取父级id下的所有子级  无限极*/
function createSonTree($data, $field = '', $parent_id = 0)
{
    $result = [];
    if ($data) {
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $result[$k] = $v;
                unset($data[$k]);
                $result[$k]['son'] = createSonTree($data, $field, $v[$field]);
            }
        }
        return $result;
    }
}

/** 获取给定分类下的所有子id  无限极*/
function getCateId($data, $parent_id = '')
{
    static $id = [];
    foreach ($data as $k => $v) {
        if ($v['parent_id'] == $parent_id) {
            $id[] = $v['cate_id'];
            unset($data[$k]);
            getCateId($data, $v['cate_id']);
        }
    }
    return $id;
}

/** 验证是否登陆 */
function is_login()
{
    return $res = Illuminate\Support\Facades\Auth::user();
}

/** 获得登陆用户id */
function getUserId()
{
    return $res = Illuminate\Support\Facades\Auth::user()->id;
}

/**加密base64后的被Serializer的数据*/
function savebase64($data){
	return base64_encode(serialize($data));
}
/**解密base64后的被Serializer的数据*/
function freebase64($str){
	return unserialize(base64_decode($str));
}

/** 短信发送 */
function sendSms($phone,$code,$templateId='TP1711063'){
    $host = "http://dingxin.market.alicloudapi.com";
    $path = "/dx/sendSms";
    $method = "POST";
    $appcode = "e36fd5ed3abe4199b11990c2526c9aff";
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    $querys = "mobile={$phone}&param=code%3A{$code}&tpl_id={$templateId}";
    $bodys = "";
    $url = $host . $path . "?" . $querys;

     $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    if (1 == strpos("$".$host, "https://"))
    {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    return curl_exec($curl);
}

/** 邮件发送 */
function sendMail($address,$subject,$view,$arrdata){
    //$arrdata为数组
    return \Mail::send($view,$arrdata,
        function($message)use($address,$subject){
          $to = $address;//收件人
          $message ->to($to)->subject($subject);//主题
     });

}

//获取执行sql语句（来源https://www.cnblogs.com/grimm/p/8548691.html）
function getLastSql() {
    DB::listen(function ($sql) {
        foreach ($sql->bindings as $i => $binding) {
            if ($binding instanceof \DateTime) {
                $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
            } else {
                if (is_string($binding)) {
                    $sql->bindings[$i] = "'$binding'";
                }
            }
        }
        $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
        $query = vsprintf($query, $sql->bindings);
        print_r($query);
        echo '<br />';
    });
}
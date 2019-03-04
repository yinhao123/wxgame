<?php
/**
 * Created by PhpStorm.
 * User: yinhao
 * Date: 4/3/2019
 * Time: 下午 11:19
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;

class Game extends Controller
{
    /**
     * 用户列表
     */
    public function userlist()
    {
        return $this->fetch();
    }
    /**
     * 游戏设置
     */
    public function gameset()
    {
        return $this->fetch();
    }
    /**
     * 返回用户列表json
     */
    public function userjson()
    {
        $list = Db::name('GameUser')->select();
        $count = Db::name('GameUser')->count();
        $code = 0;
        $message = "";
        $data['code'] = $code;
        $data['msg']= $message;
        $data['count'] = $count;
        $data['data'] = $list;

        return json($data);
    }
    /**
     * 程序流水
     */
    public function liushui()
    {


    }
}
<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

namespace app\index\controller;

use think\Controller;
use think\facade\Request;

/**
 * 应用入口控制器
 * @author Anyon <zoujingli@qq.com>
 */
class Index extends Controller
{

    public function admin()
    {
        $this->redirect('@admin/login');
    }

    /**
     * 网站首页
     * @return page
     */
    public function index()
    {
     return   $this->fetch();
    }

    /**
     * 分享二维码
     * @return 返回一个分享二维码的图片
     */
    public function fenxiang()
    {

        return $this->fetch();
    }

    public function orderlog()
    {
        return $this->fetch();

    }
    public function mychid()
    {
        return $this->fetch();
    }
    public function tuiguangguanli()
    {
        return $this->fetch();
    }
    public function paihangbang()
    {
        return $this->fetch();
    }
    public function tixian()
    {
        return $this->fetch();
    }
    public function chongzhi()
    {
        return $this->fetch();
    }
    public function weihaodingdan()
    {
        return $this->fetch();
    }
    public function chenxizhifu()
    {
        return $this->fetch();
    }
    public function shoukuanla()
    {
        return $this->fetch();
    }
}

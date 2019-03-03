<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Exception;
use think\facade\Request;
use think\facade\Session;

/**
 * Created by PhpStorm.
 * User: yinhao
 * Date: 3/3/2019
 * Time: 上午 11:28
 */

class Index extends Controller
{

    /**
     * 返回总排行数据
     * @return
     *  "ret":0,
    "data":{
    "data":[
    {
    "price":"1455.15",
    "id":"3",
    "name":"朵儿",
    "jishu":1
    },
    {
    "price":"980.70",
    "id":"4",
    "name":"迷人飞 偷打sm",
    "jishu":2
    },
    {
    "price":"961.75",
    "id":"2",
    "name":"子辰",
    "jishu":3
    },
    {
    "price":"546.45",
    "id":"359",
    "name":"木兮",
    "jishu":4
    },
    {
    "price":"449.95",
    "id":"1",
    "name":"嘟嘟",
    "jishu":5
    },
    {
    "price":"449.70",
    "id":"294",
    "name":"逸轩qq 46078509",
    "jishu":6
    },
    {
    "price":"399.70",
    "id":"12",
    "name":"无名????(回归互换???? ???? )",
    "jishu":7
    },
    {
    "price":"395.00",
    "id":"8",
    "name":"王牌推手（重阳）",
    "jishu":8
    },
    {
    "price":"370.00",
    "id":"40",
    "name":"boss",
    "jishu":9
    },
    {
    "price":"363.00",
    "id":"28",
    "name":"阿明 青果副代",
    "jishu":10
    }
    ]
    }
    }
     */
    public function zongpaihang()
    {



    }
    /**
     * 首页提交数字猜大小，猜单双 ，核心算法
     * @param total_hand 购买的数量
     * @param number 购买的种类
     * @return
     * 余额不足的情况下
     * {
    "code":1,
    "success":false,
    "msg":
    }
     * 购买成功后获胜的情况下
     *{
    "buy_money":19,
    "status":3,
    "my_money":"20.67",
    "success":true,
    "last_number":9,
    "number":"0",
    "left_cash_time":0,
    "is_win":1,
    "tail":9,
    "tail2":"9",
    "tail3":"3",
    "first_no":"1503275031201903033291722399",
    "win_money":19,
    "wx_order_number":"150327503120190303329172239",
    "total_hand":1
    }
     * {
    "buy_money":19,
    "status":3,
    "my_money":"28.17",
    "success":true,
    "last_number":6,
    "number":"0",
    "left_cash_time":0,
    "is_win":1,
    "tail":6,
    "tail2":"0",
    "tail3":"5",
    "first_no":"1503275031201903043294497506",
    "win_money":19,
    "wx_order_number":"150327503120190304329449750",
    "total_hand":1
    }
     *
     */
    public function buyNumber()
    {
        $number = Request::post('number');

        $total_hand = Request::post('total_hand');

        $openid = Session::get('openid');
        // 首先判断一下用户的钱够不够。
        $user = Db::name('GameUser')->where('openid','=',$openid)->find();
        if( $user['yue']<$total_hand){
            $data['code'] = 1;
            $data["success"] = false;
            $data["msg"] = "您的余额不足撸!1手,前往充值";
            return json($data);
        };


        //1.先获取用户的请求数据，得到用户的买大买小
        //2.获取随机成成的24位数，然后判断用户买的大小，如果买大则生成两个两位数
        // 3. 用户的 大 0 小 1 单 2 双 3

        $before26 = $this->get_random(13);

        $before27 = $this->get_random2();

        // 三种算法，通过后台控制修改
        $lastnumber = $this->bishu($number);
        $lastnumber = $this->biying($number);
        $lastnumber = $this->suiji();



        $ordernumber = $before26.''.$before27.''.$lastnumber;




        echo $ordernumber;
        echo '尾号是'.$lastnumber;



    }


    /**
     * 算法 必输
     */
    private function bishu($number)
    {
        // 必输
        switch ($number){

            case 0: // 大
                echo "选大";
                $lastnumber = mt_rand(0,4);
                //  return $lastnumber;
                break;
            case 1: // 小
                echo "选小";
                $lastnumber = mt_rand(5,9);
                //   return $lastnumber;
                break;
            case 2: // 单
                echo "选单";
                $lastnumber =  $this->get_shuang();
                //return $lastnumber;
                break;
            case 3: // 双
                $lastnumber = $this->get_dan();
                //   return $lastnumber;
                break;
            case 4: // 大单
                $number1 = mt_rand(0,4);
                $number2 =  $this->get_shuang();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                break;
            case 5: // 小单
                $number1 = mt_rand(5,9);
                $number2 =  $this->get_shuang();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                break;
            case 6: // 大双
                $number1 = mt_rand(0,4);
                $number2 =  $this->get_dan();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                break;
            case 7:    // 小双
                $number1 = mt_rand(5,9);
                $number2 =  $this->get_dan();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                break;
            default :
                $this->error('系统出错，稍后再试');
                return "系统出错，稍后再试";
                break;
        }

    }
    /**
     * 算法 必赢
     */
    private function biying($number)
    {
        // 必赢
        switch ($number){

            case 0: // 大
                echo "选大";
                $lastnumber = mt_rand(5,9);
                //  return $lastnumber;
                break;
            case 1: // 小
                echo "选小";
                $lastnumber = mt_rand(0,4);
                //   return $lastnumber;
                break;
            case 2: // 单
                echo "选单";
                $lastnumber =  $this->get_dan();
                //return $lastnumber;
                break;
            case 3: // 双
                $lastnumber = $this->get_shuang();
                //   return $lastnumber;
                break;
            case 4: // 大单
                $number1 = mt_rand(5,9);
                $number2 =  $this->get_dan();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                break;
            case 5: // 小单
                $number1 = mt_rand(0,4);
                $number2 =  $this->get_dan();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                break;
            case 6: // 大双
                $number1 = mt_rand(5,9);
                $number2 =  $this->get_shuang();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                break;
            case 7:    // 小双
                $number1 = mt_rand(0,4);
                $number2 =  $this->get_shuang();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                break;
            default :
                $this->error('系统出错，稍后再试');
                return "系统出错，稍后再试";
                break;
        }
    }

    /**
     * 算法 随机
     */
    private function suiji(){

      mt_rand(0,9);

    }
    /**
     * 生成一个单数
     */
    public function get_dan()
    {

        $base = [1,3,5,7,9];
        $num =mt_rand(0,4);
       return  $base[$num];


    }

    /**
     * 生成一个双数
     */
    public function get_shuang()
    {
        $base = [0,2,4,6,8];
        $num =mt_rand(0,4);
        return  $base[$num];


    }
   private function get_random($len=3){
        //range 是将10到99列成一个数组
        $numbers = range (10,99);
        //shuffle 将数组顺序随即打乱
        shuffle ($numbers);
        //取值起始位置随机
        $start = mt_rand(1,10);
        //取从指定定位置开始的若干数
        $result = array_slice($numbers,$start,$len);
        $random = "";
        for ($i=0;$i<$len;$i++){
            $random = $random.$result[$i];
        }
        return $random;
    }


//随机数
   private function get_random2($length = 1) {
        $min = pow(10 , ($length - 1));
        $max = pow(10, $length) - 1;
        return mt_rand($min, $max);
    }






    /**
     * 提现功能
     * @param money 钱金额
     * @return {
    "o":"zuidi",
    "msg":"余额不足10"
    }
     */
    public function ceshiduihuan()
    {

    }
    /**
     * 首页抢红包
     * @return
     * {
    "code":0,
    "msg":"今日已领取,明天再来"
    }
     */
    public function qianghongbao()
    {
        // 随机红包，红包金额限制在多少钱以下，每个用户每天只能抢一次
    }

    /**
     * 中奖人员 √
     * @return
     * [
    {
    "letter_id":"挽歌朽年",
    "head_img":"http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTK6uMhKkDoyJ48llIIqpwKPDUm815EVNTeHDq3kicYPbIVYD0j22viacHkAXmyrChvwsosfnSKrHQBA/132",
    "win_money":"19"
    },]
     */
    public function zhongjiaorenyuan()
    {
        // 返回json数组
       $log =  Db::name('GameMoneylog')->where('kind','=','1')->order('id','desc')->limit(20)->select();
        //$res = Db::name('GameUser')->where('userid','=',$userid)->find();

        foreach ($log as $key => $value)
        {
            $userid = $value['userid'];
            $res = Db::name('GameUser')->where('id','=',$userid)->find();

            $log[$key]['letter_id'] = $res['name'];
            $log[$key]['head_img'] = $res['avatar'];
            $log[$key]['win_money'] = $log[$key]['money'];
            unset($log[$key]['id']);
            unset($log[$key]['userid']);
            unset( $log[$key]['kind']);
            unset( $log[$key]['serialnumber']);
            unset( $log[$key]['status']);
            unset( $log[$key]['money']);
        }

        return json($log);

    }




}
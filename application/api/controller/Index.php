<?php
namespace app\api\controller;
use BaconQrCode\Common\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
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
     * 测试短网址
     */
    public function duanwangzhi()
    {
        $url = Request::url(true);

        $dwzUrl = dwz($url);
        $dwz =  json_decode($dwzUrl);
         return $dwz[0]->url_short;
    }
    /**
     * 生成二维码推广图片
     */
    public function share()
    {
        // Create a basic QR code
        $qrCode = new QrCode('草他妈！');
        $qrCode->setSize(300);

// Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');

        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);



        $qrCode->setValidateResult(false);


// Directly output the QR code
        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
        $user = Db::name('GameUser')->where('id','=',1)->find();
// Save it to a file
//       $path = '/static/uploads/qrcode/'.$user['id'].'/)';
//        if(!is_readable($path))
//        {
//            is_file($path) or mkdir($path,0700,true);
//        }
        $qrCode->writeFile('./static/uploads/qrcode/'.$user['id'].'/qrcode.png');

// Create a response object
     //   $response = new QrCodeResponse($qrCode);

    }
    /**
     *    生成宣传二维码图片
    */
    public function shareimage()
    {
        $image = \think\Image::open('./static/uploads/shareimage/ewmt.jpg');

        $bigImgPath = "./static/uploads/shareimage/ewmt.jpg";
        $qCodePath = "./static/uploads/qrcode/1/qrcode.png";

        $bigImg = imagecreatefromstring(file_get_contents($bigImgPath));
        $qCodeImg = imagecreatefromstring(file_get_contents($qCodePath));

        list($qCodeWidth, $qCodeHight, $qCodeType) = getimagesize($qCodePath);

        imagecopymerge($bigImg, $qCodeImg, 210, 730, 0, 0, $qCodeWidth, $qCodeHight, 100);

        list($bigWidth, $bigHight, $bigType) = getimagesize($bigImgPath);

        imagejpeg($bigImg,'./static/uploads/share/ok.jpg');


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
        Session::set('openid','fsahtgrshjgjg6zg2fdasg');

        $number = Request::post('number');
        $data = array();
        $total_hand = Request::post('total_hand');

        $openid = Session::get('openid');
        // 首先判断一下用户的钱够不够。
        $user = Db::name('GameUser')->where('id','=',1)->find();
        if( $user['yue']<$total_hand){
            $data['code'] = 1;
            $data["success"] = false;
            $data["msg"] = "您的余额不足撸!1手,前往充值";
            return json($data);
        };
        $data['success'] = true;
        $data['total_hand'] = $total_hand;


        // 生成前27位随机数
        $before24 = $this->get_random(12);
        $before25 = $this->get_random2();
        $before26 = $this->get_random2();
        $before27 = $this->get_random2();



        $max = $total_hand * 30;

        $suijishu = mt_rand(0,$max);

        $peilv = $this->peilv($number);



        if ($suijishu<5){

            // 赢了

            $lastnumber = $this->biying($number);

            $data['buy_money'] = $total_hand*$peilv*10;
            $data['status'] = 3;// 暂时没懂什么意思
            $data['my_money'] = $user['yue']+$data['buy_money'];
            $data['last_number'] = $lastnumber;
            $data['is_win'] = 1;
            $data['number'] = $number;
            $data['win_money'] = $total_hand*$peilv;

            $wx_order_number= $before24.''.$before25.''.$before26.''.$before27;
            $ordernumber = $wx_order_number.''.$lastnumber;
            $data['first_no'] = $ordernumber;
            $data['left_cash_time'] = 0;
            $data['wx_order_number'] = $wx_order_number;
            $data['tail'] = $lastnumber;
            $data['tail2'] = $before27;
            $data['tail3'] = $before26;
            $data['total_hand'] = $total_hand;



            return json($data);

        }else{
            // 输了
            $data['status'] = 3;
            $lastnumber = $this->bishu($number);
            $data['buy_money'] = $total_hand*$peilv*10;
            $data['last_number'] = $lastnumber;
            $data['win_money'] = $total_hand*$peilv;
            $data['number'] = $number;
            $data['is_win'] = 0;
            $data['my_money'] = $user['yue']+$data['buy_money'];//这一个有问题，需要计算钱包里的钱
            $wx_order_number= $before24.''.$before25.''.$before26.''.$before27;
            $ordernumber = $wx_order_number.''.$lastnumber;
            $data['first_no'] = $ordernumber;
            $data['left_cash_time'] = 0;
            $data['wx_order_number'] = $wx_order_number;
            $data['tail'] = $lastnumber;
            $data['tail2'] = $before27;
            $data['tail3'] = $before26;
            $data['total_hand'] = $total_hand;

            return json($data);


        }





    }


    /**
     * 赔率
     */
    private function peilv($number = 8)
    {
      //  $number =  Request::post('number');
        switch ($number)
        {
            case 0:
                $peilv = 1.9;
                break;
            case 1:
                $peilv = 1.9;
                break;
            case 2:
                $peilv = 1.9;
                break;
            case 3:
                $peilv = 1.9;
                break;
            case 4:
                $peilv = 3;
                break;
            case 5:
                $peilv = 3.8;
                break;
            case 6:
                $peilv = 3.8;
                break;
            case 7:
                $peilv = 3;
                break;
            case 8:
                $peilv = 1;
                break;
        }
        return $peilv;
    }

    /**
     * 算法 必输
     */
    public function bishu($number)
    {
        // 必输
        switch ($number){

            case 0: // 大

                $lastnumber = mt_rand(0,4);
                  return $lastnumber;
                break;
            case 1: // 小

                $lastnumber = mt_rand(5,9);
                 return $lastnumber;
                break;
            case 2: // 单

                $lastnumber =  $this->get_shuang();
                return $lastnumber;
                break;
            case 3: // 双
                $lastnumber = $this->get_dan();
                   return $lastnumber;
                break;
            case 4: // 大单
                $number1 = mt_rand(0,4);
                $number2 =  $this->get_shuang();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                return $lastnumber;
                break;
            case 5: // 小单
                $number1 = mt_rand(5,9);
                $number2 =  $this->get_shuang();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                return $lastnumber;
                break;
            case 6: // 大双
                $number1 = mt_rand(0,4);
                $number2 =  $this->get_dan();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                return $lastnumber;
                break;
            case 7:    // 小双
                $number1 = mt_rand(5,9);
                $number2 =  $this->get_dan();
                $lastnumber = mt_rand(0,99)%2==0?$number1:$number2;
                return $lastnumber;
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
    public function biying($number)
    {
        // 必赢
        switch ($number){

            case 0: // 大

                $lastnumber = mt_rand(5,9);
                //  return $lastnumber;
                break;
            case 1: // 小

                $lastnumber = mt_rand(0,4);
                //   return $lastnumber;
                break;
            case 2: // 单

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
        return $lastnumber;
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
<?php

class Wx{
    const TOKEN = 'weixin';

    private $config = [];
    private $obj;

    public function __construct()
    {
        if($_GET["echostr"]){
            echo  $this->checkSignature();
        }else{
            $this->config = include  'config.php';
            //获取数据
            $this->Catch();
        }



    }

    //初次接入方法
    private function checkSignature(){
        //公众平台传过来的数据
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $echostr = $_GET['echostr'];

        $tmpArr['token'] = self::TOKEN;
        $tmpArr['timestamp'] = $timestamp;
        $tmpArr['nonce'] = $nonce;
        //进行字典序排序
        sort($tmpArr,SORT_STRING);
        //拼接成字符串
        $tmpstr = implode($tmpArr);
        //进行sha1()加密
        $tmpstr = sha1($tmpstr);

        //将加密后的字符串和$signature进行对比(一样接入成功，不一样接入失败)
        if($tmpstr == $signature){
                return $echostr;
        }
        return '';
    }


    //接收文本消息
    private function Catch(){
        //接收数据
        $txt = file_get_contents('php://input');
        //把接收到的数据写如日志中
        $this->writeLog($txt);

        //将xml格式转化为对象
        $this->obj = simplexml_load_string($txt,'SimpleXMLElement',LIBXML_NOCDATA);
//        var_dump($obj);
//        exit;
        //消息的类型
        $type = $this->obj->MsgType;
        $msg = '';

        //动态方法
        $funName = $type . 'fun';
//        var_dump($funName);
        echo $msg = call_user_func([$this,$funName]);

        if(!empty($msg)){
            //把回复消息写入日志
            $this->writeLog($msg, 1);


        }

    }

    //接收数据----- 写入日志的方法
    /**
     * @param $txt  xml数据
     */
    private function writeLog( $txt,  $flag = 0){
        $title = $flag == 0 ? "接收" : "发送";
        $date = date('Y年m月d日 H:i:s');
        //将xml格式转化为对象
//        $obj = simplexml_load_string($txt,'SimpleXMLElement', LIBXML_NOCDATA);
//        var_dump($obj->Content);

        //日志内容

        $log = $title . "【{$date}】 \n";
        $log .= "-------------------------------------------- \n";
        $log .= $txt."\n";
        $log .= "--------------------------------------------- \n";

        //写入日志，以追加的形式写入
        file_put_contents("wx.xml",$log,FILE_APPEND);
    }


    //文本消息回复方法
    public function textfun(){
        $content =(string) $this->obj->Content;
        return $this->createText($content);

    }

    //生成文本消息的xml格式
    public function  createText($content){
            return sprintf($this->config['text'],$this->obj->FromUserName,$this->obj->ToUserName,time(),$content);
    }

}


 new Wx();










































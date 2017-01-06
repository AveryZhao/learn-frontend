<?php
/**
 * auther zhaoyanwei
 * date 2016.11.07
 * 微信
 */

class WechatController extends Controller {

    private $appid = 'wx810ae8a468e0d3cc';
    private $appsecret = '27acee3e7207fea68e1fd248dfd9576c';
    private $token = "cnfol.com";

    public function init(){

    }

    public function actionValid(){

        $echoStr = Yii::app()->request->getParam('echostr');
        $signature = Yii::app()->request->getParam('signature');
        $timestamp = Yii::app()->request->getParam('timestamp');
        $nonce = Yii::app()->request->getParam('nonce');
        //valid signature , option
        if($this->checkSignature($signature,$timestamp,$nonce)){
            echo $echoStr;
            exit;
        }
    }

    public function checkSignature($signature,$timestamp,$nonce){

        $tmpArr = array($this->token, $timestamp, $nonce);

        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function accessToken(){

        $RedisCluster = new RedisCluster();
        $tokenkey = Yii::app()->params['redis_prefix'] . 'wechat_access_token';
//        $RedisCluster->remove($tokenkey);
        $accesstoken = $RedisCluster->get($tokenkey);
        if(empty($accesstoken)){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
            $res = $this->CreateGetCurl($url);
            $result = json_decode($res,true);
            if(!empty($result['access_token'])){
                $RedisCluster->set($tokenkey,$result['access_token'],7200);
            }
            $accesstoken = $result['access_token'];
        }

        return $accesstoken;
    }

    public function actionGetSignature(){

        $browseurl = urldecode(Yii::app()->request->getParam('url'));
        $accesstoken = $this->accessToken();
        //获取jsapi_ticket
        $RedisCluster = new RedisCluster();
        $ticketkey = Yii::app()->params['redis_prefix'] . 'wechat_jsapi_ticket';
//        $RedisCluster->remove($ticketkey);
        $apiticket = $RedisCluster->get($ticketkey);
        if(empty($apiticket)){
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$accesstoken;
            $res = $this->CreateGetCurl($url);
            $result = json_decode($res,true);
            if(!empty($result['ticket'])){
                $RedisCluster->set($ticketkey,$result['ticket'],7200);
            }
            $apiticket = $result['ticket'];
        }
        //生成签名Signature
        $timestamp = time();
        $noncestr = 'Wm3WZYTPz0wzccnW';
        $string = "jsapi_ticket=".$apiticket."&noncestr=".$noncestr."&timestamp=".$timestamp."&url=".$browseurl;
        $signature = sha1($string);

        $sign = json_encode(array("appid"=>$this->appid,"signature"=>$signature,"noncestr"=>$noncestr,"timestamp"=>$timestamp));

       echo $sign;exit;
    }

    public function CreateGetCurl($url){
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //执行并获取HTML文档内容
        $output = curl_exec($ch);

        //释放curl句柄
        curl_close($ch);

        //返回获得的数据
        return $output;
    }
}
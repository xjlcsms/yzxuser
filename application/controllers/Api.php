<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/7/30
 * Time: 23:14
 */
class ApiController extends \Base\ApplicationController{
    public function indexAction(){
        
    }

    public function smsAction(){
        $account = $this->getParam('account','','string');
        $pwd = $this->getParam('password','','string');
        $mobile = $this->getParam('mobile','','string');
        $content = $this->getParam('content','','string');
        $sign = $this->getParam('sign','','string');
//        $data = array($account,$pwd,$mobile,$content);
//        return $this->returnData('111',0221,true,$data);
        if(empty($account) || empty($pwd) || empty($mobile) || empty($content) || empty($sign)){
            return $this->returnData('参数错误',100);
        }
        if(!\Ku\Verify::isMobile($mobile)){
            return $this->returnData('手机号格式不正确',101);
        }
        if(strlen($sign) > 12 || strlen($sign)<2){
            return $this->returnData('签名长度在2-12之间',103);
        }
        $content = '【'.$sign.'】'.$content;
        if(strlen($content) > 500){
            return $this->returnData('短信长度不可超过500',102);
        }
        $mapper = \Mapper\UsersModel::getInstance();
        $user = $mapper->fetch(array('account'=>$account,'isdel'=>0));
        if(!$user instanceof \UsersModel){
            return $this->returnData('账号不存在',104);
        }
        $userPwd = substr(md5($user->getRaw_password()),-32);
        if($userPwd != $pwd){
            return $this->returnData('密码不正确',105);
        }
        $business = \Business\SmsModel::getInstance();
        $fee = $business->oneFee($content);
        $virefy = $business->virefy($user,$content,1,$fee);
        if($virefy ==false){
            $msg = $business->getMessage();
            return $this->returnData($msg['msg'],106);
        }
        $userBusiness = \Business\UserModel::getInstance();
        $res = $userBusiness->flow($user,$fee,$fee,'normal');
        if(!$res){
           return $this->returnData('扣款失败',107);
        }
        $res = $business->smsApi($user,$mobile,$content);
        if($res === false or !isset($res['data']) ){
          return $this->returnData('发送失败',0);
        }
        $data = $res['data'][0];
        $recordMapper = \Mapper\SmsrecordModel::getInstance();
        $order = new \SmsrecordModel();
        $order->setUser_id($user->getId());
        $order->setSms_type(1);
        $order->setUid($data['uid']);
        $order->setSid($data['sid']);
        $order->setPhone($data['mobile']);
        $order->setContent($content);
        $order->setMasked_phone($data['mobile']);
        $order->setBilling_count($data['fee']);
        $order->setCode($data['code']);
        $order->setIsapi(1);
        if($data['code']!=0){
            $order->setMessage($data['msg']);
            $order->setStatus(2);
        }else{
            $order->setStatus(1);
        }
        $order->setCreated_at(date('YmdHis'));
        $recordMapper->insert($order);
        $sendData = array('fee'=>$fee,'sid'=>$order->getSid(),'mobile'=>$mobile,'content'=>$content,'send_time'=>$order->getCreated_at());
        if ($order->getStatus() == 1){
            return $this->returnData('发送成功',0,true,$sendData);
        }
        return $this->returnData($order->getMessage(),$order->getCode());
    }


    public function testAction(){
        $mobile = $this->getParam('mobile','','string');
        if(!\Ku\Verify::isMobile($mobile)){
            return $this->returnData('手机号不正确',1000);
        }
        $data = array(
            'account'=>'b00783','password'=>substr(md5('b127d1f1'),-32),
            'sign'=>'API测试','content'=>'测试发送消息','mobile'=>$mobile
        );
        $url = 'http://yzx.cddong.top/index/api/sms';
//        $url = 'http://www.yzxuser.com/index/api/sms';
        $http = new \Ku\Http();
        $http->setUrl($url);
        $http->setParam($data,true);
        $http->setTimeout(3);
        $send = $http->send();
        if($send ==false){
            return $this->returnData('失败');
        }
        $send = json_decode($send,true);
        var_dump($send);
        return false;
    }

}
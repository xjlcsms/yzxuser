<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/7/16
 * Time: 22:47
 */
class DataController extends \Base\ApplicationController{

    /**
     * 统计当天发送的短信数据
     * @return false
     */
    public function daysendAction(){
        $user = \Business\LoginModel::getInstance()->getLoginUser();
        $mapper =  \Mapper\SendtasksModel::getInstance();
        $date = date('Y-m-d');
        $generalWhere = array('sms_type in(1,2)','status in(1,2)',"updated_at >= '".$date." 00:00:00'","updated_at <= '".$date." 23:59:59'",'user_id'=>$user->getId());
        $marketWhere = array('sms_type'=>3,'status in(1,2)',"updated_at >= '".$date." 00:00:00'","updated_at <= '".$date." 23:59:59'",'user_id'=>$user->getId());
        $generalSend = $mapper->sum('billing_amount',$generalWhere);
        $marketSend = $mapper->sum('billing_amount',$marketWhere);
        $data = array('generalSend'=>$generalSend,'marketSend'=>$marketSend);
        return $this->returnData('获取成功',25002,true,$data);
    }

    /**
     * 获取当天的充值额度
     * @return false
     */
    public function dayrechargeAction(){
        $user = \Business\LoginModel::getInstance()->getLoginUser();
        $mapper = \Mapper\RechargerecordsModel::getInstance();
        $date = date('Y-m-d');
        $generalWhere = array('direction'=>1,'type'=>1,"created_at >= '".$date." 00:00:00'","created_at <= '".$date." 23:59:59'",'user_id'=>$user->getId());
        $marketWhere = array('direction'=>1,'type'=>2,"created_at >= '".$date." 00:00:00'","created_at <= '".$date." 23:59:59'",'user_id'=>$user->getId());
        $general = (float)$mapper->sum('amount',$generalWhere);
        $market = (float)$mapper->sum('amount',$marketWhere);
        $data = array('general'=>$general,'market'=>$market);
        return $this->returnData('获取成功',25003,true,$data);
    }


}
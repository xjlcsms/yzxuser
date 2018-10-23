<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/7/17
 * Time: 23:55
 */
class SendController extends \Base\ApplicationController{

    protected $_sendTypes = array(1=>'验证码',2=>'通知',3=>'营销');
    /**
     * 发送界面
     */
    public function indexAction(){
        $this->assign('sendTypes',$this->_sendTypes);

    }

    /**
     * 发送任务
     */
    public function sendtaskAction(){
        $user = \Business\LoginModel::getInstance()->getLoginUser();
        $where =array('user_id'=>$user->getId());
        $time = $this->getParam('time','','string');
        if(!empty($time)){
            $timeArr = explode('-',$time);
            $begin = date('Y-m-d',strtotime(trim($timeArr[0])));
            $end = date('Y-m-d',strtotime(trim($timeArr[1])));
            $where[] = "created_at >='".$begin." 00:00:00' and created_at <= '".$end." 23:59:59'";
        }
        $this->assign('time',$time);
        $type = $this->getParam('type',0,'int');
        if($type){
            $where['sms_type'] = $type;
        }
        $this->assign('type',$type);
        $status = $this->getParam('status',100,'int');
        if($type){
            $where['status'] = $status;
        }
        $this->assign('status',$status);
        $sign = $this->getParam('sign','','string');
        if($sign){
            $where[] = "sign like '%".$sign."%'";
        }
        $this->assign('sign',$sign);
        $content = $this->getParam('content','','string');
        if($content){
            $where[] = "content like '%".$content."%'";
        }
        $this->assign('content',$content);
        $mapper = \Mapper\SendtasksModel::getInstance();
        $select = $mapper->select();
        $select->where($where);
        $select->order(array('created_at desc'));
        $page = $this->getParam('page', 1, 'int');
        $pagelimit = $this->getParam('pagelimit', 15, 'int');
        $pager = new \Ku\Page($select, $page, $pagelimit, $mapper->getAdapter());
        $this->assign('pager', $pager);
        $this->assign('pagelimit', $pagelimit);
        $this->assign('types', $this->_sendTypes);
        $this->assign('statusData', array('发送中','成功','失败'));

    }
     /**
     * 数据统计
     */
    public function dataAction(){
        $user = \Business\LoginModel::getInstance()->getLoginUser();
        $where =array('user_id'=>$user->getId());
        $time = $this->getParam('time','','string');
        if(!empty($time)){
            $timeArr = explode('-',$time);
            $begin = date('Y-m-d',strtotime(trim($timeArr[0])));
            $end = date('Y-m-d',strtotime(trim($timeArr[1])));
            $where[] = "created_at >='".$begin." 00:00:00' and created_at <= '".$end." 23:59:59'";
        }
        $type = $this->getParam('type',0,'int');
        if($type){
            $where['sms_type'] = $type;
        }
        $this->assign('type',$type);
        $taskMapper = \Mapper\SendtasksModel::getInstance();
        $taskes = $taskMapper->fetchAll($where,null,0,0,array('id'),false);
        $taskIds = [];
        if(empty($taskes)){
            $taskIds =array(0);
        }else{
            foreach ($taskes as $task){
                $taskIds[] = $task['id'];
            }
        }
        $mapper = \Mapper\SmsqueueModel::getInstance();
        $lists = $mapper->fetchAll(array('task_id in('.implode(',',$taskIds).')'));
        $data = [];
        $business = \Business\SmsModel::getInstance();
        foreach ($lists as $list){
            $date = date('Y-m-d',strtotime($list->getCreated_at()));
            $feeone = $business->oneFee($list->getContent());
            if (isset($data[$date])){
                $data[$date]['total'] += $list->getTotal_num();
                $notArrive = $list->getTotal_num() - $list->getSend_num();
                $data[$date]['success'] += $list->getSuccess() + $notArrive;
                $fail = $list->getSend_num() - $list->getSuccess();
                $data[$date]['not_arrive'] = 0;
                $data[$date]['fail'] += $fail;
                $data[$date]['fee'] += $feeone * $list->getSend_num();
            }else{
                $data[$date]['total'] = $list->getTotal_num();
                $notArrive = $list->getTotal_num() - $list->getSend_num();
                $data[$date]['success'] = $list->getSuccess() + $notArrive;
                $fail = $list->getSend_num() - $list->getSuccess();
                $data[$date]['not_arrive'] = 0;
                $data[$date]['fail'] = $fail;
                $data[$date]['fee'] = $feeone * $list->getSend_num();
            }
        }
        $this->assign('sendTypes',$this->_sendTypes);
        $this->assign('data',$data);

    }
     /**
     * 发送记录
     */
    public function sendrecordAction(){
        $mapper = \Mapper\SmsrecordModel::getInstance();
        $user = \Business\LoginModel::getInstance()->getLoginUser();
        $where =array('user_id'=>$user->getId());
        $taskId = $this->getParam('taskId',0,'int');
        if($taskId){
            $record = $mapper->findByTask_Id($taskId);
            if(!$record instanceof \SmsrecordModel){
                return $this->redirect('/send/oldrecord?taskId='.$taskId);
            }
            $where['task_id'] = $taskId;
        }
        $this->assign('taskId',$taskId);
        $mobile = $this->getParam('mobile','','string');
        $content = $this->getParam('content','','string');
        $status = $this->getParam('status',100,'int');
        $report_status = $this->getParam('report_status',100,'int');
        if(!empty($mobile)){
            $where['phone'] = $mobile;
        }
        $this->assign('mobile',$mobile);
        if($content){
            $where[] = "content like '%".$content."%'";
        }
        $this->assign('content',$content);
        if ($status != 100){
            $where['status'] = $status;
        }
        $this->assign('status',$status);
        if ($report_status != 100){
            $where['report_status'] = $report_status;
        }
        $this->assign('report_status',$report_status);
        $type = $this->getParam('type',0,'int');
        if($type){
            $where['sms_type'] = $type;
        }
        $this->assign('type',$type);
        $select = $mapper->select();
        $select->where($where);
        $select->order(array('id desc'));
        $page = $this->getParam('page', 1, 'int');
        $pagelimit = $this->getParam('pagelimit', 15, 'int');
        $pager = new \Ku\Page($select, $page, $pagelimit, $mapper->getAdapter());
        $this->assign('pager', $pager);
        $this->assign('pagelimit', $pagelimit);
        $this->assign('types', $this->_sendTypes);
        $this->assign('statusData', array('发送中','成功','失败'));
        $this->assign('reportStatus', array('发送中','已到达','未到达'));
    }

    public function oldrecordAction(){
        $user = \Business\LoginModel::getInstance()->getLoginUser();
        $recordMapper = \Mapper\RecorduserModel::getInstance();
        $recordMapper->setTable('record_user_'.$user->getId());
        $where =array();
        $taskId = $this->getParam('taskId',0,'int');
        if($taskId){
            $where['task_id'] = $taskId;
        }
        $this->assign('taskId',$taskId);
        $mobile = $this->getParam('mobile','','string');
        $content = $this->getParam('content','','string');
        $status = $this->getParam('status',100,'int');
        $report_status = $this->getParam('report_status',100,'int');
        if(!empty($mobile)){
            $where['mobile'] = $mobile;
        }
        $this->assign('mobile',$mobile);
        if($content){
            $where[] = "content like '%".$content."%'";
        }
        $this->assign('content',$content);
        if ($status != 100){
            $where['status'] = $status;
        }
        $this->assign('status',$status);
        if ($report_status != 100){
            $where['report_status'] = $report_status;
        }
        $this->assign('report_status',$report_status);
        $type = $this->getParam('type',0,'int');
        if($type){
            $where['sms_type'] = $type;
        }
        $this->assign('type',$type);
        $select = $recordMapper->select();
        $select->where($where);
        $select->order(array('id desc'));
        $page = $this->getParam('page', 1, 'int');
        $pagelimit = $this->getParam('pagelimit', 15, 'int');
        $pager = new \Ku\Page($select, $page, $pagelimit, $recordMapper->getAdapter());
        $this->assign('pager', $pager);
        $this->assign('pagelimit', $pagelimit);
        $this->assign('types', $this->_sendTypes);
        $this->assign('statusData', array('发送中','成功','失败'));
        $this->assign('reportStatus', array('发送中','已到达','未到达'));
    }

    /**
     * 发送短信
     * @return false
     * @throws Exception
     * @throws \PHPExcel\PHPExcel\Reader_Exception
     */
    public function smsAction(){
        $business = \Business\LoginModel::getInstance();
        $user = $business->getCurrentUser();
        $type = $this->getParam('type',0,'int');
        $tempId = $this->getParam('tempId',0,'int');
        $smstype = $this->getParam('smstype',0,'int');
        $content = $this->getParam('content','','string');
        $sign = $this->getParam('sign','','string');
        $smsBusiness = \Business\SmsModel::getInstance();
        if($smstype == 1){
            $smsfile = $this->getParam('smsfile','','string');
            if(!file_exists(APPLICATION_PATH.'/public/uploads/sms/'.$smsfile) || empty($smsfile)){
                return $this->returnData('发送文件不存在',29200);
            }
            $mobiles = $smsBusiness->importMobiles($smsfile);
        }else{
            $mobilesStr = $this->getParam('mobiles','','string');
            $mobiles = explode(',',$mobilesStr);
        }
        if(empty($mobiles)){
            return $this->returnData('没有获取到有效的手机号',29202);
        }
        if($tempId){
            $template = \Mapper\TemplatesModel::getInstance()->fetch(array('id'=>$tempId,'user_id'=>$user->getId()));
            if($template instanceof \TemplatesModel and $template->getType()==2){
                $content = str_replace('【'.$sign.'】','',$content);
                $params = explode(';',$content);
                $tContent = $template->getContent();
                $tContent = str_replace('{}','%s',$tContent);
                array_unshift($params,$tContent);
                $content = call_user_func_array('sprintf',$params);
            }else{
                $content = $template->getContent();
            }
            $content = '【'.$sign.'】'.$content;
        }
        //发送的总数
        $totalfee = $smsBusiness->totalFee($mobiles,$content);
        $virefy = $smsBusiness->virefy($user,$content,$type,$totalfee);
        if(!$virefy){
            $message = $smsBusiness->getMessage();
            return $this->returnData($message['msg'],$message['code']);
        }
        $userBusiness = \Business\UserModel::getInstance();
        $account = $type == 3?'market':'normal';
        $res = $userBusiness->flow($user,0,$totalfee,$account);
        if(!$res){
            $config = \Yaf\Registry::get('config');
            $key = $config->get('flow.error');
            $redis = $this->getRedis();
            $redis->lPush($key,json_encode(array('userid'=>$user->getId(),'type'=>$account.'_show','fee'=>$totalfee)));
        }
        $mapper = \Mapper\SendtasksModel::getInstance();
        $senTask = new \SendtasksModel();
        $senTask->setUser_id($user->getId());
        $senTask->setSign($sign);
        $senTask->setContent($content);
        $senTask->setSms_type($type);
        $senTask->setType(1);
        $senTask->setCreated_at(date('Y-m-d H:i:s'));
        $senTask->setUpdated_at(date('Y-m-d H:i:s'));
        $senTask->setTemplate_id($tempId);
        $senTask->setStatus(1);
        $senTask->setQuantity(count($mobiles));
        $senTask->setBilling_count($smsBusiness->oneFee($content));
        $senTask->setBilling_amount($totalfee);
        $res =$mapper->insert($senTask);
        if(!$res){
            return $this->returnData('发送失败',36000);
        }
        $taskid = $mapper->getLastInsertId();
        $mobiles = $smsBusiness->divideMobiles($mobiles);
        $smsMapper = \Mapper\SmsqueueModel::getInstance();
        $smsMapper->begin();
        $model = new \SmsqueueModel();
        $model->setTask_id($taskid);
        $model->setContent($content);
        $model->setType($type);
        $model->setError('');
        foreach ($mobiles as $mobile){
            $uid = $taskid.date('ymdHis').mt_rand(1000, 9999);
            $model->setUid($uid);
            $data = $smsBusiness->trueMobiles($user,$mobile);
            $model->setCreated_at(date('Ymdhis'));
            $fail = empty($fail)?'':implode(',',$fail);
            $model->setNot_arrive($fail);
            $true = implode(',',$data['true']);
            $model->setMobiles(empty($true)?'':$true);
            $model->setSend_num(count($data['true']));
            $model->setTotal_num(count($mobile));
            $res = $smsMapper->insert($model);
            if($res === false){
                $smsMapper->rollback();
                \Mapper\SendtasksModel::getInstance()->update(array('status'=>2),array('id'=>$taskid));
                return $this->returnData('发送失败',29200);
            }
        }
        \Mapper\SendtasksModel::getInstance()->update(array('status'=>1),array('id'=>$taskid));
        $smsMapper->commit();
        return $this->returnData('发送成功',29201, true);
    }


    /**系统代发
     * @return false
     */
    public function syssmsAction(){
        $business = \Business\LoginModel::getInstance();
        $user = $business->getCurrentUser();
        $type = $this->getParam('type',0,'int');
        $tempId = $this->getParam('tempId',0,'int');
        $amount = $this->getParam('amount',0,'int');
        $area = $this->getParam('area','','string');
        if(empty($amount)){
            return $this->returnData('短信数量不能为空',1000);
        }
        $content = $this->getParam('content','','string');
        $sign = $this->getParam('sign','','string');
        $mapper = \Mapper\SendtasksModel::getInstance();
        $smsBusiness = \Business\SmsModel::getInstance();
        $onefee = $smsBusiness->oneFee($content);
        $senTask = new \SendtasksModel();
        $senTask->setUser_id($user->getId());
        $senTask->setSign($sign);
        $senTask->setContent($content);
        $senTask->setSms_type($type);
        $senTask->setType(2);
        $senTask->setCreated_at(date('Y-m-d H:i:s'));
        $senTask->setUpdated_at(date('Y-m-d H:i:s'));
        $senTask->setTemplate_id($tempId);
        $senTask->setStatus(0);
         $senTask->setArea($area);
        $senTask->setQuantity($amount);
        $senTask->setBilling_count($onefee);
        $senTask->setBilling_amount($onefee*$amount);
        $res =$mapper->insert($senTask);
        if(!$res){
            return $this->returnData('发送失败',36000);
        }
        return $this->returnData('代发创建成功，等待审核',36001,true);
    }

    /**批量发送文件上传
     * @return false
     * @throws Exception
     * @throws \PHPExcel\PHPExcel\Reader_Exception
     */
    public function smsfileAction(){
        $fileInfo = $_FILES['smsfile'];
        $taskid = $this->getParam('taskid',0,'int');
        $task = \Mapper\SendtasksModel::getInstance()->findById($taskid);
        if(!$task instanceof \SendtasksModel){
            return $this->returnData('发送任务不存在',291004);
        }
        if (empty($fileInfo)) {
            return $this->returnData('没有文件上传！', 29100);
        }
        $name=explode('.',$fileInfo['name']);
        $lastName=end($name);
        if(strtolower($lastName) != 'xls' and strtolower($lastName) !='xlsx' and strtolower($lastName) !='xlsb'){
            return $this->returnData('上传文件格式必须为/xls/xlsx/xlsb等文件！', 28101);
        }
        if ($fileInfo['error'] > 0) {
            $errors = array(1=>'文件大小超过了PHP.ini中的文件限制！',2=>'文件大小超过了浏览器限制！',3=>'文件部分被上传！','没有找到要上传的文件！',4=>'服务器临时文件夹丢失，请重新上传！',5=>'文件写入到临时文件夹出错！');
            $error = isset($errors[$fileInfo['error']])?$errors[$fileInfo['error']]:'未知错误！';
            return $this->returnData($error, 29102);
        }
        $d = date("YmdHis");
        $randNum = rand((int)50000000, (int)10000000000);
        $filesname = $d . $randNum . '_'.$taskid.'.' .$lastName;
        $dir = APPLICATION_PATH . '/public/uploads/sms/';
        if(!file_exists($dir)){
            \Ku\Tool::makeDir($dir);
        }
        if (!copy($fileInfo['tmp_name'], $dir. $filesname)) {
            return $this->returnData('文件上传失败！', 29103);
        }
        try{
            $read = \PHPExcel\IOFactory::createReader('Excel2007');
            $obj = $read->load($dir. $filesname);
            $dataArray =$obj->getActiveSheet()->toArray();
            $mobiles = [];
            $fail = 0;
            unset($dataArray[0]);
            foreach ($dataArray as $key=> $item){
                if(!\Ku\Verify::isMobile($item[0])){
                    $fail++;
                    continue;
                }
                $mobiles[] = $item[0];
            }
            $isMobile = count($mobiles);
            $mobiles = array_unique($mobiles);
            $true = count($mobiles);
            $key = md5($filesname);
            $redis = $this->getRedis();
            $redis->set($key,json_encode($mobiles),3600*2);
            return $this->returnData('文件上传成功！', 29101,true,array('filename'=>$filesname,'total'=>$fail+$isMobile,'true'=>$true,'repeat'=>$isMobile-$true,'fail'=>$fail));
        }catch (ErrorException $errorException){
            return $this->returnData('读取文件错误',29105);
        }
    }


    /**文件删除
     * @return false
     * @throws Exception
     */
    public function delsmsfAction(){
        $fileName = $this->getParam('fileName','','string');
        $dir = APPLICATION_PATH.'/public/uploads/sms/'.$fileName;
        if(file_exists($dir)){
            @unlink($dir);
        }
        $redis = $this->getRedis();
        $redis->del(md5($fileName));
        return $this->returnData('删除成功',29011,true);
    }

    /**
     * 下载批量短信模板
     */
    public function downtempAction(){
        header('Content-Type:application/xlsx');
        header('Content-Disposition:attachment;filename=sms_template.xlsx');
        header('Cache-Control:max-age=0');
        readfile(APPLICATION_PATH.'/data/sms_template.xlsx');
        exit();
    }


    /**获取短信发送信息
     * @return false
     */
    public function checknumAction(){
        $mobileStr = $this->getParam('mobiles','','string');
        $content = $this->getParam('content','','string');
        $mobiles= [];
        if(!empty($mobileStr)){
            $mobiles = explode(',',$mobileStr);
        }
        $business = \Business\SmsModel::getInstance();
        $oneFee = $business->oneFee($content);
        $total = $oneFee * count($mobiles);
        $data = array('total'=>$total,'oneFee'=>$oneFee);
        return $this->returnData('获取成功',1001,true,$data);
    }

}
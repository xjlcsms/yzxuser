<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/7/5
 * Time: 0:07
 */
//27000
class TemplateController extends \Base\ApplicationController
{
    private $_templateStatus = array('待审核','审核通过','审核不通过');
    private $_templateTypes = array(1=>'固定模板',2=>'变量模板');
    private $_accounts = array(1=>'行业短信',2=>'营销短信',3=>'特殊短信');

    /**
     * 模板列表
     */
    public function indexAction(){
        $business = \Business\LoginModel::getInstance();
        $user = $business->getCurrentUser();
        $where = ['user_id'=>$user->getId()];
        $status = $this->getParam('status',100,'int');
        $type = $this->getParam('type',0,'int');
        $classify = $this->getParam('classify',0,'int');
        $sign = $this->getParam('sign','','string');
        $userId = $this->getParam('userId','','int');
        $tempId = $this->getParam('tempid','','int');
        if($tempId){
            $where['template_id'] = $tempId;
        }
        if($classify){
            $where['classify'] =$classify;
        }
        if($status !=100){
            $where['status'] = $status;
        }
        if($type){
            $where['type'] = $type;
        }
        if($sign){
            $where[] = "sign like '%".$sign."%'";
        }
        if($userId){
            $where['user_id'] = $userId;
        }
        $mapper = \Mapper\TemplatesModel::getInstance();
        $select = $mapper->select();
        $select->where($where);
        $select->order(array('created_at desc'));
        $page = $this->getParam('page', 1, 'int');
        $pagelimit = $this->getParam('pagelimit', 15, 'int');
        $pager = new \Ku\Page($select, $page, $pagelimit, $mapper->getAdapter());
        $this->assign('pager', $pager);
        $this->assign('pagelimit', $pagelimit);
        $this->assign('userId', $userId);
        $this->assign('status', $status);
        $this->assign('classify', $classify);
        $this->assign('type', $type);
        $this->assign('sign', $sign);
        $this->assign('types',$this->_templateTypes);
        $this->assign('templateStatus',$this->_templateStatus);
        $this->assign('accounts',$this->_accounts);
    }


    /**添加模板
     * @return false
     */
    public function addAction(){
        $business = \Business\LoginModel::getInstance();
        $user = $business->getCurrentUser();
        $type = $this->getParam('type','','int');
//        $tempId = $this->getParam('tempId','','int');
        $classify = $this->getParam('classify','','int');
        $sign = $this->getParam('sign','','string');
        $request = $this->getRequest();
        $content = $request->get('content','');
        if(empty($content) || empty($sign)){
            return $this->returnData('签名或内容不可为空',31001);
        }
        $model = new \TemplatesModel();
        $model->setUpdated_at(date('Y-m-d H:i:s'));
        $model->setType($type);
        $model->setContent($content);
        $model->setCreated_at(date('Y-m-d H:i:s'));
        $model->setSign($sign);
        $model->setClassify($classify);
        $model->setUser_id($user->getId());
        $res = \Mapper\TemplatesModel::getInstance()->insert($model);
        if(!$res){
            return $this->returnData('添加失败',31002);
        }
        return $this->returnData('添加成功',31003,true);
    }


    /**获取模板内容
     * @return false
     */
    public function gainAction(){
        $business = \Business\LoginModel::getInstance();
        $user = $business->getCurrentUser();
        $mapper =\Mapper\TemplatesModel::getInstance();
        $tempId = $this->getParam('tempId',0,'int');
        $temp = $mapper->fetch(array('Id'=>$tempId,'status'=>1,'user_id'=>$user->getId()));
        if(!$temp instanceof \TemplatesModel){
            return $this->returnData('模板不存在或未审核',27006);
        }
        $data = $temp->toArray();
        return $this->returnData('获取成功',27005,true,$data);
    }


}
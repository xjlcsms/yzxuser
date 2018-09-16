<?php

class IndexController extends \Base\ApplicationController
{
    public function indexAction()
    {
        $user = \Business\LoginModel::getInstance()->getLoginUser();
        $this->assign('user',$user->toArray());
    }

    /**
     * 修改账号密码
     * @return false
     */
    public function changepwdAction(){
        $old = $this->getParam('old','','string');
        $new = $this->getParam('new','','string');
        $sure = $this->getParam('sure','','string');
        $business = \Business\LoginModel::getInstance();
        $user = $business->getCurrentUser();
        if(!$user instanceof \UsersModel){
            return $this->returnData('请先登陆',1200,false,array('url'=>'/login'));
        }
        if (\Ku\Tool::valid($old, $user->getNew_password(), null) === false) {
            return $this->returnData('原密码错误，请重新输入',1202);
        }
        if(strlen($new) <6){
            return $this->returnData('新密码长度至少6位',1203);
        }
        if($new != $sure){
            return $this->returnData('两次输入的密码不一致',1204);
        }
        $user->setNew_password(\Ku\Tool::encryption($new));
        $user->setUpdated_at(date('Y-m-d H:i:s'));
        $res = \Mapper\AdminModel::getInstance()->update($user);
        if(!$res){
            return $this->returnData('更改密码失败,请重试！',1205);
        }
        return $this->returnData('修改成功',1201,true);
    }

}

<?php

class LogoutController extends \Base\ApplicationController {

    /**
     * 自动开启视图引擎及布局模块
     *
     * @var boolean
     */
    protected $autoRender = false;
    protected $autoLayout = false;

    public function indexAction() {
        \Business\LoginModel::getInstance()->logout();
        $this->redirect($this->getRedirectUrl(\Bootstrap::getApiDomain('wwwUrl')));
        return false;
    }

}

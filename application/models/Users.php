<?php

/**
 * Users
 * 
 * @Table Schema: sms_test
 * @Table Name: users
 */
class UsersModel extends \Base\Model\AbstractModel {

    /**
     * Params
     * 
     * @var array
     */
    protected $_params = null;

    /**
     * Id
     * 
     * Column Type: int(10) unsigned
     * auto_increment
     * PRI
     * 
     * @var int
     */
    protected $_id = null;

    /**
     * Username
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_username = null;

    /**
     * Password
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_password = null;

    /**
     * 公司名称
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_name = null;

    /**
     * 用户类型 1 普通短信 2 营销短信
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @var int
     */
    protected $_type = 1;

    /**
     * 到达率
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 100
     * 
     * @var int
     */
    protected $_arrival_rate = 100;

    /**
     * 云之讯对应账号
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_account = null;

    /**
     * 云之讯对应密码
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_raw_password = null;

    /**
     * 真实余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_normal_balance = 0;

    /**
     * 营销余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_marketing_balance = 0;

    /**
     * 显示余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_show_normal_balance = 0;

    /**
     * 显示营销余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_show_marketing_balance = 0;

    /**
     * Remember_token
     * 
     * Column Type: varchar(100)
     * 
     * @var string
     */
    protected $_remember_token = null;

    /**
     * Created_at
     * 
     * Column Type: timestamp
     * 
     * @var string
     */
    protected $_created_at = null;

    /**
     * Updated_at
     * 
     * Column Type: timestamp
     * 
     * @var string
     */
    protected $_updated_at = null;

    /**
     * 账号所属平台 1 云之讯 2 纯真
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @var int
     */
    protected $_platform_type = 1;

    /**
     * 是否删除
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @var int
     */
    protected $_isdel = 0;

    /**
     * Params
     * 
     * Column Type: array
     * Default: null
     * 
     * @var array
     */
    public function getParams() {
        return $this->_params;
    }

    /**
     * Id
     * 
     * Column Type: int(10) unsigned
     * auto_increment
     * PRI
     * 
     * @param int $id
     * @return \UsersModel
     */
    public function setId($id) {
        $this->_id = (int)$id;
        $this->_params['id'] = (int)$id;
        return $this;
    }

    /**
     * Id
     * 
     * Column Type: int(10) unsigned
     * auto_increment
     * PRI
     * 
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Username
     * 
     * Column Type: varchar(191)
     * 
     * @param string $username
     * @return \UsersModel
     */
    public function setUsername($username) {
        $this->_username = (string)$username;
        $this->_params['username'] = (string)$username;
        return $this;
    }

    /**
     * Username
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getUsername() {
        return $this->_username;
    }

    /**
     * Password
     * 
     * Column Type: varchar(191)
     * 
     * @param string $password
     * @return \UsersModel
     */
    public function setPassword($password) {
        $this->_password = (string)$password;
        $this->_params['password'] = (string)$password;
        return $this;
    }

    /**
     * Password
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getPassword() {
        return $this->_password;
    }

    /**
     * 公司名称
     * 
     * Column Type: varchar(191)
     * 
     * @param string $name
     * @return \UsersModel
     */
    public function setName($name) {
        $this->_name = (string)$name;
        $this->_params['name'] = (string)$name;
        return $this;
    }

    /**
     * 公司名称
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * 用户类型 1 普通短信 2 营销短信
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @param int $type
     * @return \UsersModel
     */
    public function setType($type) {
        $this->_type = (int)$type;
        $this->_params['type'] = (int)$type;
        return $this;
    }

    /**
     * 用户类型 1 普通短信 2 营销短信
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @return int
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * 到达率
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 100
     * 
     * @param int $arrival_rate
     * @return \UsersModel
     */
    public function setArrival_rate($arrival_rate) {
        $this->_arrival_rate = (int)$arrival_rate;
        $this->_params['arrival_rate'] = (int)$arrival_rate;
        return $this;
    }

    /**
     * 到达率
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 100
     * 
     * @return int
     */
    public function getArrival_rate() {
        return $this->_arrival_rate;
    }

    /**
     * 云之讯对应账号
     * 
     * Column Type: varchar(191)
     * 
     * @param string $account
     * @return \UsersModel
     */
    public function setAccount($account) {
        $this->_account = (string)$account;
        $this->_params['account'] = (string)$account;
        return $this;
    }

    /**
     * 云之讯对应账号
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getAccount() {
        return $this->_account;
    }

    /**
     * 云之讯对应密码
     * 
     * Column Type: varchar(191)
     * 
     * @param string $raw_password
     * @return \UsersModel
     */
    public function setRaw_password($raw_password) {
        $this->_raw_password = (string)$raw_password;
        $this->_params['raw_password'] = (string)$raw_password;
        return $this;
    }

    /**
     * 云之讯对应密码
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getRaw_password() {
        return $this->_raw_password;
    }

    /**
     * 真实余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @param int $normal_balance
     * @return \UsersModel
     */
    public function setNormal_balance($normal_balance) {
        $this->_normal_balance = (int)$normal_balance;
        $this->_params['normal_balance'] = (int)$normal_balance;
        return $this;
    }

    /**
     * 真实余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getNormal_balance() {
        return $this->_normal_balance;
    }

    /**
     * 营销余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @param int $marketing_balance
     * @return \UsersModel
     */
    public function setMarketing_balance($marketing_balance) {
        $this->_marketing_balance = (int)$marketing_balance;
        $this->_params['marketing_balance'] = (int)$marketing_balance;
        return $this;
    }

    /**
     * 营销余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getMarketing_balance() {
        return $this->_marketing_balance;
    }

    /**
     * 显示余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @param int $show_normal_balance
     * @return \UsersModel
     */
    public function setShow_normal_balance($show_normal_balance) {
        $this->_show_normal_balance = (int)$show_normal_balance;
        $this->_params['show_normal_balance'] = (int)$show_normal_balance;
        return $this;
    }

    /**
     * 显示余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getShow_normal_balance() {
        return $this->_show_normal_balance;
    }

    /**
     * 显示营销余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @param int $show_marketing_balance
     * @return \UsersModel
     */
    public function setShow_marketing_balance($show_marketing_balance) {
        $this->_show_marketing_balance = (int)$show_marketing_balance;
        $this->_params['show_marketing_balance'] = (int)$show_marketing_balance;
        return $this;
    }

    /**
     * 显示营销余额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getShow_marketing_balance() {
        return $this->_show_marketing_balance;
    }

    /**
     * Remember_token
     * 
     * Column Type: varchar(100)
     * 
     * @param string $remember_token
     * @return \UsersModel
     */
    public function setRemember_token($remember_token) {
        $this->_remember_token = (string)$remember_token;
        $this->_params['remember_token'] = (string)$remember_token;
        return $this;
    }

    /**
     * Remember_token
     * 
     * Column Type: varchar(100)
     * 
     * @return string
     */
    public function getRemember_token() {
        return $this->_remember_token;
    }

    /**
     * Created_at
     * 
     * Column Type: timestamp
     * 
     * @param string $created_at
     * @return \UsersModel
     */
    public function setCreated_at($created_at) {
        $this->_created_at = (string)$created_at;
        $this->_params['created_at'] = (string)$created_at;
        return $this;
    }

    /**
     * Created_at
     * 
     * Column Type: timestamp
     * 
     * @return string
     */
    public function getCreated_at() {
        return $this->_created_at;
    }

    /**
     * Updated_at
     * 
     * Column Type: timestamp
     * 
     * @param string $updated_at
     * @return \UsersModel
     */
    public function setUpdated_at($updated_at) {
        $this->_updated_at = (string)$updated_at;
        $this->_params['updated_at'] = (string)$updated_at;
        return $this;
    }

    /**
     * Updated_at
     * 
     * Column Type: timestamp
     * 
     * @return string
     */
    public function getUpdated_at() {
        return $this->_updated_at;
    }

    /**
     * 账号所属平台 1 云之讯 2 纯真
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @param int $platform_type
     * @return \UsersModel
     */
    public function setPlatform_type($platform_type) {
        $this->_platform_type = (int)$platform_type;
        $this->_params['platform_type'] = (int)$platform_type;
        return $this;
    }

    /**
     * 账号所属平台 1 云之讯 2 纯真
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @return int
     */
    public function getPlatform_type() {
        return $this->_platform_type;
    }

    /**
     * 是否删除
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @param int $isdel
     * @return \UsersModel
     */
    public function setIsdel($isdel) {
        $this->_isdel = (int)$isdel;
        $this->_params['isdel'] = (int)$isdel;
        return $this;
    }

    /**
     * 是否删除
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @return int
     */
    public function getIsdel() {
        return $this->_isdel;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'                     => $this->_id,
            'username'               => $this->_username,
            'password'               => $this->_password,
            'name'                   => $this->_name,
            'type'                   => $this->_type,
            'arrival_rate'           => $this->_arrival_rate,
            'account'                => $this->_account,
            'raw_password'           => $this->_raw_password,
            'normal_balance'         => $this->_normal_balance,
            'marketing_balance'      => $this->_marketing_balance,
            'show_normal_balance'    => $this->_show_normal_balance,
            'show_marketing_balance' => $this->_show_marketing_balance,
            'remember_token'         => $this->_remember_token,
            'created_at'             => $this->_created_at,
            'updated_at'             => $this->_updated_at,
            'platform_type'          => $this->_platform_type,
            'isdel'                  => $this->_isdel
        );
    }

}

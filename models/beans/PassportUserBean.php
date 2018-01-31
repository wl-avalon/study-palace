<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/31
 * Time: 下午7:00
 */

namespace app\models\beans;


class PassportUserBean
{
    private $id             = null; // bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键,自增ID',
    private $uuid           = null; // varchar(100) DEFAULT '' COMMENT '用户唯一ID',
    private $user_type      = null; // tinyint(4) DEFAULT '0' COMMENT '用户类型 0:超级管理员',
    private $user_status    = null; // tinyint(4) DEFAULT '0' COMMENT '用户状态 0:预注册 1:正常，2:挂失(临时不用) 3:注销(永久不用)',
    private $phone          = null; // varchar(30) DEFAULT '' COMMENT '手机号',
    private $nick_name      = null; // varchar(30) DEFAULT '' COMMENT '昵称',
    private $avatar_url     = null; // varchar(300) DEFAULT '' COMMENT '头像地址',
    private $register_time  = null; // timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '注册时间',
    private $create_time    = null; // timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    private $update_time    = null; // timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',

    public function __construct($input)
    {
        $this->id              = $input['id']               ?? null;
        $this->uuid            = $input['uuid']             ?? null;
        $this->user_type       = $input['user_type']        ?? null;
        $this->user_status     = $input['user_status']      ?? null;
        $this->phone           = $input['phone']            ?? null;
        $this->nick_name       = $input['nick_name']        ?? null;
        $this->avatar_url      = $input['avatar_url']       ?? null;
        $this->register_time   = $input['register_time']    ?? null;
        $this->create_time     = $input['create_time']      ?? null;
        $this->update_time     = $input['update_time']      ?? null;
    }

    public function toArray(){
        return [
            'id'            => $this->id,
            'uuid'          => $this->uuid,
            'user_type'     => $this->user_type,
            'user_status'   => $this->user_status,
            'phone'         => $this->phone,
            'nick_name'     => $this->nick_name,
            'avatar_url'    => $this->avatar_url,
            'register_time' => $this->register_time,
            'create_time'   => $this->create_time,
            'update_time'   => $this->update_time,
        ];
    }

    public function getID()            {return $this->id;}
    public function getUuid()          {return $this->uuid;}
    public function getUserType()      {return $this->user_type;}
    public function getUserStatus()    {return $this->user_status;}
    public function getPhone()         {return $this->phone;}
    public function getNickName()      {return $this->nick_name;}
    public function getAvatarRrl()     {return $this->avatar_url;}
    public function getRegisterTime()  {return $this->register_time;}
    public function getCreateTime()    {return $this->create_time;}
    public function getUpdateTime()    {return $this->update_time;}

    public function setID($id)                      {$this->id = $id;}
    public function setUuid($uuid)                  {$this->uuid = $uuid;}
    public function setUserType($user_type)         {$this->user_type = $user_type;}
    public function setUserStatus($user_status)     {$this->user_status = $user_status;}
    public function setPhone($phone)                {$this->phone = $phone;}
    public function setNickName($nick_name)         {$this->nick_name = $nick_name;}
    public function setAvatarRrl($avatar_url)       {$this->avatar_url = $avatar_url;}
    public function setRegisterTime($register_time) {$this->register_time = $register_time;}
    public function setCreateTime($create_time)     {$this->create_time = $create_time;}
    public function setUpdateTime($update_time)     {$this->update_time = $update_time;}
}
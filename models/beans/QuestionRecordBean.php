<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/1
 * Time: 上午11:18
 */

namespace app\models\beans;


class QuestionRecordBean
{
    private $id                     = null; // BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '主键,自增ID',
    private $uuid                   = null; // VARCHAR(32) NOT NULL DEFAULT '' COMMENT '业务唯一ID',
    private $question_md5           = null; // VARCHAR(40) DEFAULT '' COMMENT '去重的md5',
    private $question_creator_id    = null; // VARCHAR(100) DEFAULT '' COMMENT '创建问题人的ID',
    private $question_remark        = null; // TEXT COMMENT '问题的附件',
    private $work_status            = null; // TINYINT(4) NOT NULL DEFAULT 0 COMMENT '问题状态 0:待拆解, 1:拆解中, 2:拆解完成',
    private $work_content           = null; // TEXT COMMENT '拆解用到的参数',
    private $del_status             = null; // BOOLEAN DEFAULT FALSE COMMENT '逻辑删除状态,false:未删除, true:已删除',
    private $create_time            = null; // TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    private $update_time            = null; // TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',

    public function __construct($input)
    {
        $this->id                   = $input['id']                  ?? null;
        $this->uuid                 = $input['uuid']                ?? null;
        $this->question_md5         = $input['question_md5']        ?? null;
        $this->question_creator_id  = $input['question_creator_id'] ?? null;
        $this->question_remark      = $input['question_remark   ']  ?? null;
        $this->work_status          = $input['work_status']         ?? null;
        $this->work_content         = $input['work_content']        ?? null;
        $this->del_status           = $input['del_status']          ?? null;
        $this->create_time          = $input['create_time']         ?? null;
        $this->update_time          = $input['update_time']         ?? null;
    }

    public function toArray(){
        return [
            'id'                    => $this->id,
            'uuid'                  => $this->uuid,
            'question_md5'          => $this->question_md5,
            'question_creator_id'   => $this->question_creator_id,
            'question_remark   '    => $this->question_remark   ,
            'work_status'           => $this->work_status,
            'work_content'          => $this->work_content,
            'del_status'            => $this->del_status,
            'create_time'           => $this->create_time,
            'update_time'           => $this->update_time,
        ];
    }

    public function getID()                 {return $this->id;}
    public function getUuid()               {return $this->uuid;}
    public function getQuestionMD5()        {return $this->question_md5;}
    public function getQuestionCreatorID()  {return $this->question_creator_id;}
    public function getQuestionRemark()     {return $this->question_remark;}
    public function getWorkStatus()         {return $this->work_status;}
    public function getWorkContent()        {return $this->work_content;}
    public function getDelStatus()          {return $this->del_status;}
    public function getCreateTime()         {return $this->create_time;}
    public function getUpdateTime()         {return $this->update_time;}

    public function setID($id)                                 {$this->id = $id;}
    public function setUuid($uuid)                             {$this->uuid = $uuid;}
    public function setQuestionMD5($question_md5)              {$this->question_md5 = $question_md5;}
    public function setQuestionCreatorID($question_creator_id) {$this->question_creator_id = $question_creator_id;}
    public function setQuestionRemark($question_remark)        {$this->question_remark = $question_remark;}
    public function setWorkStatus($work_status)                {$this->work_status = $work_status;}
    public function setWorkContent($work_content)              {$this->work_content = $work_content;}
    public function setDelStatus($del_status)                  {$this->del_status = $del_status;}
    public function setCreateTime($create_time)                {$this->create_time = $create_time;}
    public function setUpdateTime($update_time)                {$this->update_time = $update_time;}

}
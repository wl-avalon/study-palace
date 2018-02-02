<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/30
 * Time: 下午10:29
 */

namespace app\models\beans;


class QuestionDetailBean
{
    private $id                         = null; // BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '主键,自增ID',
    private $uuid                       = null; // VARCHAR(32) NOT NULL DEFAULT '' COMMENT '业务唯一ID',
    private $question_record_id         = null; // VARCHAR(100) DEFAULT '' COMMENT '问题记录ID',
    private $question_content           = null; // TEXT COMMENT '问题的正文',
    private $question_answer            = null; // TEXT COMMENT '问题的答案',
    private $question_analysis          = null; // TEXT COMMENT '问题的分析',
    private $question_knowledge_point   = null; // TEXT COMMENT '问题的知识点',
    private $question_question_point    = null; // TEXT COMMENT '问题的题点',
    private $difficulty                 = null; // TINYINT(4) DEFAULT 0 COMMENT '问题难易度',
    private $grade                      = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '年级',
    private $subject                    = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '科目',
    private $version                    = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '教材版本',
    private $module                     = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '模块级别',
    private $question_type              = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '题型',
    private $del_status                 = null; // BOOLEAN DEFAULT FALSE COMMENT '逻辑删除状态,false:未删除, true:已删除',
    private $create_time                = null; // TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    private $update_time                = null; // TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',

    public function __construct($input)
    {
        $this->id                           = $input['id']                          ?? null;
        $this->uuid                         = $input['uuid']                        ?? null;
        $this->question_record_id           = $input['question_record_id']          ?? null;
        $this->question_content             = $input['question_content']            ?? null;
        $this->question_answer              = $input['question_answer']             ?? null;
        $this->question_analysis            = $input['question_analysis']           ?? null;
        $this->question_knowledge_point     = $input['question_knowledge_point']    ?? null;
        $this->question_question_point      = $input['question_question_point']     ?? null;
        $this->difficulty                   = $input['difficulty']                  ?? null;
        $this->grade                        = $input['grade']                       ?? null;
        $this->subject                      = $input['subject']                     ?? null;
        $this->version                      = $input['version']                     ?? null;
        $this->module                       = $input['module']                      ?? null;
        $this->question_type                = $input['question_type']               ?? null;
        $this->del_status                   = $input['del_status']                  ?? null;
        $this->create_time                  = $input['create_time']                 ?? null;
        $this->update_time                  = $input['update_time']                 ?? null;
    }

    public function toArray(){
        return [
            'id'                        => $this->id,
            'uuid'                      => $this->uuid,
            'question_record_id'        => $this->question_record_id,
            'question_content'          => $this->question_content,
            'question_answer'           => $this->question_answer,
            'question_analysis'         => $this->question_analysis,
            'question_knowledge_point'  => $this->question_knowledge_point,
            'question_question_point'   => $this->question_question_point,
            'difficulty'                => $this->difficulty,
            'grade'                     => $this->grade,
            'subject'                   => $this->subject,
            'version'                   => $this->version,
            'module'                    => $this->module,
            'question_type'             => $this->question_type,
            'del_status'                => $this->del_status,
            'create_time'               => $this->create_time,
            'update_time'               => $this->update_time,
        ];
    }

    public function getID()                         {return $this->id;}
    public function getUuid()                       {return $this->uuid;}
    public function getQuestionCreatorID()          {return $this->question_record_id; }
    public function getQuestionContent()            {return $this->question_content;}
    public function getQuestionAnswer()             {return $this->question_answer;}
    public function getQuestionAnalysis()           {return $this->question_analysis;}
    public function getQuestionKnowledgePoint()     {return $this->question_knowledge_point;}
    public function getQuestionQuestionPoint()      {return $this->question_question_point;}
    public function getDifficulty()                 {return $this->difficulty;}
    public function getGrade()                      {return $this->grade;}
    public function getSubject()                    {return $this->subject;}
    public function getVersion()                    {return $this->version;}
    public function getModule()                     {return $this->module;}
    public function getQuestionType()               {return $this->question_type;}
    public function getDelStatus()                  {return $this->del_status;}
    public function getCreateTime()                 {return $this->create_time;}
    public function getUpdateTime()                 {return $this->update_time;}

    public function setID($id)                                              {$this->id = $id;}
    public function setUuid($uuid)                                          {$this->uuid = $uuid;}
    public function setQuestionRecordID($question_record_id)                {$this->question_record_id  = $question_record_id; }
    public function setQuestionContent($question_content)                   {$this->question_content = $question_content;}
    public function setQuestionAnswer($question_answer)                     {$this->question_answer = $question_answer;}
    public function setQuestionAnalysis($question_analysis)                 {$this->question_analysis = $question_analysis;}
    public function setQuestionKnowledgePoint($question_knowledge_point)    {$this->question_knowledge_point = $question_knowledge_point;}
    public function setQuestionQuestionPoint($question_question_point)      {$this->question_question_point = $question_question_point;}
    public function setDifficulty($difficulty)                              {$this->difficulty = $difficulty;}
    public function setGrade($grade)                                        {$this->grade = $grade;}
    public function setSubject($subject)                                    {$this->subject = $subject;}
    public function setVersion($version)                                    {$this->version = $version;}
    public function setModule($module)                                      {$this->module = $module;}
    public function setQuestionType($question_type)                         {$this->question_type = $question_type;}
    public function setDelStatus($del_status)                               {$this->del_status = $del_status;}
    public function setCreateTime($create_time)                             {$this->create_time = $create_time;}
    public function setUpdateTime($update_time)                             {$this->update_time = $update_time;}
}
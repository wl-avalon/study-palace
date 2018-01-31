<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/30
 * Time: 下午10:29
 */

namespace app\models\beans;


class QuestionBean
{
    private $id                         = null; // BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '主键,自增ID',
    private $uuid                       = null; // VARCHAR(32) NOT NULL DEFAULT '' COMMENT '业务唯一ID',
    private $question_creator_id        = null; // VARCHAR(100) DEFAULT '' COMMENT '创建问题人的ID',
    private $question_remark            = null; // TEXT COMMENT '问题的附件',
    private $question_content           = null; // TEXT COMMENT '问题的正文',
    private $question_answer            = null; // TEXT COMMENT '问题的答案',
    private $question_analysis          = null; // TEXT COMMENT '问题的分析',
    private $question_knowledge_point   = null; // TEXT COMMENT '问题的知识点',
    private $question_question_point    = null; // TEXT COMMENT '问题的题点',
    private $question_difficulty        = null; // TINYINT(4) DEFAULT 0 COMMENT '问题难易度',
    private $grade                      = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '年级',
    private $subject                    = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '科目',
    private $teaching_material_version  = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '教材版本',
    private $module_level               = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '模块级别',
    private $del_status                 = null; // BOOLEAN DEFAULT FALSE COMMENT '逻辑删除状态,false:未删除, true:已删除',
    private $create_time                = null; // TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    private $update_time                = null; // TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',

    public function __construct($input)
    {
        $this->id                           = $input['id']                          ?? null;
        $this->uuid                         = $input['uuid']                        ?? null;
        $this->question_creator_id          = $input['question_creator_id']         ?? null;
        $this->question_remark              = $input['question_remark']             ?? null;
        $this->question_content             = $input['question_content']            ?? null;
        $this->question_answer              = $input['question_answer']             ?? null;
        $this->question_analysis            = $input['question_analysis']           ?? null;
        $this->question_knowledge_point     = $input['question_knowledge_point']    ?? null;
        $this->question_question_point      = $input['question_question_point']     ?? null;
        $this->question_difficulty          = $input['question_difficulty']         ?? null;
        $this->grade                        = $input['grade']                       ?? null;
        $this->subject                      = $input['subject']                     ?? null;
        $this->teaching_material_version    = $input['teaching_material_version']   ?? null;
        $this->module_level                 = $input['module_level']                ?? null;
        $this->del_status                   = $input['del_status']                  ?? null;
        $this->create_time                  = $input['create_time']                 ?? null;
        $this->update_time                  = $input['update_time']                 ?? null;
    }

    public function toArray(){
        return [
            'id'                        => $this->id,
            'uuid'                      => $this->uuid,
            'question_creator_id'       => $this->question_creator_id,
            'question_remark'           => $this->question_remark,
            'question_content'          => $this->question_content,
            'question_answer'           => $this->question_answer,
            'question_analysis'         => $this->question_analysis,
            'question_knowledge_point'  => $this->question_knowledge_point,
            'question_question_point'   => $this->question_question_point,
            'question_difficulty'       => $this->question_difficulty,
            'grade'                     => $this->grade,
            'subject'                   => $this->subject,
            'teaching_material_version' => $this->teaching_material_version,
            'module_level'              => $this->module_level,
            'del_status'                => $this->del_status,
            'create_time'               => $this->create_time,
            'update_time'               => $this->update_time,
        ];
    }

    public function getID()                     {return $this->id;}
    public function getUuid()                   {return $this->uuid;}
    public function getQuestionCreatorID()      {return $this->question_creator_id;}
    public function getQuestionRemark()         {return $this->question_remark;}
    public function getQuestionContent()        {return $this->question_content;}
    public function getQuestionAnswer()         {return $this->question_answer;}
    public function getQuestionAnalysis()       {return $this->question_analysis;}
    public function getQuestionKnowledgePoint() {return $this->question_knowledge_point;}
    public function getQuestionQuestionPoint()  {return $this->question_question_point;}
    public function getQuestionDifficulty()     {return $this->question_difficulty;}
    public function getGrade()                  {return $this->grade;}
    public function getSubject()                {return $this->subject;}
    public function getTeachingMaterialVersion(){return $this->teaching_material_version;}
    public function getModuleLevel()            {return $this->module_level;}
    public function getDelStatus()              {return $this->del_status;}
    public function getCreateTime()             {return $this->create_time;}
    public function getUpdateTime()             {return $this->update_time;}

    public function setID($id)                                              {$this->id = $id;}
    public function setUuid($uuid)                                          {$this->uuid = $uuid;}
    public function setQuestionCreatorID($question_creator_id)              {$this->question_creator_id = $question_creator_id;}
    public function setQuestionRemark($question_remark)                     {$this->question_remark = $question_remark;}
    public function setQuestionContent($question_content)                   {$this->question_content = $question_content;}
    public function setQuestionAnswer($question_answer)                     {$this->question_answer = $question_answer;}
    public function setQuestionAnalysis($question_analysis)                 {$this->question_analysis = $question_analysis;}
    public function setQuestionKnowledgePoint($question_knowledge_point)    {$this->question_knowledge_point = $question_knowledge_point;}
    public function setQuestionQuestionPoint($question_question_point)      {$this->question_question_point = $question_question_point;}
    public function setQuestionDifficulty($question_difficulty)             {$this->question_difficulty = $question_difficulty;}
    public function setGrade($grade)                                        {$this->grade = $grade;}
    public function setSubject($subject)                                    {$this->subject = $subject;}
    public function setTeachingMaterialVersion($teaching_material_version)  {$this->teaching_material_version = $teaching_material_version;}
    public function setModuleLevel($module_level)                           {$this->module_level = $module_level;}
    public function setDelStatus($del_status)                               {$this->del_status = $del_status;}
    public function setCreateTime($create_time)                             {$this->create_time = $create_time;}
    public function setUpdateTime($update_time)                             {$this->update_time = $update_time;}
}
<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/30
 * Time: 下午10:23
 */
namespace app\models\beans;

class NodeListBean
{
    private $id             = null; // BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '主键,自增ID',
    private $uuid           = null; // VARCHAR(40) NOT NULL DEFAULT '' COMMENT '唯一nodeID',
    private $grade          = null; // TINYINT(4) NOT NULL DEFAULT 0 COMMENT '学段',
    private $subject        = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '学科',
    private $version        = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '教材版本',
    private $module         = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '模块',
    private $node_key       = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '节点key',
    private $node_value     = null; // INT(11) NOT NULL DEFAULT 0 COMMENT '节点值',
    private $parent_node_id = null; // VARCHAR(40) NOT NULL DEFAULT '' COMMENT '父级节点ID',

    public function __construct($input)
    {
        $this->id               = $input['id']              ?? null;
        $this->uuid             = $input['uuid']            ?? null;
        $this->grade            = $input['grade']           ?? null;
        $this->subject          = $input['subject']         ?? null;
        $this->version          = $input['version']         ?? null;
        $this->module           = $input['module']          ?? null;
        $this->node_key         = $input['node_key']         ?? null;
        $this->node_value       = $input['node_value']       ?? null;
        $this->parent_node_id   = $input['parent_node_id']  ?? null;
    }

    public function toArray(){
        return [
            'id'                => $this->id,
            'uuid'              => $this->uuid,
            'grade'             => $this->grade,
            'subject'           => $this->subject,
            'version'           => $this->version,
            'module'            => $this->module,
            'node_key'          => $this->node_key,
            'node_value'        => $this->node_value,
            'parent_node_id'    => $this->parent_node_id,
        ];
    }

    public function getID()             {return $this->id;}
    public function getUuid()           {return $this->uuid;}
    public function getGrade()          {return $this->grade;}
    public function getSubject()        {return $this->subject;}
    public function getVersion()        {return $this->version;}
    public function getModule()         {return $this->module;}
    public function getNode_key()       {return $this->node_key;}
    public function getNode_value()     {return $this->node_value;}
    public function getParentNodeID()   {return $this->parent_node_id;}

    public function setID($id)                          {$this->id              = $id;}
    public function setUuid($uuid)                      {$this->uuid            = $uuid;}
    public function setGrade($grade)                    {$this->grade           = $grade;}
    public function setSubject($subject)                {$this->subject         = $subject;}
    public function setVersion($version)                {$this->version         = $version;}
    public function setModule($module)                  {$this->module          = $module;}
    public function setNode_key($node_key)              {$this->node_key        = $node_key;}
    public function setNode_value($node_value)          {$this->node_value      = $node_value;}
    public function setParentNodeID($parent_node_id)    {$this->parent_node_id  = $parent_node_id;}
}
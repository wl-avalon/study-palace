<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/30
 * Time: 下午10:23
 */
namespace app\models\beans;

class QuestConstBean
{
    private $id         = null;  // BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '主键,自增ID',
    private $tag_key    = null;  // BIGINT(20) DEFAULT 0 COMMENT 'tag-key',
    private $tag_value  = null;  // BIGINT(20) DEFAULT 0 COMMENT 'tag-value',

    public function __construct($input)
    {
        $this->id           = $input['id']          ?? null;
        $this->tag_key      = $input['tag_key']     ?? null;
        $this->tag_value    = $input['tag_value']   ?? null;
    }

    public function toArray(){
        return [
            'id'        => $this->id,
            'tag_key'   => $this->tag_key,
            'tag_value' => $this->tag_value,
        ];
    }

    public function getID()         {return $this->id;}
    public function getTagKey()     {return $this->tag_key;}
    public function getTagValue()   {return $this->tag_value;}

    public function setID($id)              {$this->id = $id;}
    public function setTagKey($tag_key)     {$this->tag_key = $tag_key;}
    public function setTagValue($tag_value) {$this->tag_value = $tag_value;}
}
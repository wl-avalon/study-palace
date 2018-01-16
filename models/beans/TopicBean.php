<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/15
 * Time: 下午9:50
 */
namespace app\models\beans;

class TopicBean
{
    private $id;
    private $p_id;
    private $name;

    public function __construct($topicBeanData)
    {
        $this->id   = $topicBeanData['id']      ?? null;
        $this->p_id = $topicBeanData['pId']     ?? null;
        $this->name = $topicBeanData['name']    ?? null;
    }

    public function toArray(){
        return [
            'id'    => $this->id,
            'pID'   => $this->p_id,
            'name'  => $this->name,
        ];
    }

    public function getID()
    {
        return $this->id;
    }

    public function getPID()
    {
        return $this->p_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPID($p_id)
    {
        $this->p_id = $p_id;
    }
}
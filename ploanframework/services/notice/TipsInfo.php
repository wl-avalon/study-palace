<?php

namespace ploanframework\services\notice;

class TipsInfo{
    /** @var string OPUI下发节点nodeID */
    private $tipsType;
    /** @var string 第三方全局唯一ID, 用于幂等,不要超过18位 */
    private $bizId;
    /** @var ExtData 扩展类型 */
    private $extData;

    public function __construct($tipsType, $bizId){
        $this->tipsType = $tipsType;
        $this->bizId = $bizId;
    }

    /**
     * @return mixed
     */
    public function getTipsType(){
        return $this->tipsType;
    }

    /**
     * @return mixed
     */
    public function getBizId(){
        return $this->bizId;
    }

    /**
     * @return mixed
     */
    public function getExtData(){
        return $this->extData;
    }

    /**
     * @param mixed $extData
     */
    public function setExtData($extData){
        $this->extData = $extData;
    }

    public function toArray(){
        $arr = [
            'tipsType' => $this->tipsType,
            'bizId'    => $this->bizId,
        ];

        if(empty($this->extData)){
            $arr['extData'] = (object)[];
        }else{
            $arr['extData'] = $this->extData->toArray();
        }

        return $arr;
    }
}
<?php

namespace ploanframework\services\notice;

class BizInfo{
    /** @var string type */
    private $type;
    /** @var ExtData 扩展类型 */
    private $extData;

    public function __construct($type, ExtData $extData){
        $this->type = $type;
        $this->extData = $extData;
    }

    /**
     * @return string
     */
    public function getType(): string{
        return $this->type;
    }

    /**
     * @return ExtData
     */
    public function getExtData(): ExtData{
        return $this->extData;
    }

    public function toArray(){
        return [
            'type'    => $this->type,
            'extData' => $this->extData->toArray(),
        ];
    }
}
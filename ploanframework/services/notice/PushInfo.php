<?php

namespace ploanframework\services\notice;

class PushInfo{
    /** @var string 类型 1交易推送信息, 线下审核 2好友推送信息。3订阅推送信息 6系统消息 7交易消息 8催收消息 9在线客服消息 10静音免打扰（暂不支持） 100开关免疫 */
    private $type;
    /** @var string 标题 */
    private $title;
    /** @var string 内容 */
    private $content;
    /** @var string 描述 */
    private $description;
    /** @var ExtData 扩展类型 */
    private $extData;

    public function __construct($type, $title, $content, $description = '', ExtData $extData = null){
        $this->type = $type;
        $this->title = $title;
        $this->content = $content;
        $this->description = $description;
        $this->extData = $extData;
    }

    /**
     * @return string
     */
    public function getType(): string{
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle(): string{
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string{
        return $this->content;
    }

    /**
     * @return string
     */
    public function getDescription(): string{
        return $this->description;
    }

    /**
     * @return ExtData
     */
    public function getExtData(): ExtData{
        return $this->extData;
    }

    public function toArray(){
        $arr = [
            'type'        => $this->type,
            'title'       => $this->title,
            'content'     => $this->content,
            'description' => $this->description,
        ];

        if(empty($this->extData)){
            $arr['extData'] = (object)[];
        }else{
            $arr['extData'] = $this->extData->toArray();
        }

        return $arr;
    }
}
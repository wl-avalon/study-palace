<?php

namespace ploanframework\services\notice;

class Secretary{
    /** @var array 追加字段 */
    private $appendField;
    /** @var string 小秘书内容，200字以内 */
    private $content;
    /** @var ExtData 扩展字段 */
    private $extData;
    /** @var string 小秘书图标URL */
    private $iconUrl;
    /** @var string 小秘书点击跳转URL */
    private $messageUrl;
    /** @var string 小秘书标题 */
    private $title;
    /** @var string 唯一标识一条小秘书。用于幂等,不要超过18位 */
    private $bizId;
    /** @var string 1系统消息，2交易消息，3催收消息，可以默认系统消息 1，如选择2、3请提前联系系统负责人 */
    private $type;

    public function __construct($type, $bizId, $title, $content, $iconUrl, $messageUrl = ''){
        $this->type = $type;
        $this->bizId = $bizId;
        $this->title = $title;
        $this->content = $content;
        $this->messageUrl = $messageUrl;
        $this->iconUrl = $iconUrl;
        $this->appendField = [];
    }

    /**
     * @return array
     */
    public function getAppendField(): array{
        return $this->appendField;
    }

    /**
     * @param array $appendField
     */
    public function setAppendField(array $appendField){
        $this->appendField = $appendField;
    }

    /**
     * @return string
     */
    public function getContent(): string{
        return $this->content;
    }

    /**
     * @return ExtData
     */
    public function getExtData(): ExtData{
        return $this->extData;
    }

    /**
     * @param ExtData $extData
     */
    public function setExtData(ExtData $extData){
        $this->extData = $extData;
    }

    /**
     * @return string
     */
    public function getIconUrl(): string{
        return $this->iconUrl;
    }

    /**
     * @return string
     */
    public function getMessageUrl(): string{
        return $this->messageUrl;
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
    public function getBizId(): string{
        return $this->bizId;
    }

    /**
     * @return string
     */
    public function getType(): string{
        return $this->type;
    }

    public function toArray(){
        $arr = [
            'type'       => $this->type,
            'bizId'      => $this->bizId,
            'title'      => $this->title,
            'messageUrl' => $this->messageUrl,
            'iconUrl'    => $this->iconUrl,
            'content'    => $this->content,
        ];

        if(empty($this->extData) || $this->extData == null){
            $arr['extData'] = (object)[];
        }else{
            $arr['extData'] = $this->extData->toArray();
        }

        $arr['appendField'] = $this->appendField;

        return $arr;
    }
}
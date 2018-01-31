<?php

namespace ploanframework\services\notice;

class ExtData{
    /** @var string 链接地址 */
    private $url;
    /** @var string subType 参考 app push跳转类型定义 */
    private $subtype;
    /** @var string 数量 */
    private $count;

    private $ext;

    public function __construct($url, $subType){
        $this->url = $url;
        $this->subtype = $subType;
    }

    /**
     * @return string
     */
    public function getUrl(): string{
        return $this->url;
    }

    /**
     * @return string
     */
    public function getSubtype(): string{
        return $this->subtype;
    }

    /**
     * @return string
     */
    public function getCount(): string{
        return $this->count;
    }

    /**
     * @param string $count
     */
    public function setCount(string $count){
        $this->count = $count;
    }

    public function setExt($ext){
        if(empty($this->ext)){
            $this->ext = $ext;
        }else{
            $this->ext = array_merge($this->ext, $ext);
        }
    }

    public function toArray(){
        $arr = [
            'url'     => $this->url,
            'subtype' => $this->subtype,
        ];

        if(isset($this->count)){
            $arr['count'] = $this->count;
        }

        if(isset($this->ext)){
            $arr = array_merge($arr, $this->ext);
        }

        return $arr;
    }
}
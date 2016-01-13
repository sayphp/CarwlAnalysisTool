<?php
    /*
     * 文件父类,所有类型文本的基类
     * Say
     * 2016-01-11
     */
    class fileCls{
        
        public $content;//输入内容
        
        public $data = [
            'title' => '',//标题
            'author' => '',//作者
            'content' => '',//内容
        ];//返回结果数据
        
        //构造
        public function __construct($content = null){
            if(isset($content)){
                $this->content = $content;
            }
        }
        
        //设置返回结果
        public function set($key, $val){
            $this->data[$key] = $val;
            return true;
        }
        
    }


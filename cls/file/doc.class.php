<?php
    /*
     * word文档，百度文库解析程序
     * Say
     * 2016-01-12
     */
    class docCls extends fileCls{
        
        //获取标题
        public function title(){
            $preg = "/\<h1\>(.*)\<\/h1\>/";
            var_dump($this->content);
            preg_match_all($preg, $this->content, $matches);
            var_dump($matches);
            return $matches;
        }
        
        //是否继续
        public function next(){
            
        }
    }
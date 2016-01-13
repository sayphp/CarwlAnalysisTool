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
            preg_match($preg, $this->content, $matches);
            return $matches[1];
        }
        
        //获取作者
        public function author(){
            $preg = "/\<p class\=\"uploader\"\>(.*?)\<\/p\>/i";
            preg_match($preg, $this->content, $matches);
            return $matches[1];
        }
        
        //获取内容
        public function content(){
            $preg = "/\<p\ class=\"txt\">([\w\W]*?)\<\/p\>/i";
            preg_match_all($preg, $this->content, $matches);
//            var_dump($this->content);
//            var_dump($matches);
            $content = '';
            if($matches[1]){
                foreach($matches[1] as $v){
                    if(trim($v)!='') $content .= $v."\r\n\r\n";
                }
            }
            return $content;
        }
        
        //是否继续
        public function next(){
            
        }
    }
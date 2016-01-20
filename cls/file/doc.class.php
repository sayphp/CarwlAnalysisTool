<?php
    /*
     * word文档，百度文库解析程序
     * Say
     * 2016-01-12
     */
    class docCls extends fileCls{
        
        public function __construct($id){
            parent::__construct($id);
            $this->set('title', $this->title());
            $this->set('author', $this->author());
        }
        
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
        public function content($id, $next=1){
            //选定文本内容
            $content = '';
            if($next){
                if($next > 1){
                    $this->content = $this->view($id.'?pn='.$next.'&pu=');
                }
                $preg = "/\<div class=\"content.*?\"\>([\w\W]+?)\<\/div\>/";
                preg_match($preg, $this->content, $matches);
                if($matches[1]){
                    $content .= $matches[1];//当前
                    $content .= $this->content($id,$this->next());//下一页
                }
            }
            $preg = "/\<p\ class=\"img\">[\w\W]*?\<a href=\"(.*?)\"[\w\W]*?\<\/p\>/i";
            preg_match_all($preg, $content, $matches);
            if($matches[1]){
                $url = $matches[1][0];
                $this->img($url);
            }
            return $content;
        }
        
        //*过滤内容
        public function filter($content){
            $preg = "/\<p class\=\"txt\"\>(.*?)\<\/p\>/i";//文字
            $replace = "$1";
            $content = preg_replace($preg, $replace, $content);
            $preg = "/\<p class\=\"img\"\>[\w\W]*?\/(\w*?)\?[\w\W]*?\<\/p\>/i";//图片
            $replace = "![](img/$1.jpg)";
            $content = preg_replace($preg, $replace, $content);
            $preg = "/\<p class\=\"page\"\>[\w\W]*?\<\/p\>/i";//分页
            $replace = "";
            $content = preg_replace($preg, $replace, $content);
            //清楚多余空格
            $data = explode("\n", $content);
            foreach($data as $k => $v){
                if(trim($v)==''){
                    unset($data[$k]);
                }else{
                    $data[$k] = trim($v);
                }
            }
            return implode("\r\n\r\n", $data);
        }
        //是否继续
        public function next(){
            $preg = "/\<p class\=\"page\"\>.*?(\d+?).*?(\d+?).*?\<\/p\>/i";
            preg_match($preg, $this->content, $matches);
            if($matches[1] && $matches[2] && $matches[1] < $matches[2]) return $matches[1]+1;
            return false;
        }
    }
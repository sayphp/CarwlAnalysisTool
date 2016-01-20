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
        public function __construct($id){
            $this->content = $this->view($id);
        }
        
        //设置返回结果
        public function set($key, $val){
            $this->data[$key] = $val;
            return true;
        }
        
        //获取页面
        public function view($id){
            httpCls::$response = false;
            httpCls::set('host', crawlConf::$host);
            httpCls::set('uri', $id);
            httpCls::set('agent', crawlConf::$browser[0]['agent']);
            httpCls::set('accept', crawlConf::$browser[0]['accept']);
            httpCls::set('cookie', crawlConf::$browser[0]['cookie']);
            $rs = httpCls::send();
            echo '获取页面'.PHP_EOL;
            //返回内容
            //httpCls::$response = (httpCls::unchunk2preg(httpCls::$response));
            return httpCls::$response;
        }
        
        //获取图片
        public function img($url){
            httpCls::$response = '';
            $info = parse_url($url);
            httpCls::set('host', $info['host']);
            httpCls::set('uri', $info['path'].'?'.$info['query']);
            httpCls::set('agent', crawlConf::$browser[0]['agent']);
            httpCls::set('accept', crawlConf::$browser[0]['accept']);
            httpCls::set('cookie', crawlConf::$browser[0]['cookie']);
            httpCls::set('timeout', 120);
            $rs = httpCls::down();
            $content = httpCls::$response;
            $file = pathinfo($info['path']);
            $data = explode("\r\n\r\n", $content);
            file_put_contents(ROOT.'img/'.$file['filename'].'.jpg', $data[1]);
            return true;
        }
        
    }


<?php
    /*
     * 抓取类文件
     * Say
     * 2016-01-10
     */
    class crawlCls{
        
        //根据关键词抓取分析页面
        public function search($key){
            httpCls::set('host', crawlConf::$host);
            httpCls::set('uri', crawlConf::$search.'?word='.urlencode($key).'&ssid=&lc=&from=&bd_page_type=&uid=&pu=&st=&wk=');
            httpCls::set('agent', crawlConf::$browser[0]['agent']);
            httpCls::set('accept', crawlConf::$browser[0]['accept']);
            httpCls::set('cookie', crawlConf::$browser[0]['cookie']);
            httpCls::send();
            //返回内容
            $content = httpCls::$response;
            //*获取list
//            var_dump($content);
            $preg = "/\<p\><a href=\"(.*)?\?ssid\=(.*)?\"\>.*?\.(\w*?)\<\/a\>/i";
            preg_match_all($preg, $content, $matches);
            $data = false;
            if($matches){
                foreach($matches[1] as $k => $id){
                    $data[$id] = $matches[3][$k];
                }
            }
            return $data;
        }
        
        //*查询详情页面
        public function view($id){
            httpCls::set('host', crawlConf::$host);
            httpCls::set('uri', $id);
            httpCls::set('agent', crawlConf::$browser[0]['agent']);
            httpCls::set('accept', crawlConf::$browser[0]['accept']);
            httpCls::set('cookie', crawlConf::$browser[0]['cookie']);
            $rs = httpCls::send();
            //返回内容
            self::log('header',httpCls::$request);
            
            $content = $rs?httpCls::$response:'';
            return $content;
        }
        
        //Doc文件解析
        public static function doc($content){
            $doc = new docCls($content);
            $content = $doc->title();
            return $content;
        }
        
        //PDF文件解析
        public static function pdf($content){
            return $content;
        }
        
        //PPT文件解析
        public static function ppt($content){
            return $content;
        }
        
        //TXT文件解析
        public static function txt($content){
            return $content;
        }
        
        //日志
        public static function log($type='default', $content=''){
            return file_put_contents(ROOT.'log'.DIRECTORY_SEPARATOR.$type.'.log', date('Y-m-d H:i:s').PHP_EOL.$content.PHP_EOL);
        }
        
        
    }


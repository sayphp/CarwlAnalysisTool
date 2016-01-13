<?php
    /*
     * 抓取类文件
     * Say
     * 2016-01-10
     */
    class crawlCls{
        
        public static $key = 'default';//搜索关键词
        
        //根据关键词抓取分析页面
        public static function search($key){
            self::$key = $key;
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
        public static function view($id){
            httpCls::set('host', crawlConf::$host);
            httpCls::set('uri', $id);
            httpCls::set('agent', crawlConf::$browser[0]['agent']);
            httpCls::set('accept', crawlConf::$browser[0]['accept']);
            httpCls::set('cookie', crawlConf::$browser[0]['cookie']);
            $rs = httpCls::send();
            //返回内容
            self::log('header',httpCls::$request);
            $content = $rs?httpCls::$response:false;
            return $content;
        }
        
        //Doc文件解析
        public static function doc($content){
            $doc = new docCls($content);
            $doc->set('title', $doc->title());
            $doc->set('author', $doc->author());
            $doc->set('content', $doc->content());
            return $doc->data;
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
        
        //保存文件
        public static function save($data){
            $path = ROOT.'data'.DIRECTORY_SEPARATOR.self::$key;
            if(!file_exists($path)) mkdir ($path);
            return file_put_contents(ROOT.'data'.DIRECTORY_SEPARATOR.self::$key.DIRECTORY_SEPARATOR.$data['title'].'.md', $data['content']);
        }
    }


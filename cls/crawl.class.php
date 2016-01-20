<?php
    /*
     * 抓取类文件
     * Say
     * 2016-01-10
     */
    class crawlCls{
        
        public static $key = 'default';//搜索关键词
        
        public static $id;//资源地址
        
        //根据关键词抓取分析页面
        public static function search($key, $next=1){
            echo '抓取页面'.PHP_EOL;
            self::$key = $key;
            httpCls::$response = '';
            httpCls::set('host', crawlConf::$host);
            httpCls::set('uri', crawlConf::$search.'?word='.urlencode($key).'&pn='.$next.'&ssid=&lc=&from=&bd_page_type=&uid=&pu=&st=&wk=');
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
            $data = array();
            if($matches){
                foreach($matches[1] as $k => $id){
                    $data[$id] = $matches[3][$k];
                }
            }
            self::log('data', var_export($data,1));
            $next = self::next($content);
            if($next){
                $data = array_merge($data, self::search($key, $next));
            }
            return $data;
        }
        
        //是否下一页
        public static function next($content){
            $preg = "/\<td align\=\"right\"\>\s*?第(\d*?)\/(\d*?)页/i";
            preg_match($preg, $content, $match);
            if($match){
                if($match[1]<$match[2]) return intval($match[1])+1;
            }
            return false;
        }
        
        //*查询详情页面
        public static function view($id){
            httpCls::$response = false;
            httpCls::set('host', crawlConf::$host);
            httpCls::set('uri', $id);
            httpCls::set('agent', crawlConf::$browser[0]['agent']);
            httpCls::set('accept', crawlConf::$browser[0]['accept']);
            httpCls::set('cookie', crawlConf::$browser[0]['cookie']);
            $rs = httpCls::send();
            //返回内容
            //httpCls::$response = (httpCls::unchunk2preg(httpCls::$response));
            return httpCls::$response;
        }
        
        //Doc文件解析
        public static function doc($id){
            $doc = new docCls($id);
            $content = $doc->content($id);
            $content = $doc->filter($content);
            $doc->set('content', $content);
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
            return file_put_contents(ROOT.'log'.DIRECTORY_SEPARATOR.$type.'.log', date('Y-m-d H:i:s').PHP_EOL.$content.PHP_EOL, FILE_APPEND);
        }
        
        //保存文件
        public static function save($data){
            $path = ROOT.'data'.DIRECTORY_SEPARATOR.self::$key;
            if(!file_exists($path)) mkdir ($path);
            $file = $path.DIRECTORY_SEPARATOR.$data['title'].'.md';
            $repeat = self::repeat($file, $data);
            if($repeat==0){
                self::md5($data['content']);
                return file_put_contents($file, $data['content']);
            }elseif($repeat==1){
                self::md5($data['content']);
                return file_put_contents($path.DIRECTORY_SEPARATOR.$data['title'].date('YmdHis').'.md', $data['content']);
            }else{
                self::log('repeat', $file.'文件重复');
                return true;
            }
        }
        
        //检查文件是否重复存在，内容是否一致
        public static function repeat($file, $data){
            if(file_exists($file)){
                $content = file_get_contents(ROOT.'data'.DIRECTORY_SEPARATOR.'md5.md');
                $list = explode("\r\n", $content);
                if(in_array(md5($data['content']),$list)) return 1;//同文件
                return 2;//同名
            }
            return 0;//不重复
        }
        
        //md5记录
        public static function md5($content=''){
            return file_put_contents(ROOT.'data'.DIRECTORY_SEPARATOR.'md5.md', md5($content).PHP_EOL, FILE_APPEND);
        }
    }


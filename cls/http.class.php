<?php
    /*
     * http请求类
     */
    class httpCls{
        
        public static $host = false;//服务器
        
        public static $uri = '';//资源
        
        public static $port = 80;//端口
        
        public static $agent = '';//客户端
        
        public static $cookie = '';//Cookie
        
        public static $referer = '';//来源
        
        public static $length = 0;//请求长度
        
        public static $timeout = 10;//超时时长
        
        public static $method = 1;//请求方式
        
        public static $accept = ['', '', ''];//允许内容
        
        public static $request;//请求
        
        public static $response;//返回
        
        public static $errno;//错误码
        
        public static $errstr;//错误信息
        
        public static $body;//请求主体
        
        public static $content = 1024;//返回内容
        
        //设定参数值
        public static function set($key=null, $val=null){
            if(isset($key)){
                self::$$key = $val;
                return true;
            }
            return false;
        }
        
        //发送请求
        public static function send(){
            //分片传输BUG处理，3种方式：1.正则、2.16进制 3.改用HTTP 1.0
            if(self::$host){
                $fp = fsockopen(self::$host, self::$port, self::$errno, self::$errstr, self::$timeout);
                self::$request = self::method(self::$method)." ".self::$uri." HTTP/1.0\r\n";//请求方式 资源 协议
                self::$request .= "Host: ".self::$host."\r\n";//服务器
                self::$request .= "User-agent:".self::$agent."\r\n";//客户端
                foreach(self::$accept as $k => $v){
                    self::$request .= self::accept($k, $v)."\r\n";
                }
                if(self::$cookie!=''){
                    self::$request .= "Cookie: ".self::$cookie."\r\n";
                }
                //POST的情况下
                $post = self::$body."\r\n";
                if(self::$method==2){
                    self::$request .= "Content-Length: ".strlen($post)."\r\n";
                }
                self::$request .= "Referer: http://".self::$host.self::$uri."\r\n";
                self::$request .= "Connection: keep-alive\r\n";
                self::$request .= "\r\n";
                if(self::$method==2){
                    self::$request .= $post;
                }
                if($fp){
                    fwrite($fp, self::$request);//写入内容
                    $str = '';
                    while(!feof($fp)){
                        //$str = stream_get_line($fp, 1024, "\r\n");
                        $str = fgets($fp, 1024);
                        self::$response .= $str;
                    }
                    fclose($fp);
                    return true;
                }
                
            }
            return false;
        }
        
        //下载请求
        public static function down(){
            if(self::$host){
                $fp = fsockopen(self::$host, self::$port, self::$errno, self::$errstr, self::$timeout);
                self::$request = self::method(self::$method)." ".self::$uri." HTTP/1.0\r\n";//请求方式 资源 协议
                self::$request .= "Host: ".self::$host."\r\n";//服务器
                self::$request .= "User-agent:".self::$agent."\r\n";//客户端
                foreach(self::$accept as $k => $v){
                    self::$request .= self::accept($k, $v)."\r\n";
                }
                if(self::$cookie!=''){
                    self::$request .= "Cookie: ".self::$cookie."\r\n";
                }
                //POST的情况下
                $post = self::$body."\r\n";
                if(self::$method==2){
                    self::$request .= "Content-Length: ".strlen($post)."\r\n";
                }
                self::$request .= "Referer: http://".self::$host.self::$uri."\r\n";
                self::$request .= "Connection: close\r\n";
                self::$request .= "\r\n";
                if(self::$method==2){
                    self::$request .= $post;
                }
                echo '下载图片'.PHP_EOL;
                if($fp){
//                    $fi = fopen(ROOT.'img/'.time().'.png', 'w');
                    fwrite($fp, self::$request);//写入内容
                    $str = '';
                    while(!feof($fp)){
                        $str = fgets($fp, 1024);
//                        echo $str;
                        self::$response .= $str;
                    }
//                    fclose($fi);
                    fclose($fp);
                    return true;
                }
            }
            return false;
        }
        //获取请求方法
        public static function method($type=1){
            switch($type){
                case 2:
                    $method = 'POST';
                    break;
                case 3:
                    $method = 'PUT';
                    break;
                case 4:
                    $method = 'DELETE';
                    break;
                case 1:
                default:
                    $method = 'GET';
            }
            return $method;
        }
        
        //获取允许信息
        public static function accept($k=0, $v=''){
            $accept = '';
            switch($k){
                case 0: case '0': case 'accept':
                    $accept = 'Accept: '.($v==''?'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8':$v);
                    break;
                case 1: case '1': case 'accept-language':
                    $accept = 'Accept-Language: '.($v==''?'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3':$v);
                    break;
                case 2: case '2': case 'accept-encoding':
                    $accept = 'Accept-Encoding: '.($v==''?'':$v);
                    break;
            }
            return $accept;
        }
    }


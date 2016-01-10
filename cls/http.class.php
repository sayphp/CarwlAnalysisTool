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
        
        public static $length = 0;//请求长度
        
        public static $method = 1;//请求方式
        
        public static $accept = '';//允许内容
        
        public static $request;//请求
        
        public static $response;//返回
        
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
            $fp = fsockopen($host, 80, $errno, $errstr, 10);
            
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
    }


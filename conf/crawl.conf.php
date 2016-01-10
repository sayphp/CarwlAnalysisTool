<?php
    /*
     * 抓取配置类
     * Say
     * 2016-01-10
     */
    class crawlConf{
        
        public static $host = 'http://wk.baidu.com/';//服务器
        
        public static $search = 'search';//搜索URI
        
        public static $view = 'view/';//文件URI
        
        public static $browser = [
            [
                'agent' => 'Mozilla/5.0 (Android; Mobile; rv:22.0) Gecko/22.0 Firefox/22.0',//浏览器
                'cookie' => '',//Cookie
                'accept' => "",//允许内容
            ],
        ];
    }


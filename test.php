<?php
    //载入文件
    define('ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
    $conf = array(
        ROOT.'conf'.DIRECTORY_SEPARATOR.'*\.conf\.php',
        ROOT.'cls'.DIRECTORY_SEPARATOR.'*\.class\.php',
        ROOT.'cls'.DIRECTORY_SEPARATOR.'file'.DIRECTORY_SEPARATOR.'*\.class\.php',
    );
    $list = glob('{'.implode(',', $conf).'}', GLOB_BRACE);
    foreach($list as $file){
        require $file;
    }
    $key = '语文';
    $list = crawlCls::search($key);
    foreach($list as $id => $type){
        $content = crawlCls::view($id);
        if($content){
            switch($type){//根据类型分析内容
                case 'doc':
                    $data = crawlCls::doc($content);
                    crawlCls::save($data);
                    break;
                case 'pdf':
                    crawlCls::pdf($content);
    //                break;
                case 'ppt':
                    crawlCls::ppt($content);
    //                break;
                case 'txt':
                    crawlCls::txt($content);
    //                break;
                default:
                    crawlCls::log('type', $type.'的文件不可解析');
            }
        }else{
            crawlCls::log('view', $id.'的页面打开失败');
        }
        break;
    }
    echo json_encode($data);

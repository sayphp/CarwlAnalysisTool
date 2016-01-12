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
    
    $list = crawlCls::search('语文');
    foreach($list as $id => $type){
        $content = crawlCls::view($id);
        switch($type){//根据类型分析内容
            case 'doc':
                crawlCls::doc($content);
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
        break;
    }

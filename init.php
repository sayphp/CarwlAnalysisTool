<?php
    //载入文件
    ini_set('memory_limit', '1024M');
    
    $conf = array(
        ROOT.'conf'.DIRECTORY_SEPARATOR.'*\.conf\.php',
        ROOT.'cls'.DIRECTORY_SEPARATOR.'*\.class\.php',
        ROOT.'cls'.DIRECTORY_SEPARATOR.'file'.DIRECTORY_SEPARATOR.'*\.class\.php',
    );
    $list = glob('{'.implode(',', $conf).'}', GLOB_BRACE);
    foreach($list as $file){
        require $file;
    }


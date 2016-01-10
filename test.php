<?php
    require 'cls/http.class.php';
    $http = httpCls::set('uri', "123");
    echo json_encode(httpCls::$uri);

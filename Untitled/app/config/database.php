<?php
    //DB Params
    define('DB_HOST', 'localhost:3306');
    define('DB_USER', 'hassan');
    define('DB_PASS', 'hsf');
    define('DB_NAME', 'Camagru');

    //approot
    define('APPROOT',dirname(dirname(__FILE__)));
    define('APPROOT1',dirname(dirname(dirname(__FILE__)))."/"."public/img/");
    define('APPROOT2',dirname(dirname(dirname(__FILE__)))."/"."public/stickers/");
    define('APPROOT3',dirname(dirname(dirname(__FILE__)))."/"."public/emoji/");
    //urlroot
    
    define('URLROOT','http://'.trim(shell_exec('hostname -I')).'/Camagru/');
    //sitename
    define('SITENAME','Camagru');
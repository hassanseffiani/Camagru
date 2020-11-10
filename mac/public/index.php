<?php
    shell_exec('sudo -S chmod 777 ./img 2>&1');
    require_once "../app/all.php";

    $test = new Core;
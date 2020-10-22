<?php
    function redirect($page){
        header('Location: ' . URLROOT . $page);
    }

    function is_login_in(){
        if (isset($_SESSION['user_id']))
            return TRUE;
        else
            return FALSE;
      }
      
      function verify($to , $message){
        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($message, 70, "\r\n");
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        // Send - replace email@domain.com with the recipient address
        $bool = mail($to, 'A new Camagru msg :', $message, $headers);
      }

      function dlt_img_path(){
        $file = APPROOT1.'*';
        $files = glob($file); //get all file names
        foreach($files as $file){
            if(is_file($file))
            unlink($file); //delete file
        }
      }
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
      
    //   function verify(){
    //     $to = "royam96404@eliteseo.net";
    //     $subject = "Confirmation Email";

    //     $message = "</html>";

    //     $headers = "MIME-Version: 1.0" . "\r\n";
    //     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    //     $headers .= 'From: <camagru.1337@gmail.com>' . "\r\n";
    //     $headers .= 'Reply-To: <camagru.1337@gmail.com>' . "\r\n";

    //     mail($to,$subject,$message,$headers);

    //   }

      function dlt_img_path(){
        $file = APPROOT1.'*';
        $files = glob($file); //get all file names
        foreach($files as $file){
            if(is_file($file))
            unlink($file); //delete file
        }
      }
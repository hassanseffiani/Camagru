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

      function get_all_stickers(){
        $file = APPROOT2.'*';
        $files = glob($file); //get all file names
        $arr = [];
        foreach($files as $file)
          array_push($arr, (base64_encode(file_get_contents($file))));
        return ($arr);
      }

      //to merge and copy image with base 64

      function merge_64($i1, $i2){
        $img1 = base64_decode($i1);
        $img2 = base64_decode($i2);
        list($width, $height) = getimgstring($img1);
        $img1 = imagecreatefromstring($img1);
        $img2 = imagecreatefromstring($img2);

        // Copy and merge 
        imagecopy($img1, $img2, $width / 2.5, $height / 2.5, 0, 0, $width / 6.6, $height / 5);
        ob_start();
        imagepng($img1);
        $bin = ob_get_clean();
        $b64 = base64_encode($bin);
        return $b64;
      }


      function getimgstring($data)
      {
         $uri = 'data://application/octet-stream;base64,'  . base64_encode($data);
         return getimagesize($uri);
      }
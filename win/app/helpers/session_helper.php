<?php
  session_start();
  function flash($name = '', $message = ''){
    if(!empty($name)){
      if(!empty($message) && empty($_SESSION[$name])){
        if(!empty($_SESSION[$name])){
          unset($_SESSION[$name]);
        }

        $_SESSION[$name] = $message;
      } elseif(empty($message) && !empty($_SESSION[$name])){
        echo '<span style="color: green;">'.$_SESSION[$name].'</span>';
        unset($_SESSION[$name]);
      }
    }
  }
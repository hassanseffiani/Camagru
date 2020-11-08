<?php

/*
  Core to set mvc for our application .... next ... set file Database ... link controller with model and view
*/

   // Url : - /controller/method/params
  class Core {
    protected $currentController = 'Posts';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
      $url = $this->getUrl();
      if(file_exists('../app/controllers/' . ucwords($url[0]). '.php')){
        $this->currentController = ucwords($url[0]);
        unset($url[0]);
      }

      require_once '../app/controllers/'. $this->currentController . '.php';

      $this->currentController = new $this->currentController;

      // Check for second part of url
      if(isset($url[1])){
        if(method_exists($this->currentController, $url[1])){
          $this->currentMethod = $url[1];
          unset($url[1]);
        }
      }

      // Get params
      $this->params = $url ? array_values($url) : [];

      // Call a callback with array of params
      call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
      if(isset($_GET['url'])){
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
      }
    }
  }
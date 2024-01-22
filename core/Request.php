<?php

namespace app\core;

class Request 
{


    public function getPath() {

        $path = $_SERVER['REQUEST_URI'] ?? "/";
        $position = strpos($path, "?");

        if($position === false){
            return $path;
        }

        return substr($path, 0, $position);
    }

    
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isGet()
    {
        return $this->method() == 'GET';
    }

    public function isPost()
    {
        return $this->method() == 'POST';
    }

    
    public function getBody()
    {

        $body = [];

        if($this->method() == 'GET'){
            foreach($_GET as $key => $value){
                $body[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if($this->method() == 'POST'){
            foreach($_POST as $key => $value){
                $body[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
    
}

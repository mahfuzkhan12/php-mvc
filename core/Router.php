<?php

namespace app\core;

class Router 
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }



    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $callback = $this->routes[$method][$path] ?? false;

        if(!$callback){
            $this->response->setStatusCode(404);
            return "NOT FOUND";
        }

        if(is_string($callback)){
            return $this->renderView($callback);
        }

        return call_user_func($callback);
    }



    public function renderView($view) 
    {
        $layouteContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);
        echo str_replace("{{content}}", $viewContent, $layouteContent);
    }


    protected function layoutContent()
    {
        ob_start();

        include_once Application::$ROOT_DIR . "/views/layouts/main.php";

        return ob_get_clean();
        
        // ob_start() allow not to return anything in the browser
        // ob_get_clean() allow to return the file contentes without returning to the browser
    }

    protected function renderOnlyView($view)
    {
        ob_start();

        include_once Application::$ROOT_DIR . "/views/$view.php";

        return ob_get_clean();
    }

}

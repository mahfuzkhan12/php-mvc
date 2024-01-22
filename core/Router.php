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

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }


    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();

        $callback = $this->routes[$method][$path] ?? false;

        if(!$callback){
            $this->response->setStatusCode(404);
            echo $this->renderView("_404");
            exit;
        }

        if(is_string($callback)){
            return $this->renderView($callback);
        }


        if(is_array($callback)){
            Application::$app->controller = new $callback[0];
            $callback[0] = Application::$app->controller;
        }

        return call_user_func($callback, $this->request);
    }



    public function renderView($view, $params = []) 
    {
        $layouteContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        echo str_replace("{{content}}", $viewContent, $layouteContent);
        exit;
    }

    public function renderContent($viewContent) 
    {
        $layouteContent = $this->layoutContent();
        echo str_replace("{{content}}", $viewContent, $layouteContent);
        exit;
    }



    protected function layoutContent()
    {
        $layout = Application::$app->controller->layout;

        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
        
        // ob_start() allow not to return anything in the browser
        // ob_get_clean() allow to return the file contentes without returning to the browser
    }

    protected function renderOnlyView($view, $params = [])
    {

        foreach($params as $key => $value){
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }

}

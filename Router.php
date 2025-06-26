<?php

namespace app;

use app\controllers\ProductController;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Router
{

    public array $getRoutes = [];
    public array $postRoutes = [];
    private $twig;
    public Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function get($url, $function)
    {
        $this->getRoutes[$url] = $function;
    }

    public function post($url, $function)
    {
        $this->postRoutes[$url] = $function; 
    }

    public function resolve()
    {
        $currentUrl = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if( $method === 'GET')
        {
            $func = $this->getRoutes[$currentUrl] ?? null;
        }else{
            $func = $this->postRoutes[$currentUrl] ?? null;
        }

        if($func)
        {
           [$controller, $method] = $func;
           $controller = new $controller();
           $controller->$method($this);
        }else
        {
            echo "Page not found";
        }
    }

    public function renderView($view, $params = [])
    {
        $loader = new FileSystemLoader(__DIR__ . '/views');
        $this->twig = new Environment($loader);

        return $this->twig->render($view, $params);
    }
    
}
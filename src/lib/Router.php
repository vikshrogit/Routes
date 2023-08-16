<?php 
namespace Ramphp\Router\lib;

class Router
{
    protected static function get($app_route, $app_callback): void
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
            return;
        }

        self::on($app_route, $app_callback);
    }

    protected static function post($app_route, $app_callback): void
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
            return;
        }

        self::on($app_route, $app_callback);
    }

    protected static function put($app_route, $app_callback): void
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') !== 0) {
            return;
        }

        self::on($app_route, $app_callback);
    }

    protected static function del($app_route, $app_callback): void
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE') !== 0) {
            return;
        }

        self::on($app_route, $app_callback);
    }

    protected static function option($app_route, $app_callback): void
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'OPTION') !== 0) {
            return;
        }

        self::on($app_route, $app_callback);
    }

    protected static function routes($app_route, $app_callback): void
    {
        if ((strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) && (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) && (strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') !== 0) && (strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE') !== 0)) {
            return;
        }

        self::on($app_route, $app_callback);
    }

    protected static function on($exprr, $call_back): void
    {
        $paramtrs = $_SERVER['REQUEST_URI'];
        $paramtrs = (stripos($paramtrs, "/") !== 0) ? "/" . $paramtrs : $paramtrs;
        $query =[];
        $paramtrs = explode("?",$paramtrs);
        if(count($paramtrs) > 1){
            $query = $paramtrs[1];
            $query = explode("&",$query);
        }
        $paramtrs = $paramtrs[0];

        $exprr = str_replace('/', '\/', $exprr);
        $matched = preg_match('/^' . ($exprr) . '$/', $paramtrs, $is_matched, PREG_OFFSET_CAPTURE);
        
        
        if ($matched) {
            // first value is normally the route, lets remove it
            
            array_shift($is_matched);
            // Get the matches as parameters
            
            $paramtrs = array_map(function ($paramtr) {
                return explode("/",$paramtr[0]);
            }, $is_matched);
            
            $call_back(new Request($paramtrs,$query), new Response());
        }
    }
}
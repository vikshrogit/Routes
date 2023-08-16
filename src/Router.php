<?php
namespace Ramphp\Router;


use Exception;
use Ramphp\Router\lib\Request;
use Ramphp\Router\lib\Response;

class Router extends lib\Router{
    /**
     * @var string
     * @example /Home
     * @example regex
     */
    protected string $path;
    /**
     * @var mixed
     * @example ['Service','Web']
     */
    protected mixed $components;
    /**
     * @var string
     * @example GET
     * @example POST
     */
    protected string $method;
    /**
     * @var string
     * @example service or component
     */
    protected string $type;

    /**
     * @throws Exception
     */
    public function __construct(string $path="", string $method="", string $type="", mixed $components="")
    {
              $this->path=$path;
              $this->components=$components;
              $this->method=$method;
              $this->type=$type;
              /*$this->createRoutes();*/
    }

    /**
     * @throws Exception
     */
    protected function createRoutes(): void
    {
        if(preg_match_all("header|footer|Header|Footer|HEADER|FOOTER",$this->components) || preg_match_all("header|footer|Header|Footer|HEADER|FOOTER",$this->path)){
            throw new Exception("Header or Footer Component cannot called by route");
        }
        if(strtolower($this->type)=="services"){
            $this->serviceRoute();
        }else if(strtolower($this->type)=="component"){
            $this->componentRoute();
        }else{
            throw new Exception("Route Type is Invalid");
        }
    }

    private function serviceRoute(): void
    {
        $this->{$this->method}($this->path,function (Request $request,Response $response){

        });
    }

    private function componentRoute(): void
    {
        $this->{$this->method}($this->path,function (Request $request){

        });
    }
}
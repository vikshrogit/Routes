<?php
namespace Ramphp\Router\lib;

class   Request
{
    public mixed $paramtrs;
    public string $req_method;
    public string $content_type;
    public mixed $headers;
    
    public array $query=[];
    public array $cookies;

    public function __construct($paramtrs = [],$query=[])
    {
        
        $this->paramtrs = $paramtrs;
        if($query){
            $this->getQuery($query);
        }
        $this->req_method = trim($_SERVER['REQUEST_METHOD']);
        $this->content_type = !empty($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        $this->headers = $this->getHeaders();
        $this->cookies = $_COOKIE;
    }

    public function getBody(): array|string
    {
        if ($this->req_method !== 'POST' && $this->req_method !== 'PUT') {
            return '';
        }

        $post_body = [];
        foreach ($_POST as $key => $value) {
            $post_body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $post_body;
    }

    public function getJSON(): mixed
    {
        if ($this->req_method !== 'POST' && $this->req_method !== 'PUT') {
            return [];
        }

        if (strcasecmp($this->content_type, 'application/json') !== 0) {
            return [];
        }

        // Receive the RAW post data.
        $post_content = trim(file_get_contents("php://input"));
        return json_decode($post_content);
    }

    function getHeaders($header_name = null): mixed
    {
        $keys = array_keys($_SERVER);
        $headervals=[];
        if (is_null($header_name)) {
            //$keys = preg_replace("/REDIRECT_/","",$keys);
            $headers = preg_grep("/^(.*)HTTP_(.*)/si", $keys);
            //print_r($headers);
        } else {
            //$keys = preg_replace("/REDIRECT_/","",$keys);
            $header_name_safe = str_replace("-", "_", strtoupper(preg_quote($header_name)));
            $headers = preg_grep("/^(.*)HTTP_${header_name_safe}$/si", $keys);
        }

        foreach ($headers as $header) {
            if (is_null($header_name)) {
                $headervals[substr(preg_replace("/^(.*)REDIRECT_/","",$header), 5)] = $_SERVER[$header];
                $headervals[strtolower(substr(preg_replace("/^(.*)REDIRECT_/","",$header), 5))] = $_SERVER[$header];
                $headervals[ucfirst(substr(preg_replace("/^(.*)REDIRECT_/","",$header), 5))] = $_SERVER[$header];
            } else {
                return $_SERVER[$header];
            }
        }
        $this->headers =$headervals;
        return $headervals;
    }

    private function getQuery($query): void
    {
        $this->query=array();
        foreach($query as $q){
            $q = explode("=",$q);
            $this->query[$q[0]] = explode(",",$q[1]);
        }
    }
}
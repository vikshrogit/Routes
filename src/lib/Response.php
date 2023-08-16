<?php

namespace Ramphp\Router\lib;

class Response
{
    private int $p_status = 200;

    public function p_status(int $p_code): static
    {
        $this->p_status = $p_code;
        return $this;
    }
    
    public function toJSON($data,$status=200, $headers=[],$cookies=[],$excludeHeaders=[]): void
    {
        
        
        if($headers){
            $this->setHeaders($headers);
        }

        if($cookies){
            $this->setCookies($cookies);
        }

        if($excludeHeaders){
            $this->removeHeaders($excludeHeaders);
        }

        if($status!=200){
            $this->p_status=$status;
        }

        header('Content-Type: application/json');
        header('Accept: application/json');

        if(gettype($data) == "string"){
            http_response_code($this->p_status);
            echo $data;
        }else{
            if (array_key_exists("StatusCode",$data)){
                $this->p_status = $data['StatusCode'];
            }
            http_response_code($this->p_status);
            echo json_encode($data);
        }
        
    }

    private function setHeaders($headers=[]): void
    {
        $keys = array_keys($headers);
        foreach($keys as $key){
            header($key.":".$headers[$key],true,$this->p_status);
        }
    }

    private function setCookies($Cookies=[]): void
    {
        $keys = array_keys($Cookies);
        foreach($keys as $key){
            setcookie($key,$Cookies[$key]);
        }
    }

    private function removeHeaders($headers=[]): void
    {
        foreach($headers as $header){
           header_remove($header);
        }
    }

    
    
}
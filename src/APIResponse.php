<?php

namespace EMGroup\BlueSnap;

class APIResponse
{

    public $headers;
    public $body;

    public function __construct($body, $headers)
    {
        $this->setBody($body);
        $this->setHeaders($headers);
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $headers
     */
    public function setHeaders($headers)
    {
        foreach(explode(PHP_EOL, $headers) as $header){
            list($header_var, $header_val) = explode(':', $header, 1);
            $this->headers[trim($header_var)] = trim($header_val);
        }
    }

    public function getHeader($key){
        if (isset($this->headers[$key])){
            return $this->headers[$key];
        }else{
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

}
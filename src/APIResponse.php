<?php

namespace EMGroup\BlueSnap;

/**
 * Class APIResponse
 * @package EMGroup\BlueSnap
 */
class APIResponse
{

    public $responseCode = null;
    public $headers;
    public $body;

    /**
     * APIResponse constructor.
     * @param $body
     * @param $headers
     * @param int $responseCode
     */
    public function __construct($body, $headers, $responseCode = null)
    {
        $this->setBody($body);
        $this->setHeaders($headers);
        $this->responseCode = $responseCode;
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
            if (strpos($header, ':') !== false){
                $v = explode(':', $header, 2);
                $header_var = isset($v[0]) ? $v[0] : null;
                $header_val = isset($v[1]) ? $v[1] : null;
                $this->headers[trim($header_var)] = trim($header_val);
            }
        }
    }

    /**
     * @param $key
     * @return null
     */
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
<?php
namespace EMGroup\BlueSnap;

class APICall {

    public static $url = '';
    public static $username = '';
    public static $password = '';

    /**
     * @var null|APIResponse
     */
    public static $last_response = null;

    /**
     * @param $endpoint
     * @param $method
     * @param $data
     * @return bool|mixed
     */
    public static function call($endpoint, $method, $data){
        $url = rtrim(self::$url, "/").'/'.$endpoint;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, self::$username . ":" . self::$password);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        // receive server response ...
        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        self::$last_response = new APIResponse($body, $header, $httpcode);

        curl_close ($ch);
        if (substr($body,0,1) !== '{'){
            return false;
        }else{
            return json_decode($body);
        }
    }



}
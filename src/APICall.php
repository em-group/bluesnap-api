<?php
namespace EMGroup\Bluesnap;

class APICall {

    public static $url = '';
    public static $username = '';
    public static $password = '';

    public static function call($endpoint, $data){
        $url = rtrim(self::$url, "/").'/'.$endpoint;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, self::$username . ":" . self::$password);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
        if (substr($server_output,0,1) !== '{'){
            return false;
        }else{
            return json_decode($server_output);
        }
    }

}
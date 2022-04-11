<?php
namespace App\Service;

class GeoPlugin
{
    //initiate the geoPlugin vars
    private $host = null;
    private $ip = null;
    private $city = null;
    private $countryCode = null;
    private $countryName = null;
    private $latitude = null;
    private $longitude = null;
//    private $key = null;

    function __construct() {
        $this->host = 'https://geolocation-db.com/json/{IP}';
//        $this->key = 'AIzaSyD78Uf551-yLVeqj6km_p5GF-vVWAWva5k';
    }

    function locate($ip = null) {
        global $_SERVER;

        if ( is_null( $ip ) ) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $host = str_replace( '{IP}', $ip, $this->host );
        //$host = str_replace( '{LANG}', $this->lang, $host );
        //$host = str_replace( '{KEY}', $this->key, $this->host );

        $data = array();

        $response = $this->fetch($host);
        $data = json_decode($response,true);
        $this->ip = $ip;
        $this->city = $data['city'];
        $this->countryCode = $data['country_code'];
        $this->countryName = $data['country_name'];
        $this->latitude = $data['latitude'];
        $this->longitude = $data['longitude'];
        return $this->city;

    }

    function fetch($host) {

        if ( function_exists('curl_init') ) {

            //use cURL to fetch data
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $host);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_USERAGENT, 'geoPlugin PHP Class v1.1');
            $response = curl_exec($ch);
            curl_close ($ch);

        } else if ( ini_get('allow_url_fopen') ) {

            //fall back to fopen()
            $response = file_get_contents($host, 'r');

        }
        return $response;
    }
}
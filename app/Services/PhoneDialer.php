<?php
/**
 * Created by PhpStorm.
 * User: sloan58
 * Date: 6/27/15
 * Time: 11:04 AM
 */

namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PhoneDialer {

    /**
     * @var Client
     */
    public $client;

    /**
     * @param $phoneIP
     */
    function __construct($phoneIP)
    {
        $this->client = new Client([
            'base_uri' => 'http://' . $phoneIP,
            'verify' => false,
            'headers' => [
                'Accept' => 'application/xml',
                'Content-Type' => 'application/xml'
            ],
            'auth' => [
                    env('CUCM_LOGIN'), env('CUCM_PASS')
                ],
        ]);

//        $this->client = new Client([
//            'base_url' => 'http://' . $phoneIP,
//            'defaults' => [
//                'headers' => [
//                    'Accept' => 'application/xml',
//                    'Content-Type' => 'application/xml'
//                ],
//                'verify' => false,
//                'auth' => [
//                    env('CUCM_LOGIN'), env('CUCM_PASS')
//                ],
//            ]
//        ]);
    }

    public function dial($keys,$p)
    {

        foreach ($keys as $k)
        {
            $xml = 'XML=<CiscoIPPhoneExecute><ExecuteItem Priority="0" URL="' . $k . '"/></CiscoIPPhoneExecute>';

            try {

                $response = $this->client->post('http://' . $p . '/CGI/Execute',['body' => $xml]);

            } catch (RequestException $e) {

                dd($e);

            }

            $body = $response->getBody();
            echo $body; die;

        }



//        foreach ($keys as $k)
//        {
//            if ( $k == "Key:Sleep")
//            {
//                sleep(2);
//                continue;
//            }
//
//            $xml = 'XML=<CiscoIPPhoneExecute><ExecuteItem Priority="0" URL="' . $k . '"/></CiscoIPPhoneExecute>';
//
//            try {
//
//                $response = $this->client->post('http://' . $p . '/CGI/Execute',['body' => $xml]);
//
//                $xml = $response->xml();
//
//                if ($xml->ResponseItem['Status'] != "0")
//                {
//                    dd($xml->ResponseItem);
//                }
//
//
//            } catch (RequestException $E) {
//                dd($E);
//            }
//        }
//        return true;
    }

}
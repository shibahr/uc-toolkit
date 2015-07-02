<?php
/**
 * Created by PhpStorm.
 * User: sloan58
 * Date: 6/27/15
 * Time: 11:04 AM
 */

namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

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
    }

    public function dial($keys,$p)
    {
        foreach ($keys as $k)
        {
            if ( $k == "Key:Sleep")
            {
                sleep(2);
                continue;
            }

            $xml = 'XML=<CiscoIPPhoneExecute><ExecuteItem Priority="0" URL="' . $k . '"/></CiscoIPPhoneExecute>';

            try {

                $response = $this->client->post('http://' . $p . '/CGI/Execute',['body' => $xml]);

            } catch (RequestException $e) {

                if($e instanceof ClientException)
                {
                    //Unauthorized
                    dd('Client Exception');
                }
                elseif($e instanceof ConnectException)
                {
                    //Can't Connect
                    Log::error('Connection Exception', [$e]);
                    dd('Connection Exception');
                }
                else
                {
                    //Other exception
                    dd('Request Exception');
                }

            }
            Log::info('dial(),response', [$response->getBody()]);
        }
        return true;
    }

}
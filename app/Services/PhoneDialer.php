<?php
/**
 * Created by PhpStorm.
 * User: sloan58
 * Date: 6/27/15
 * Time: 11:04 AM
 */

namespace App\Services;


use App\Itl;
use GuzzleHttp\Client;
use Sabre\Xml\Reader;
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

        $this->reader = new Reader;
    }

    public function dial(ITL $itl,$keys)
    {

        foreach ($keys as $k)
        {
            if ( $k == "Key:Sleep")
            {
                sleep(2);
                continue;
            }

            //Temp workaround for USC NAT
//            $itl->ip_address = "10.134.173.108";

            $xml = 'XML=<CiscoIPPhoneExecute><ExecuteItem Priority="0" URL="' . $k . '"/></CiscoIPPhoneExecute>';

            try {

                $response = $this->client->post('http://' . $itl->ip_address . '/CGI/Execute',['body' => $xml]);

            } catch (RequestException $e) {

                if($e instanceof ClientException)
                {
                    //Unauthorized
                    Log::error('Authentication Exception', [$e]);
                    $itl->failure_reason = "Authentication Exception";
                    $itl->save();
                }
                elseif($e instanceof ConnectException)
                {
                    //Can't Connect
                    Log::error('Connection Exception', [$e]);
                    $itl->failure_reason = "Connection Exception";
                    $itl->save();
                }
                else
                {
                    //Other exception
                    Log::error('Unknown Error', [$e]);
                    $itl->failure_reason = "Unknown Exception";
                    $itl->save();
                }

                return false;
            }

            /*
             * Check our response code and flip
             * $return to false if non zero
             */
            $this->reader->xml($response->getBody()->getContents());
            $response = $this->reader->parse();
            if(! $response['value'][0]['attributes']['Status'] == 0) $return = false;

            Log::info('dial(),response', [$response]);

        }
        return $return;
    }

}
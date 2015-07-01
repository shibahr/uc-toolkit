<?php
namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use SoapClient;
use SoapFault;

class AxlSoap {

    /**
     * @var resource
     */
    protected $client;

    public function __construct($wsdlPath,$location,$user,$pass)
    {
        $this->client = new SoapClient($wsdlPath,
            [
                'trace'=> true,
                'exceptions'=> true,
                'location'=> $location,
                'login'=> $user,
                'password'=> $pass,
            ]
        );

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFunctions()
    {
        return $this->client->__getFunctions();
    }

    /**
     * @return mixed
     */
    public function getLastRequest()
    {
        return $this->client->__getLastRequest();
    }

    /**
     * @return string
     */
    public function getLastRequestHeaders()
    {
        return $this->client->__getLastRequestHeaders();
    }

    /**
     * @return string
     */
    public function getLastResponseHeaders()
    {
        return $this->client->__getLastResponseHeaders();
    }

    /**
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->client->__getLastResponse();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getPhone($macAddress)
    {
        try {
            return $this->client->getPhone([
                'name' => $macAddress
            ]);
        } catch(SoapFault $E) {

            dd($E);
            return $E;

        }
    }

    /**
     * @param $appUserId
     * @return \Exception|\SoapFault
     */
    public function getAppUser($appUserId)
    {
        try {
            return $this->client->getAppUser([
                'userid' => $appUserId
            ]);
        } catch(SoapFault $E) {

            dd($E);
            return $E;
//            var_dump($E->getMessage());
//            var_dump($E->getPrevious());
//            var_dump($E->getCode());
//            var_dump($E->getFile());
//            var_dump($E->getLine());
//            die;
        }
    }

    public function updateAppUser($appUserId,$devices)
    {
        try {
            return $this->client->updateAppUser([
                'userid' => $appUserId,
                'associatedDevices' => [
                    'device' => $devices
                ]
            ]);
        } catch(SoapFault $E) {

            dd($E);
            return $E;
        }
    }
}
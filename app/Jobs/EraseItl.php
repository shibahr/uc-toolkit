<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone;
use App\Services\AxlSoap;
use App\Services\RisSoap;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Storage;
use SoapClient;

class EraseItl extends Job implements SelfHandling
{
    /**
     * Create a new job instance.
     *
     * @param $macAddress
     * @param AxlSoap $axl
     * @return \App\Jobs\EraseItl
     */

    private $phone;

    public function __construct($macAddress)
    {


        $this->macAddress = $macAddress;

        $this->axl = new AxlSoap(
            base_path() . env('CUCM_AXL_WSDL'),
            env('CUCM_AXL_LOCATION'),
            env('CUCM_LOGIN'),
            env('CUCM_PASS')
        );

        $this->sxml = new RisSoap(
            base_path() . env('CUCM_SXML_WSDL'),
            env('CUCM_SXML_LOCATION'),
            env('CUCM_LOGIN'),
            env('CUCM_PASS')
        );

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if(!is_array($this->macAddress))
        {
            $deviceArray[] = $this->macAddress;
        } else {
            $deviceArray = $this->macAddress;
        }

        //Get Device Description
        $r = $this->axl->getPhone($this->macAddress);
        $phoneObj = $r->return->phone;

        //Get App User
        $appUserObj = $this->axl->getAppUser(env('CUCM_LOGIN'));

        //Create Device Array
        $s = createDeviceArray($appUserObj,$deviceArray);

        //Associate Devices
        $r = $this->axl->updateAppUser(env('CUCM_LOGIN'),$s);

        //Create RIS Port Phone Array
        $risArray = createRisPhoneArray($deviceArray);

        //Get Device IP
        $res = $this->sxml->getDeviceIp($risArray);
        dd($res);

        //Process RIS Results
        $r = processRisResults($res,$risArray);
    }
}

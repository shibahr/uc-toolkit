<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone;
use App\Services\AxlSoap;
use App\Services\PhoneDialer;
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
            app_path() . env('CUCM_AXL_WSDL'),
            env('CUCM_AXL_LOCATION'),
            env('CUCM_LOGIN'),
            env('CUCM_PASS')
        );

        $this->sxml = new RisSoap(
            app_path() . env('CUCM_SXML_WSDL'),
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

        // Ensure $deviceArray is an array
        if(!is_array($this->macAddress))
        {
            $deviceArray[] = $this->macAddress;
        } else {
            $deviceArray = $this->macAddress;
        }

        //Get App User
        $appUserObj = $this->axl->getAppUser(env('CUCM_LOGIN'));

        //Create Device Array
        $appUserDeviceArray = createDeviceArray($appUserObj,$deviceArray);

        //Associate Devices to App User
        $this->axl->updateAppUser(env('CUCM_LOGIN'),$appUserDeviceArray);

        //Create RIS Port Phone Array
        $risArray = createRisPhoneArray($deviceArray);

        //Get Device IP's from RIS Port
        $SelectCmDeviceResult = $this->sxml->getDeviceIp($risArray);

        //Process RIS Port Results
        $risPortResults = processRisResults($SelectCmDeviceResult,$risArray);
        //Loop Devices and erase ITL
        foreach($risPortResults as $device)
        {
            $keys = setITLKeys('Cisco 7965');

            $dialer = new PhoneDialer($device['IpAddress']);

            $dialer->dial($keys,$device['IpAddress']);

        }
    }
}

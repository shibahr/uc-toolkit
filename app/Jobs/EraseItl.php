<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone;
use App\Services\AxlSoap;
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
            base_path() . env('CUCM_WSDL'),
            env('CUCM_LOCATION'),
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
        //Get Device Description
        $r = $this->axl->getPhone($this->macAddress);
        $phoneObj = $r->return->phone;

        /*
         * Save the phone
         * We'll record this event regardless
         * of the outcome.
         */
        $p = Phone::firstOrCreate([
            'mac' => $this->macAddress,
            'description' => $phoneObj->description
        ]);

        //Create the ITL record for the phone
        $i = $p->itl()->create([]);

        //Get App User
        $appUserObj = $this->axl->getAppUser(env('CUCM_LOGIN'));

        /*
         * The 'createDeviceArray' function
         * needs an array of devices
         */
        $deviceArray[] = $this->macAddress;

        //Create Device Array
        $s = createDeviceArray($appUserObj,$deviceArray);

        //Associate Devices
        $r = $this->axl->updateAppUser(env('CUCM_LOGIN'),$s);

        $i->result = 'Success';
        $i->save();

        return;
        //Create Phone Array
        //Get Device IP
        //Process RIS Results
    }
}

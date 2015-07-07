<?php

namespace App\Jobs;

use App\Eraser;
use App\Phone;
use App\Services\PhoneDialer;
use App\Services\PreparePhoneList;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Log;

/**
 * Class EraseItl
 * @package App\Jobs
 */
class EraseTrustList extends Job implements SelfHandling
{
    /**
     * Create a new job instance.
     *
     * @param $macAddress
     * @return \App\Jobs\EraseItl
     */

    private $macAddress;
    private $tleType;

    /**
     * @param $macAddress
     * @param $tleType
     */
    public function __construct($macAddress,$tleType)
    {
        $this->macAddress = $macAddress;
        $this->tleType = strtolower($tleType);
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

        $phoneList = new PreparePhoneList($deviceArray);
        $risPortResults = $phoneList->createList();

        //Loop Devices and erase Trust List
        foreach($risPortResults as $device)
        {
            //Create the Phone model
            $phone = Phone::firstOrCreate([
                'mac' => $this->macAddress,
                'description' => $device['Description']
            ]);

            //Start creating Eraser
            $tleObj = new Eraser;
            $tleObj->phone_id = $phone->id;
            $tleObj->ip_address = $device['IpAddress'];
            $tleObj->eraser_type = $this->tleType;

            if($device['IpAddress'] == "Unregistered/Unknown")
            {
                //Not registered, save as failed
                $tleObj->result = 'Fail';
                $tleObj->failure_reason = 'Unregistered/Unknown';
                $tleObj->save();
                Log::info('Device Unregistered/Unknown.', [$device]);
                continue;
            }

            /*
             * Get the key press series
             */
            $keys = setKeys($device['Model'],$tleObj->eraser_type);
            Log::info('setEraserKeys(),$keys', [$keys]);

            if(!$keys)
            {
                $tleObj->result = 'Fail';
                $tleObj->failure_reason = 'Unsupported Model';
                $tleObj->save();
                return;
            }


            $dialer = new PhoneDialer($device['IpAddress']);

            //Dial the keys
            $status = $dialer->dial($tleObj,$keys);

            //Check Pass/Fail and save ITL
            $passFail = $status ? 'Success' : 'Fail';
            $tleObj->result = $passFail;
            $tleObj->save();


        }
    }
}

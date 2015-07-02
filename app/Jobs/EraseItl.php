<?php

namespace App\Jobs;

use App\Itl;
use App\Phone;
use App\Services\PhoneDialer;
use App\Services\PreparePhoneList;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Log;

class EraseItl extends Job implements SelfHandling
{
    /**
     * Create a new job instance.
     *
     * @param $macAddress
     * @return \App\Jobs\EraseItl
     */

    private $macAddress;

    public function __construct($macAddress)
    {
        $this->macAddress = $macAddress;
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

        //Loop Devices and erase ITL
        foreach($risPortResults as $device)
        {
            //Create the Phone model
            $phone = Phone::firstOrCreate([
                'mac' => $this->macAddress,
                'description' => $device['Description']
            ]);

            //Start creating ITL
            $itl = new Itl;
            $itl->phone_id = $phone->id;
            $itl->ip_address = $device['IpAddress'];

            if($device['IpAddress'] == "Unregistered/Unknown")
            {
                //Not registered, save as failed
                $itl->result = 'Fail';
                $itl->save();
                Log::info('Device Unknown/Unregistered.', [$device]);
                continue;
            }

            /*
             * This needs work
             * Create device enum/model map
             * or query CUCM again
             */
            $keys = setITLKeys('Cisco 7961');
            Log::info('setITLKeys(),$keys', [$keys]);

            if(!$keys)
            {
                $itl->result = 'Fail';
                $itl->failure_reason = 'Unsupported Model';
                $itl->save();
                return;
            }

            // Temp workaround for AO NAT
//            $device['IpAddress'] = "10.134.173.108";

            $dialer = new PhoneDialer($device['IpAddress']);

            //Dial the keys
            $status = $dialer->dial($keys,$device['IpAddress']);

            //Check Pass/Fail and save ITL
            $passFail = $status ? 'Success' : 'Fail';
            $itl->result = $passFail;
            $itl->save();


        }
    }
}

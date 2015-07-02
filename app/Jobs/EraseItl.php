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
            $phone = Phone::firstOrCreate([
                'mac' => $this->macAddress,
                'description' => $device['Description']
            ]);

            if($device['IpAddress'] == "Unregistered/Unknown")
            {
                Itl::create([
                    'phone_id' => $phone->id,
                    'ip_address' => $device['IpAddress'],
                    'result' => 'Fail'
                ]);

                Log::info('Device Unknown/Unregistered.', [$device]);
                continue;
            }

            $keys = setITLKeys('Cisco 7965');
            Log::info('setITLKeys(),$keys', [$keys]);

            // Temp workaround for AO NAT
//            $device['IpAddress'] = "10.134.173.108";

            $dialer = new PhoneDialer($device['IpAddress']);

            $dialer->dial($keys,$device['IpAddress']);

        }
    }
}

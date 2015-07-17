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
     * @return \App\Jobs\EraseTrustList
     */

    public $eraserArray;

    public function __construct(Array $eraserArray)
    {
        $this->eraserArray = $eraserArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $macList = array_column($this->eraserArray, 'MAC');

        $phoneList = new PreparePhoneList();

        $risPortResults = $phoneList->createList($macList);

        foreach($this->eraserArray as $row)
        {
            $key = array_search($row['MAC'], array_column($risPortResults, 'DeviceName'));
            $risPortResults[$key]['TLE'] = $row['TLE'];
            $risPortResults[$key]['BULK_ID'] = $row['BULK_ID'];
        }

        //Loop Devices and erase Trust List
        foreach($risPortResults as $device)
        {


            //Create the Phone model
            $phone = Phone::firstOrCreate([
                'mac' => $device['DeviceName'],
                'description' => $device['Description']
            ]);

            //Start creating Eraser
            $tleObj = Eraser::create([
                'phone_id' => $phone->id,
                'ip_address' => $device['IpAddress'],
                'eraser_type' => $device['TLE'],
            ]);

            $tleObj->bulks()->attach($device['BULK_ID']);

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
            Log::info('setKeys(),$keys', [$tleObj->eraser_type]);

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

            //Successful if returned true
            $passFail = $status ? 'Success' : 'Fail';
            $tleObj->result = $passFail;
            $tleObj->save();

            return;
        }
    }
}

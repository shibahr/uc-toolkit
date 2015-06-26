<?php

/**
 * @param $userObj
 * @param $deviceList
 * @return array
 */
function createDeviceArray($userObj,$deviceList)
{
    //$device array to be returned
    $devices = [];

    /*
     * Check if UserObj deviceList is not set.
     * (user is not currently associated to any devices)
     */
    if (!isset($userObj->return->appUser->associatedDevices->device))
    {

        /*
         * Add all device from the device list
         * to the devices array
         */
        foreach($deviceList as $d)
        {
            $devices[] = $d;
        }
    }
    /*
     * Check if userObj devices is an array
     * (user is associated to multiple devices)
     */
    elseif (is_array($userObj->return->appUser->associatedDevices->device)) {

        $devices = array_merge($userObj->return->appUser->associatedDevices->device,$deviceList);

        //If the userObj element DOES exist but IS NOT and array (it's a single device)
        /*
         * The userObj devices object exists
         * but is not an array.
         * (it's a single device)
         */
    } else {

        array_push($deviceList,$userObj->return->appUser->associatedDevices->device);
        $devices = $deviceList;
    }

    /*
     * It's possible some devices were
     * already associated to the appUser.
     * Here we make sure the device names
     * are unique.
     */
    $devices = array_unique($devices);

    /*
     * We only want to return the
     * array values.
     * (Can't remember why I had to do this...)
     */
    $devices = array_values($devices);

    return $devices;
}

/**
 * @param $phones
 * @return array
 */
function createRisPhoneArray($phones)
{
    $deviceArray = [];

    foreach ($phones as $i)
    {
        $deviceArray[]['Item'] = $i;
    }
    return $deviceArray;
}

/**
 * @param $risResults
 * @param $phoneArray
 * @return mixed
 */
function processRisResults($risResults,$phoneArray)
{
    $i = 0;

    foreach (array_chunk($phoneArray,1000,true) as $chunk)
    {
        foreach($chunk as $k => $v)
        {
            $deviceAndIp[$i]['DeviceName'] = $v['Item'];

            foreach ($risResults as $cmNode)
            {
                if (!isset($cmNode->CmDevices[0])) continue;

                $deviceAndIp[$i]['IpAddress'] = searchForIp($cmNode->CmDevices,$deviceAndIp[$i]['DeviceName']);

                if (filter_var($deviceAndIp[$i]['IpAddress'], FILTER_VALIDATE_IP)) break;
            }
            if (!$deviceAndIp[$i]['IpAddress'])
            {
                $deviceAndIp[$i]['IpAddress'] = "Unregistered/Unknown";
            }
            $i++;
        }
    }
    return $deviceAndIp;
}

/**
 * @param $array
 * @param $value
 * @return bool
 */
function searchForIp($array,$value)
{
    foreach ($array as $device)
    {
        if ($device->Name == $value && $device->Status == "Registered")
        {
            return $device->IpAddress;
        }
    }
    return false;
}
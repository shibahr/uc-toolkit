<?php


namespace App\Services;


use Illuminate\Support\Facades\Log;

class PreparePhoneList {

    public $deviceArray;

    public function __construct(Array $deviceArray)
    {
        $this->deviceArray = $deviceArray;

        $this->axl = new AxlSoap(
            app_path() . '/CiscoAPI/axl/schema/8.5/AXLAPI.wsdl',
            env('CUCM_AXL_LOCATION'),
            env('CUCM_LOGIN'),
            env('CUCM_PASS')
        );

        $this->sxml = new RisSoap(
            app_path() . '/CiscoAPI/sxml/schema/RISAPI.wsdl',
            env('CUCM_SXML_LOCATION'),
            env('CUCM_LOGIN'),
            env('CUCM_PASS')
        );
    }

    public function createList()
    {
        //Get App User
        $appUserObj = $this->axl->getAppUser(env('CUCM_LOGIN'));
        Log::info('getAppUser(),$appUserObj,', [$appUserObj]);

        //Create Device Array
        $appUserDeviceArray = createDeviceArray($appUserObj,$this->deviceArray);
        Log::info('createDeviceArray(),$appUserDeviceArray,', [$appUserDeviceArray]);

        //Associate Devices to App User
        $res = $this->axl->updateAppUser(env('CUCM_LOGIN'),$appUserDeviceArray);
        Log::info('updateAppUser(),$res,', [$res]);

        //Create RIS Port Phone Array
        $risArray = createRisPhoneArray($this->deviceArray);
        Log::info('createRisPhoneArray(),$risArray,', [$risArray]);

        //Get Device IP's from RIS Port
        $SelectCmDeviceResult = $this->sxml->getDeviceIp($risArray);
        Log::info('getDeviceIp(),$SelectCmDeviceResult,', [$SelectCmDeviceResult]);

        //Process RIS Port Results
        $risPortResults = processRisResults($SelectCmDeviceResult,$risArray);

        //Fetch device model from type product
        for($i=0; $i<count($risPortResults); $i++)
        {
            if($risPortResults[$i]['IsRegistered'])
            {
                $results = $this->axl->executeSQLQuery('SELECT name FROM typeproduct WHERE enum = "' . $risPortResults[$i]['Product'] . '"');
                $risPortResults[$i]['Model'] = $results->return->row->name;
            }
        }
        Log::info('processRisResults(),$risPortResults,', [$risPortResults]);
        return $risPortResults;
    }

}
<?php
namespace App\Services;

/**
 * Class SqlSelect
 * @package App\Services
 */
class SqlSelect
{

    /**
     * @param $wsdlPath
     * @param $location
     * @param $user
     * @param $pass
     */
    public function __construct($sql)
    {
        $this->axl = new AxlSoap(
            app_path() . '/CiscoAPI/axl/schema/8.5/AXLAPI.wsdl',
            env('CUCM_AXL_LOCATION'),
            env('CUCM_LOGIN'),
            env('CUCM_PASS')
        );

        $this->sql = $sql;
    }

    public function executeQuery()
    {
        /*
         * Send Query to CUCM and
         * return the results.
         */
        $result = $this->axl->executeSQLQuery($this->sql);

        return $result->return->row;

    }

    public function parseSql()
    {
        /*
         * Set the Regex pattern
         * Extract the 'SELECT List'
         */
        $pattern = "/SELECT\s+(.*)\s+FROM/i";
        preg_match_all($pattern,$this->sql, $matches);

        /*
         * Place SELECT List into array
         */
        $arr = explode(',', $matches[1][0]);

        /*
         * Create final array that this
         * function will return
         */
        $selectList = [];

        /*
         * Iterate each item in the SELECT list
         */
        foreach($arr as $i)
        {
            /*
             * Check if the element has a space.
             * If so, we need to extract the
             * intended header name
             *
             * i.e. - SELECT d.name devicename FROM device
             *  OR    SELECT d.name as devicename FROM device
             */
            if(preg_match('/\s/', $i))
            {
                /*
                 * Split the SELECT List
                 * and push onto the return array
                 * by popping the splits array
                 */
                $splits = explode(' ',$i);
                array_push($selectList, trim(array_pop($splits)));
            }

            /*
             * If no spaces, check if there is
             * any 'dot notation'.
             *
             * i.e - SELECT d.name FROM device
             */
            elseif(preg_match('/.*\.(.*)$/', $i,$matches))
            {
                array_push($selectList, trim($matches[1]));
            }

            /*
             * No dots or spaces, must be a
             * simple SELECT List
             */
            else {
                array_push($selectList, trim($i));
            }
        }

        return $selectList;
    }
}
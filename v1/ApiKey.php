<?php
namespace apikey\v1;

/*
Loads JSON file with keys and the checks if given key contained in keys array

@Author Evgeniy Panin
@Email varenich@gmail.com
*/

class ApiKey {
    private $_configPath;
    private $_keys;

    /*
    Creates an object, loads keys from JSON file located at configPath

    configPath : string - Path to JSON file of API keys
    */
    public function __construct($configPath='') {
        $this->_configPath = $configPath;

        $jsonconfigstr = file_get_contents($configPath);
        $jsonconfig = json_decode($jsonconfigstr,true);

        $this->_keys = @$jsonconfig["apiKeys"];
    } // __construct

    /*
    Checks if keyToCheck exists in keys array of a given restriction

    restrictionName : string - key of an api keys array related to restriction (loaded from configPath)
    keyToCheck : string - API key to check
    */
    public function check($restrictionName='',$keyToCheck='') {
        if (@$this->_keys[$restrictionName]) {
            $res = array();
            foreach ($this->_keys[$restrictionName] as $key=>$val) {
                $res = array_merge( $res, array_values($val) );
            }
            if (in_array($keyToCheck, $res) ) return true;
            throw new \Exception('Access denied', 403);
        }
        throw new \Exception('Key storege is empty. Check the path to config');
    } // check
} // class
?>
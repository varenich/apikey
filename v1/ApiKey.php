<?php
namespace apikey\v1;

class ApiKey {
    private $_configPath;
    private $_keys;

    public function __construct($configPath) {
        $this->_configPath = $configPath;

        $jsonconfigstr = file_get_contents($configPath);
        $jsonconfig = json_decode($jsonconfigstr,true);

        $this->_keys = @$jsonconfig["apiKeys"];
    } // __construct

    public function check($restrictionName,$keyToCheck) {
        //print_r($this->_keys);
        //echo "#####";
        if (@$this->_keys[$restrictionName]) {
            $res = array();
            foreach ($this->_keys[$restrictionName] as $key=>$val) {
                $res = array_merge( $res, array_values($val) );
            }
            if (in_array($keyToCheck, $res) ) return true;
            throw new \Exception('Доступ запрещен', 403);
        }
        throw new \Exception('Хранилище ключей пустое');
    } // check
} // class
?>
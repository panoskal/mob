<?php
class Configuration {

    public $id;
    public $keyname;
    public $value;
    public $notes;

    public static function getAllConfiguration() {
        global $database;
        $query = "SELECT * FROM cms_configuration ORDER BY id ASC";
        $result_array = $database->getObj($query, 'Configuration');
		return !empty($result_array) ? $result_array : false;
    }

    public static function getConfigurationByKey($keyname) {
        global $database;
        $query = "SELECT * FROM cms_configuration WHERE keyname=:keyname LIMIT 1";
        $result_array = $database->getObj($query, 'Configuration', array(':keyname' => $keyname));
		return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function getConfigurationById($id) {
        global $database;
        $query = "SELECT * FROM cms_configuration WHERE id=:id LIMIT 1";
        $result_array = $database->getRows($query, array(':id' => $id));
		return !empty($result_array) ? array_shift($result_array) : false;
    }

    public function insertSingleConfig($configArray) {
        global $database;
        $query = "INSERT INTO cms_configuration (keyname, value, notes) VALUES (:keyname, :value, :notes)";
        $database->insertRow($query, array(
            ':keyname' => $configArray['keyname'],
            ':value' => $configArray['value'],
            ':notes' => $configArray['notes']
        ));
        $result = $database->getInsertedId();
        return $result;
    }

    public static function insertMultiConfig($configArray) {
        global $database;
        if (!empty($configArray) && is_array($configArray)) {
            foreach($configArray as $configKey=>$configVal) {
                $query = "INSERT INTO cms_configuration (keyname, value, notes) VALUES (:keyname, :value, :note);";
                $database->imInsertRow($query, array(
                    ':keyname'  => mb_strtoupper($configKey, 'UTF-8'),
                    ':value'    => $configVal['value'],
                    ':note'     => $configVal['note']
                ));
            }
        }
        $result = $database->getInsertedId();
        return $result;
    }

    public function updateConfig($configArray, $id) {
        global $database;
        $query = "UPDATE cms_configuration SET keyname=:keyname, value=:value, notes=:notes WHERE id=:id";
        $result = $database->updateRow($query, array(
            ':keyname'          => $configArray['keyname'],
            ':value'            => $configArray['value'],
            ':notes'            => $configArray['notes'],
            ':id'               => $id
        ));
        return $result;
    }

    public static function deleteConfig($id) {

        global $database;
        $query = "DELETE FROM cms_configuration WHERE id=:id";
        $result = $database->deleteRow($query, array(
            ':id'               => $id
        ));
        return $result;

    }


}

?>

<?php

// If it's going to need the database, then it's
// probaly smart to require it before we start.
require_once (LIB_PATH.DS.'database.php');
class DatabaseObject {

    // common database methods
    public static function find_all()    {
        return static::find_by_sql("SELECT * FROM" .static::$table_name);

    }

    public static function find_by_id($id=0) {
        global $database;
        $result_array = static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function find_by_sql($sql=""){
        global $database;
        $result_set= $database->query($sql);
        $object_array = array();
        while ($row = $database->fetch_array($result_set)) {
            $object_array[] = static::instantiate($row);

        }
        return $object_array;
    }


    private static function instantiate($record) {
        // Could check that $record exists and is an array
        // Simple, long-form approach:
        $object = new static();
//        $object->id         = $record['id'];
//        $object->username   = $record['username'];
//        $object->password   = $record['password'];
//        $object->first_name = $record['first_name'];
//        $object->last_name  = $record['last_name'];

        // more dynamic, short-form approach:
        foreach($record as $attribute=>$value){
            if($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;

    }
    private function has_attribute($attribute) {
        //get_object_vats returns an associate array with all attributes
        //(incl. private ones!) as the keys and their current values as the value
        $object_vars = get_object_vars($this);
        // we don't care about the value, we just want to know if the key exists
        //Will return true or false
        return array_key_exists($attribute, $object_vars);
    }

}














?>
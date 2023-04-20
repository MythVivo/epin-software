<?php

namespace App\Classes;

use App\Classes\Cari\CariException;

abstract class DBItem extends DBAdapter
{
    abstract public  function getName(): string;
    protected int $id = 0;
    private static array $DBMAP;

    protected function __construct(int $id = null, ?object $data = null)
    {
        if ($id) {
            $this->id = $id;
            if (!$this->Reload())
                $this->id = 0;
        } elseif ($data) {
            $this->fromDB($data);
        }
    }
    public function __toString()
    {
        return json_encode($this->export(false), JSON_UNESCAPED_UNICODE);
    }
    public function export(bool $full = false)
    {
        $output = ["__type" => get_called_class()];
        foreach ($this::$DBMAP['fields'] as $local => $db) {
            $localArr = explode(".", $local);
            $localName = $localArr[0];
            if ($full) {
                $localVal = $this->$localName instanceof DBItem ? $this->$localName->export(true) : $this->$localName;
            } else {
                $localVal = $this;
                foreach ($localArr as $l)
                    $localVal = $localVal->$l;
            }

            $output[$localName] = $localVal;
        }
        return $output;
    }
    public static function RequiredFields($method = "Create")
    {
        $finalClassName = get_called_class();
        $ReflectionClass = new \ReflectionClass($finalClassName);
        $fields = [];
        if ($method == "Create") {
            $props = $ReflectionClass->getProperties();
            foreach ($props as $prop)
                if (!$prop->hasDefaultValue() && $prop->name != 'id')
                    $fields[] = ["name" => $prop->name, "type" => (string)$prop->getType(), "value" =>  "", "readonly" => false];
        } else {
            $reflectionMethod = new \ReflectionMethod($finalClassName, $method);
            $params = $reflectionMethod->getParameters();
            foreach ($params as $param) {
                if (!$param->isDefaultValueAvailable())
                    $fields[] = ["name" => $param->name, "type" => (string)$param->getType(), "value" =>  "", "readonly" => false];
            }
        }
        return $fields;
    }
    public static function Caller(string $method, ?array $args)
    {
        $finalClassName = get_called_class();
        if ($method == "Create")
            return $finalClassName::$method($args);

        $reqFields = $finalClassName::RequiredFields($method);
        $forwardArr = [];
        foreach ($reqFields as $field) {
            $argName = $field['name'];
            $argType = $field['type'];
            $argVal = strstr($argType, "\\") && !($args[$argName] instanceof $argType) ?  $argType::FindById($args[$argName]) : $args[$argName];
            $forwardArr[] = $argVal;
        }
        return forward_static_call_array([$finalClassName, $method], $forwardArr);
    }
    public static function Create(array $args)
    {
        $finalClassName = get_called_class();
        $reqFields = $finalClassName::RequiredFields();
        $fields = array_map(function ($f) {
            return $f["name"];
        }, $reqFields);
        unset($args['id']);
        if (array_diff($fields, array_keys($args)))
            return null;

        $instance = new $finalClassName();

        /* foreach ($args as $argName => $argVal)
            $instance->$argName = $argVal; */

        foreach ($reqFields as $field) {
            $argName = $field['name'];
            $argType = $field['type'];
            $argVal = strstr($argType, "\\") && !($args[$argName] instanceof $argType) ?  $argType::FindById($args[$argName]) : $args[$argName];
            $instance->$argName = $argVal;
        }

        $result = $instance->Insert();
        if ($result) {
            $instance->id = $result;
            $instance->Reload();
            return $instance;
        } else
            return null;
    }
    public function getId(): int
    {
        return $this->id;
    }


    public function toDB(): array
    {
        $retArr = [];
        foreach ($this::$DBMAP['fields'] as $local => $db)
            $retArr[$db] = @$this->mapLocalVariable($this, $local);
        return $retArr;
    }
    public function fromDB(object $data)
    {
        $finalClassName = get_called_class();
        foreach ($this::$DBMAP['fields'] as $local => $db) {
            $isObj = strstr($local, ".");
            $local = explode(".", $local)[0];
            if ($isObj) {
                if ($data->$db) {
                    $refProp = new \ReflectionProperty($finalClassName, $local);
                    $objType = (string)$refProp->getType();
                    $this->$local = $objType::FindById($data->$db);
                }
            } else
                $this->$local = @$data->$db;
        }
    }
    public static function getTableName()
    {
        return get_called_class()::$DBMAP['table'];
    }
    public static function FindById(int $id)
    {
        $finalClassName = get_called_class();
        $instance = new $finalClassName($id);
        if ($instance->id)
            return $instance;
        else
            return null;
    }
    public static function InitFromData($data)
    {
        $finalClassName = get_called_class();
        $instance = new $finalClassName(null, $data);
        if ($instance->id)
            return $instance;
        else
            return null;
    }

    public function mapLocalVariable(object $that, string $field)
    {
        if (strstr($field, ".")) {
            $fields = explode(".", $field);
            foreach ($fields as $f) {
                $that = $this->mapLocalVariable($that, $f);
            }
            return $that;
        } else
            return $that->$field;
    }
    public static function All(?int $from = null, ?int $limit = null, ?bool $include_deleted = null, bool $objMode = true): ?DBItemCollection
    {
        return self::SelectAll($from, $limit, $include_deleted, $objMode);
    }
    public function Update(array $fields)
    {
        $finalClassName = get_called_class();
        $map = $finalClassName::$DBMAP['fields'];
        $upFields = array_keys($fields);

        $dbFields = array_values($map);
        $diffFields = array_diff($upFields, $dbFields);
        if ($diffFields) { //Rebuild Fields For Database
            foreach ($diffFields as $k => $f) {

                $mapF = @$map[$f];
                if ($mapF) {
                    $fields[$mapF] = $fields[$f];
                    unset($fields[$f]);
                }
            }
            $upFields = array_keys($fields);
            $diffFields = array_diff($upFields, $dbFields);
            if ($diffFields)
                throw new CariException("DB Error");
        }

        return parent::Update($fields);
    }
}

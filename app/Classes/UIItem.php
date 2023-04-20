<?php

namespace App\Classes;


abstract class UIItem extends DBItem
{
    final private function SerializeUI()
    {
        $fields = $this->export(true);

        $output = [];
        foreach ($fields as $field => $value) {
            if ($field == "__type")
                continue;
            if (is_array($value)) {
                $type = $value["__type"];
                $val = $value["id"];
            } else {
                $type = gettype($value);
                $val = $value;
            }
            $output[] = ["name" => $field, "type" => $type, "value" =>  $val, "readonly" => ($field == "id" ? true : false)];
        }
        return $output;
    }
    final public function __toString()
    {

        return FormGenerator::asHtml($this->SerializeUI());
    }
    public function Edit()
    {
        return FormGenerator::asHtml($this->SerializeUI(), true);
    }
}

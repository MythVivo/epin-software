<?php

namespace App\Classes;

use App\Classes\DBItem;

class FormGenerator
{
    public static function GenerateForm(string $className, string  $method, string $type = 'html')
    {
        /*   $reflection = new \ReflectionMethod($className, $method);
        $fields = [];
        foreach ($reflection->getParameters() as $arg) {
            $fields[] = ["name" => $arg->name, "type" => (string)$arg->gettype(), "value" => ($arg->isDefaultValueAvailable() ? $arg->getDefaultValue() : "")];
        } */

        $fields = $className::RequiredFields($method);
        switch ($type) {
            case 'html':
                return self::asHtml($fields, true);
                break;
            case 'json':
                return self::asJson($fields);
                break;
        }
    }
    public static function GenerateInfo(string $className, int $id, string  $type = 'html'): string
    {
        $instance = $className::FindById($id);
        if ($type == "edit" || $type == "confirm")
            return $instance->Edit();
        else
            return (string)$instance;
    }
    public static function asHtml(array $data, bool $edit = false, bool $confirm = false)
    {
        if (!$edit) {
            $output = [];
            foreach ($data as $input) {
                $type = $input["type"];
                $value = "";
                switch ($type) {
                    case 'bool':
                    case 'boolean':
                        $output[] = "<input type=''>";
                        break;
                    case 'string':
                    case 'float':
                    case 'int':
                    case 'integer':
                    case 'double':
                        $value = $input["value"];
                        break;
                    default: {
                            if (class_exists($type)) {
                                $value = $type::FindById($input["value"])->getName();
                            }
                        }
                        break;
                }
                $output[] = "<tr><td>{$input["name"]}</td><td>{$value}</td></tr>";
            }
            if (!$confirm)
                $output[] = "<tr><td colspan='2'><input type='submit' name='editUI' value='Edit'></td></tr>";
            else
                $output[] = "<tr><td colspan='2'><input type='submit' name='confirmUI' value='Confirm'></td></tr>";

            $output = implode("", $output);
            return "<form method='post'><table>$output</table></form>";
        }
        $output = [];
        foreach ($data as $input) {
            $type = $input["type"];
            $readonly = $input["readonly"] ? "readonly" : "";
            $output[] = "<label for='{$input["name"]}'>{$input["name"]} : </label>";
            switch ($type) {
                case 'bool':
                case 'boolean':
                    $output[] = "<input type=''>";
                    break;
                case 'string':
                case 'float':
                case 'int':
                case 'integer':
                case 'double':
                    $output[] = "<input type='text' name='{$input["name"]}' placeholder='{$input["name"]}' value='{$input["value"]}' {$readonly}/>";
                    break;
                default: {
                        if (class_exists($type)) {
                            $output[] = "<select name='{$input["name"]}'>";
                            $options = $type::All(null, null, null, true);
                            foreach ($options as $option) {
                                $selected = $option->getId() == $input["value"] ? ' selected="selected" ' : "";
                                $output[] = "<option value='{$option->getId()}' {$selected}>" . $option->getName() . '</option>';
                            }
                            $output[] = "</select>";
                        }
                    }
                    break;
            }
            $output[] = "<br>";
        }
        $output[] = "<input type='submit' name='confirmUI' value='Submit'>";
        $output = implode("", $output);
        return "<form method='post'>$output</form>";
    }
    public static function asJson($data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

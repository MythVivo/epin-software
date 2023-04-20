<?php

namespace App\Classes\Cari;

use App\Classes\FormGenerator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use function PHPSTORM_META\type;

class CariRouter  extends Controller
{
    public function list()
    {
        $exclude = ["CariException", "CariRouter"];
        $temp_files = glob(__dir__ . '/*');
        $classNames = array_filter(array_map(function ($file) use ($exclude) {
            $f_name =  pathinfo($file, PATHINFO_FILENAME);
            return in_array($f_name, $exclude) ? null : $f_name;
        }, $temp_files));
        foreach ($classNames as $class) {

            echo "<a href='" . $_SERVER['REQUEST_URI'] . '/' . $class . "'>$class</a><br>";
        }
    }
    public function form(Request $request)
    {
        $c  = @$request->route;
        $m = @$request->subroute;

        if (!$c && !$m) {
            echo "noop";
            exit();
        }
        $className = __NAMESPACE__ . '\\'  . $c;
        if (intval($m) == $m) {
            echo FormGenerator::GenerateInfo($className, $m);
        } else
            echo FormGenerator::GenerateForm($className, $m);
    }
    public function router(Request $request)
    {
        $postdata = $request->post();
        $c = @$request->route;
        $m = @$request->subroute;

        $className = __NAMESPACE__ . '\\'  . $c;
        if (class_exists($className)) {
            if (!$m) {
                $reflection = new \ReflectionClass($className);
                $methods = $reflection->getMethods(\ReflectionMethod::IS_STATIC);
                foreach ($methods as $method) {
                    var_dump($method);
                    echo "<a href='/carixxx/{$c}/{$method->name}'>{$method->name}</a>";
                    echo "<br>";
                }
                exit();
            } else {

                if (intval($m) == $m) {
                    if (@$postdata['editUI']) {
                        echo FormGenerator::GenerateInfo($className, $m, 'edit');
                    } elseif (@$postdata['confirmUI']) {
                        $id = $postdata['id'];
                        $item = $className::FindById($id);
                        $itemArr = $item->export();
                        unset($postdata['confirmUI']);
                        unset($postdata['id']);
                        $fieldsToUpdate = [];
                        foreach ($postdata as $k => $v) {
                            if ($itemArr[$k] != $v)
                                $fieldsToUpdate[$k] = $v;
                        }
                        if ($fieldsToUpdate)
                            $item->Update($fieldsToUpdate);
                        echo json_encode(["status" => 1, "fields" => $fieldsToUpdate], JSON_UNESCAPED_UNICODE);
                    }
                    exit();
                } else {
                    if (method_exists($className, $m)) {
                        $reflection = new \ReflectionMethod($className, $m);
                        if ($reflection->isPublic() && $reflection->isStatic()) {
                            $args = [];

                            $missingArgs = [];
                            $reqFields = $className::RequiredFields($m);

                            $fields = array_map(function ($f) {
                                return $f["name"];
                            }, $reqFields);

                            foreach ($fields as $arg) {
                                if (@$postdata[$arg] === null) {
                                    $missingArgs[] = $arg;
                                } else
                                    $args[] = $postdata[$arg];
                            }
                            if ($missingArgs) {
                                echo json_encode(['status' => 0, 'msg' => 'Missing Args', 'args' => $missingArgs]);
                                exit();
                            } else {
                                try {
                                    $result = $className::Caller($m, $postdata);
                                    echo json_encode(['status' => 1, 'data' => $result->export(true)], JSON_UNESCAPED_UNICODE);
                                } catch (CariException $e) {
                                    echo json_encode(['status' => 0, 'msg' => $e->getMessage()]);
                                }
                                exit();
                            }
                        }
                        exit();
                    }
                }
            }
        }
        echo json_encode(['status' => 0, 'msg' => 'Missing Endpoint']);

        /*        switch ($route) {
        } */
    }
}

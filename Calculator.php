<?php
    function calc($a, $b, $op) {
        $res = 0;

        try {
            switch ($op)
            {
                case '+': $res = $a + $b; break;
                case '-': $res = $a - $b; break;
                case '*': $res = $a * $b; break;
                case '/': $res = $a / $b; break;
                default: throw new Exception('Неизвестная операция'); break;
            }
        } catch (Exception $e){
            throw $e;
        }

        return $res;
    }

    http_response_code(400);

    try {
        $data = json_decode(file_get_contents('php://input'), true);
    } catch (Exception $e) {
        die(json_encode(array('error'=>"Ошибка разбора данных: " . $e->getMessage())));
    }

    if (!isset($data["a"]) ||
        !isset($data["b"]) ||
        !isset($data["op"]))
    {
        die(json_encode(array('error'=>'Незаполненные поля')));
    }

    $result = 0;
    
    try {
        $result = calc($data['a'], $data['b'], $data['op']);
    } catch (DivisionByZeroError $e) {
        die(json_encode(array('error'=>"Ошибка: Деление на ноль!")));
    } catch (Exception $e) {
        die(json_encode(array('error'=>"Ошибка: " . $e->getMessage())));
    }

    http_response_code(200);
    
    die(json_encode(array_merge(
        $data, ['result' => $result]
    )));
?>

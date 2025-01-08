<?php
    $subdomain = 'vergivikinandrey'; //Поддомен нужного аккаунта
    $link = 'https://' . $subdomain . '.amocrm.ru/api/v4/leads/complex'; //Формируем URL для запроса
    /** Получаем access_token из вашего хранилища */
    $access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjUzMmE1OTBiNjM4NGI1MmYxZTQ5ZjI2ODdiZGFmNDNlZmZhOWVlY2FjYjM5ZGY0MjhhMDNmYmJjYTRmNWYyZTM2MTQ4NmRlMjVmM2QzZDQwIn0.eyJhdWQiOiJjOTU5ZDcxMC0xMGE4LTQxNWYtODUzNS01MDViNzcwMjNlYTYiLCJqdGkiOiI1MzJhNTkwYjYzODRiNTJmMWU0OWYyNjg3YmRhZjQzZWZmYTllZWNhY2IzOWRmNDI4YTAzZmJiY2E0ZjVmMmUzNjE0ODZkZTI1ZjNkM2Q0MCIsImlhdCI6MTczNjM1NjczNywibmJmIjoxNzM2MzU2NzM3LCJleHAiOjE3MzgyODE2MDAsInN1YiI6IjExOTYwODYyIiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjMyMTU2OTgyLCJiYXNlX2RvbWFpbiI6ImFtb2NybS5ydSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJjcm0iLCJmaWxlcyIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiLCJwdXNoX25vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiZmRhZmQwYzgtNTdiMC00NDhmLWIxNzQtZjBiMzM0YTk4Y2Q5IiwiYXBpX2RvbWFpbiI6ImFwaS1iLmFtb2NybS5ydSJ9.WC4An-ztS_vUBDXvDwIwKhYdiNIfPtFE4i19bXhUUyG410ipRcsXnHUr0db6m-0yW3mXyinCAQNHmIl8l8wjmAsiohFa1XG71NqAeFR2zhsHfuC8zunyHGF9Uhn4rooBaEIJp9esHF_D59iPx902zEI1w7E_Wq-2gZQY8MDxb82-KWXHyU0MeJCMj4ZgoORaxALvldlte4GJTd7WdEf5gA3t89WNOtLUc_h91Xj44qfLWxdF3_edh6omBH5zl2x5nw9yqYeOVaivpuVfq_CVFkveO4UJx2cISavmXKmdmyIqnX45W2DC4obVaSZCSPJ111EpBPdKTdDjm7tKooWJYQ';
    /** Формируем заголовки */
    $headers = [
        'Authorization: Bearer ' . $access_token,
        'Content-Type:application/json'
    ];

    // Если пользователь провел на сайте больше 30 секунд, добавляем дополнительное поле для сделки
    $timeValue = (int) $_POST['time'] == 1 ? true : false;

    /** Соберем данные для запроса */
    $data = [
        [
            'name' => 'Сделка',
            'price' => (int) $_POST['price'],
            "custom_fields_values" => [
                [
                    "field_id" => 767597,
                    "values" => [
                        [
                            "value" => $timeValue
                        ]
                    ]
                ]
            ],
            "_embedded" => [
                "contacts" => [
                    [
                        "first_name" => $_POST['name'],
                        "custom_fields_values" => [
                            [
                                "field_code" => "EMAIL",
                                "values" => [
                                    [
                                        "enum_code" => "WORK",
                                        "value" => $_POST['email']
                                    ]
                                ]
                            ],
                            [
                                "field_code" => "PHONE",
                                "values" => [
                                    [
                                        "enum_code" => "WORK",
                                        "value" => $_POST['phone']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ]
    ];

    /**
     * Нам необходимо инициировать запрос к серверу.
     * Воспользуемся библиотекой cURL (поставляется в составе PHP).
     */
    $curl = curl_init(); //Сохраняем дескриптор сеанса cURL
    /** Устанавливаем необходимые опции для сеанса cURL  */
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
    curl_setopt($curl,CURLOPT_URL, $link);
    curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl,CURLOPT_HEADER, false);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
    $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    /** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
    $code = (int)$code;
    $errors = [
        400 => 'Bad request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not found',
        500 => 'Internal server error',
        502 => 'Bad gateway',
        503 => 'Service unavailable',
    ];
    
    try
    {
        /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
        if ($code < 200 || $code > 204) {
            throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
        } else {
            echo 'Запрос успешно выполнен. Сделка добавлена.';
        }
    } catch(\Exception $e)
    {
        die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
    }
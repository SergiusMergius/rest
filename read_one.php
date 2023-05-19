<?php

// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// подключение файла для соединения с базой и файл с объектом
include_once "../config/database.php";
include_once "../objects/item.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$item = new Item($db);

// установим свойство ID записи для чтения
$item->id = isset($_GET["id"]) ? $_GET["id"] : die();

// получим детали товара
$item->readOne();

if ($item->name != null) {

    // создание массива
    $item_arr = array(
        "id" =>  $item->id,
        "name" => $item->name,
        "phone" => $item->phone,
        "key" => $item->key,
        "created_at" => $item->created_at,
        "updated_at" => $item->updated_at
    );

    // код ответа - 200 OK
    http_response_code(200);

    // вывод в формате json
    echo json_encode($item_arr);
} else {
    // код ответа - 404 Не найдено
    http_response_code(404);

    // сообщим пользователю, что такой товар не существует
    echo json_encode(array("message" => "Товар не существует"), JSON_UNESCAPED_UNICODE);
}
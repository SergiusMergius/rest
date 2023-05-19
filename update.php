<?php

// HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключаем файл для работы с БД и объектом Product
include_once "../config/database.php";
include_once "../objects/item.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$item = new Item($db);

// получаем id товара для редактирования
$data = json_decode(file_get_contents("php://input"));

// установим id свойства товара для редактирования
$item->id = $data->id;

// установим значения свойств товара
$item->name = $data->name;
$item->phone = $data->phone;
$item->key = $data->key;
$item->updated_at = $data->updated_at;

// обновление товара
if ($item->update()) {
    // установим код ответа - 200 ok
    http_response_code(200);

    // сообщим пользователю
    echo json_encode(array("message" => "Запись обновлена"), JSON_UNESCAPED_UNICODE);
}
// если не удается обновить, сообщим пользователю
else {
    // код ответа - 503 Сервис не доступен
    http_response_code(503);

    // сообщение пользователю
    echo json_encode(array("message" => "Невозможно обновить"), JSON_UNESCAPED_UNICODE);
}
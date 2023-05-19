<?php

class Item
{
    // подключение к базе данных и таблице "item"
    private $conn;
    private $table_name = "item";

    // свойства объекта
    public $id;
    public $name;
    public $phone;
    public $key;
    public $created_at;
    public $updated_at;

    // конструктор для соединения с базой данных
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // метод для получения
    function read()
    {
        // выбираем все записи
        $query = "SELECT
        i.id, i.name, i.phone, i.key, i.created_at, i.updated_at
    FROM
        " . $this->table_name . " i
    ORDER BY
        i.created_at DESC
        ";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // выполняем запрос
        $stmt->execute();
        return $stmt;
    }

    // метод для создания
    function create()
    {
        // запрос для вставки (создания) записей
        $query = "INSERT INTO
            " . $this->table_name . "
        SET
            name=:name, phone=:phone, key=:key, created_at=:created_at, updated_at=:updated_at";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->key = htmlspecialchars(strip_tags($this->key));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        // привязка значений
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":key", $this->key);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // метод для получения конкретного ID
    function readOne()
    {
        // запрос для чтения одной записи
        $query = "SELECT
            i.id, i.name, i.phone, i.key, i.created_at, i.updated_at
        FROM
            " . $this->table_name . " i
            
        WHERE
            i.id = ?
        LIMIT
            0,1";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // привязываем id
        $stmt->bindParam(1, $this->id);

        // выполняем запрос
        $stmt->execute();

        // получаем извлеченную строку
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // установим значения свойств объекта
        $this->name = $row["name"];
        $this->phone = $row["phone"];
        $this->key = $row["key"];
        $this->created_at = $row["created_at"];
        $this->updated_at = $row["updated_at"];
    }

    // метод для обновления
    function update()
    {
        // запрос для обновления записи
        $query = "UPDATE
            " . $this->table_name . "
        SET
            name = :name,
            phone = :phone,
            key = :key,
            updated_at = :updated_at
        WHERE
            id = :id";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->key = htmlspecialchars(strip_tags($this->key));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // привязываем значения
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":key", $this->key);
        $stmt->bindParam(":updated_at", $this->updated_at);
        $stmt->bindParam(":id", $this->id);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // метод для удаления товара
    function delete()
    {
        // запрос для удаления записи (товара)
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->id = htmlspecialchars(strip_tags($this->id));

        // привязываем id записи для удаления
        $stmt->bindParam(1, $this->id);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
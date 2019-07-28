<?php

require_once(__DIR__ . "/FaceRepositoryInterface.php");
require_once(__DIR__ . "/Face.php");

/** @var PDO $_connect */
class FaceRepositoryMysql implements FaceRepositoryInterface
{
    private $_connect;
    private $host = '127.0.0.1';
    private $db = 'pikabu';
    private $user = 'root';
    private $pass = '1234';
    private $charset = 'utf8mb4';

    /**
     * FaceRepositoryMysql constructor.
     */
    public function __construct()
    {
        // подклчаемся dbname=INFORMATION_SCHEMA на случай если наша база еще не существует
        $this->_connect = new PDO(
            "mysql:host=$this->host;dbname=INFORMATION_SCHEMA;charset=$this->charset",
            $this->user,
            $this->pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
        $this->_connect->exec("CREATE DATABASE IF NOT EXISTS $this->db;");

        $this->_connect->exec("USE $this->db");

        $this->_connect->exec("CREATE TABLE IF NOT EXISTS face (
   id int auto_increment primary key,
   race int,
   emotion int,
   oldness int
) ENGINE=INNODB;");
    }

    public function all(): array
    {
        return $this->_connect->query("select * from face")->fetchAll(PDO::FETCH_CLASS, Face::class);
    }

    public function find($id): FaceInterface
    {
        $statement = $this->_connect->prepare("select * from face where id = :id");
        $statement->bindParam(":id", $id);

        return $statement->fetch(PDO::FETCH_CLASS, Face::class);
    }

    public function create(FaceInterface $face): void
    {
        $sql = "INSERT INTO face (race, emotion, oldness) VALUES (:race, :emotion, :oldness)";
        $this->_connect->prepare($sql)->execute(
            [
                ":race" => $face->getRace(),
                ":emotion" => $face->getEmotion(),
                ":oldness" => $face->getOldness()
            ]
        );
    }

    public function update(FaceInterface $face): void
    {
        // TODO: Implement update() method.
    }

    public function delete(FaceInterface $face): void
    {
        // TODO: Implement delete() method.
    }

    public function flush()
    {
        $this->_connect->exec("TRUNCATE TABLE face");
    }

    public function findFiveSimilar(FaceInterface $face)
    {
        $statement = $this->_connect->prepare("select * from (select * from face order by id desc limit 10000) f order by sqrt(pow(f.race - :race, 2) + pow(f.emotion - :emotion, 2) + pow(f.oldness - :oldness, 2)) asc limit :limit");
        $statement->bindValue(":race", $face->getRace());
        $statement->bindValue(":emotion", $face->getEmotion());
        $statement->bindValue(":oldness", $face->getOldness());
        $statement->bindValue(":limit", 5);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Face::class);
        return $result;
    }
}
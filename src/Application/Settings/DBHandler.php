<?php

namespace App\Application\Settings;

use PDO;

class DBHandler
{
    private const PATH_TO_SQLITE_FILE = '/../../../db/chat.db';

    private static $instance = null;

    private $connection;

    private function __construct()
    {
        $this->connectDB();
        $this->createTables();
    }

    private function connectDB(): void
    {
        $databaseFile = __DIR__ . self::PATH_TO_SQLITE_FILE;
        $this->connection = new PDO('sqlite:' . $databaseFile);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DBHandler();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function createTables(): void
    {
        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL
        );"
        );

        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY,
            user_id INTEGER NOT NULL,
            content TEXT NOT NULL,
            timestamp INTEGER NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users (id)
        );"
        );
    }
}
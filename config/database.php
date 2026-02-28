<?php
class Database {
    public static function connect() {
        return new PDO("mysql:host=localhost;dbname=gestion_usuarios", "root", "");
    }
}

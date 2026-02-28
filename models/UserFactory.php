<?php
require_once "Admin.php";
require_once "NormalUser.php";

class UserFactory {
    public static function create($tipo, $nombre, $email) {
        if ($tipo === "admin") {
            return new Admin($nombre, $email);
        }
        return new NormalUser($nombre, $email);
    }
}

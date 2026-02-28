<?php
require_once "../config/database.php";
require_once "../models/UserFactory.php";
require_once "../observers/Logger.php";
require_once "../observers/EmailNotifier.php";

class UserController {

    private $observers = [];

    public function __construct() {
        $this->observers[] = new Logger();
        $this->observers[] = new EmailNotifier();
    }

    private function notify($mensaje) {
        foreach ($this->observers as $observer) {
            $observer->update($mensaje);
        }
    }

    // CREATE
    public function store($nombre, $email, $tipo) {
        if(strlen($nombre) < 3){
            throw new Exception("Nombre inválido");
        }

        $db = Database::connect();
        $user = UserFactory::create($tipo, $nombre, $email);

        $stmt = $db->prepare("INSERT INTO users (nombre, email, tipo) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $user->getTipo()]);

        $this->notify("Usuario creado: $nombre");
    }

    // READ
    public function index() {
        $db = Database::connect();
        return $db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id){
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update($id, $nombre, $email, $tipo){
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE users SET nombre=?, email=?, tipo=? WHERE id=?");
        $stmt->execute([$nombre, $email, $tipo, $id]);

        $this->notify("Usuario actualizado: $nombre");
    }

    // DELETE
    public function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);

        $this->notify("Usuario eliminado ID: $id");
    }
}
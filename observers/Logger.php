<?php
require_once "Observer.php";

class Logger implements Observer {
    public function update($mensaje) {
        $_SESSION['notificacion'][] = [
            "tipo" => "success",
            "mensaje" => $mensaje
        ];
    }
}
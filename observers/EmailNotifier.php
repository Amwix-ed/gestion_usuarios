<?php
require_once "Observer.php";

class EmailNotifier implements Observer {
    public function update($mensaje) {
        $_SESSION['notificacion'][] = [
            "tipo" => "info",
            "mensaje" => "Notificación enviada: " . $mensaje
        ];
    }
}
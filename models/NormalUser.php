<?php
require_once "User.php";

class NormalUser extends User {
    public function getTipo() {
        return "normal";
    }
}

<?php

class Session{
    private $logged_in = false;
    public $id;

    function __construct() {
        session_start();
        $this->check_login();
        if ( $this->logged_in ) {
            //
        } else {
            //
        }
    }

    public function login($user) {
        if ($user) {
            $this->id = $_SESSION['id'] = $user->user;
            $this->logged_in = true;
        }
    }

    private function check_login() {
        if (isset($_SESSION['id'])) {
            $this->id = $_SESSION['id'];
            $this->logged_in = true;
        } else {
            unset($this->id);
            $this->logged_in = false;
        }
    }

    public function is_logged_in() {
        return $this->logged_in;
    }

    public function logout() {
        unset($_SESSION['id']);
        unset($this->id);
        $this->logged_in = false;
    }
}
$session=new Session();
?>

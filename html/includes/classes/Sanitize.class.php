<?php

class Sanitize {
    public static $output;

    public static function cleanString($input) {
        $string = trim($input);
        $string = stripslashes($string);
        $string = strip_tags($string);
        return $string;
    }

    public static function processPass($input) {
        $input = trim($input);
        self::$output = password_hash( $input, PASSWORD_BCRYPT, array('cost' => 12));
        return self::$output;
    }

    public static function passVerify($pass, $dbPass) {
        return password_verify($pass, $dbPass);
    }

    public static function processEmail($input) {
        $input = trim($input);
        self::$output = filter_var($input, FILTER_SANITIZE_EMAIL);
        if (!filter_var(self::$output, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return self::$output;
        }
    }

    public static function processString($input) {
        $input = trim($input);
        self::$output = filter_var($input, FILTER_SANITIZE_STRING);
        return self::$output;
    }

    public static function processInt($input) {
        $input = trim($input);
        self::$output = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        if (!filter_var(self::$output, FILTER_VALIDATE_INT)) {
            return false;
        } else {
            return self::$output;
        }
    }

    public static function processFloat($input) {
        $input = trim($input);
        self::$output = filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT);
        if (!filter_var(self::$output, FILTER_VALIDATE_FLOAT)) {
            return false;
        } else {
            return self::$output;
        }
    }

    public static function processUrl($input) {
        $input = trim($input);
        self::$output = filter_var($input, FILTER_SANITIZE_URL);
        if (!filter_var(self::$output, FILTER_VALIDATE_URL)) {
            return false;
        } else {
            return self::$output;
        }
    }

}




?>

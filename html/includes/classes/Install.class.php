<?php
class Install {

//    public static function setTimezone() {
//        global $database;
//
//        $query = "SET time_zone = '+00:00';";
//
//        $database->imExec($query);
//    }

    public static function setMode() {
        global $database;

        $query = "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';";

        $database->imExec($query);
    }

    public static function createConfig() {
        global $database;

        $query = "CREATE TABLE IF NOT EXISTS `cms_configuration` (
          `id` int(255) NOT NULL AUTO_INCREMENT,
          `keyname` varchar(255) NOT NULL,
          `value` varchar(255) DEFAULT NULL,
          `notes` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

        $database->imExec($query);
    }

    public static function createMenu() {
        global $database;

        $query = "CREATE TABLE IF NOT EXISTS `cms_menu` (
          `slug` varchar(255) DEFAULT NULL,
          `order` int(255) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $database->imExec($query);
    }

    public static function createPages() {
        global $database;

        $query = "CREATE TABLE IF NOT EXISTS `cms_pages` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `slug` varchar(255) DEFAULT NULL,
          `title` varchar(255) DEFAULT NULL,
          `content` longtext,
          `meta_keywords` varchar(255) DEFAULT NULL,
          `meta_description` varchar(255) DEFAULT NULL,
          `lang` varchar(25) DEFAULT NULL,
          `enabled` int(11) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

        $database->imExec($query);
    }

    public static function createUsers() {
        global $database;

        $query = "CREATE TABLE IF NOT EXISTS `cms_users` (
          `user` varchar(255) DEFAULT NULL,
          `password` varchar(255) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $database->imExec($query);
    }

    public static function createWinners() {
        global $database;

        $query = "CREATE TABLE IF NOT EXISTS `cms_winners` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) DEFAULT NULL,
          `winnerdate` date DEFAULT NULL,
          `prize` varchar(255) DEFAULT NULL,
          `title` varchar(255) DEFAULT NULL,
          `email` varchar(255) DEFAULT NULL,
          `phone` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

        $database->imExec($query);
    }

}

?>

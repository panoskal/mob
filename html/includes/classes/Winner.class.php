<?php

class Winner {

    public $id;
    public $name;
    public $winnerdate;
    public $prize;
    public $title;
    public $email;
    public $phone;

    public static function getAllWinners() {
        global $database;
        $query = "SELECT * FROM cms_winners ORDER BY id ASC";
        $result_array = $database->getObj($query, 'Winner');
		return !empty($result_array) ? $result_array : false;
    }
    public static function getLastWinnerByDate() {
        global $database;
        $query = "SELECT * FROM cms_winners ORDER BY winnerdate DESC LIMIT 1";
        $result_array = $database->getObj($query, 'Winner');
		return !empty($result_array) ? $result_array : false;
    }

    public function getWinnerById($winner_id) {
        global $database;
        $query = "SELECT * FROM cms_winners WHERE id=:winner_id LIMIT 1";
        $result_array = $database->getObj($query, 'Winner', array(':winner_id' => $winner_id));
		return !empty($result_array) ? array_shift($result_array) : false;
    }

    public function insertWinner($winnerArray) {
        global $database;
        $query = "INSERT INTO cms_winners (name, winnerdate, prize, title, email, phone) VALUES (:name, :winnerdate, :prize, :title, :email, :phone)";
        $database->insertRow($query, array(
            ':name'         => $winnerArray['name'],
            ':winnerdate'   => $winnerArray['winnerdate'],
            ':prize'        => $winnerArray['prize'],
            ':title'        => $winnerArray['title'],
            ':email'        => $winnerArray['email'],
            ':phone'        => $winnerArray['phone']
        ));
        $result = $database->getInsertedId();
        return $result;
    }

    public function updateWinner($winnerArray, $id) {
        global $database;
        $query = "UPDATE cms_winners SET name=:name, winnerdate=:winnerdate, prize=:prize, title=:title, email=:email, phone=:phone WHERE id=:id";
        $result = $database->updateRow($query, array(
            ':name'         => $winnerArray['name'],
            ':winnerdate'   => $winnerArray['winnerdate'],
            ':prize'        => $winnerArray['prize'],
            ':title'        => $winnerArray['title'],
            ':email'        => $winnerArray['email'],
            ':phone'        => $winnerArray['phone'],
            ':id'           => $id
        ));
        return $result;
    }

    public static function deleteWinner($id) {

        global $database;
        $query = "DELETE FROM cms_winners WHERE id=:id";
        $result = $database->deleteRow($query, array(
            ':id'               => $id
        ));
        return $result;

    }

}

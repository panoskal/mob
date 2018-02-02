<?php

class User {

	public $user;
	public $password;

	/**
	* [[Description]]
	* @param  int $id [[Description]]
	* @return Object User object
	*/
	public function getUserById($id) {

		global $database;
		$query = "SELECT * FROM cms_users WHERE user = :user LIMIT 1";
		return $database->getObj($query, 'User', array(':id' => $id));

	}

    public static function insertUser($userArray) {

		global $database;
        $query = "INSERT INTO cms_users (user, password) VALUES (:user, :password);";
        $database->imInsertRow($query, array(
            ':user' => $userArray['username'],
            ':password' => $userArray['password']
        ));
        $result = $database->getInsertedId();
        return $result;

	}

	public static function userAuth($user="") {

		global $database;
		$query = "SELECT * FROM cms_users WHERE user = :user LIMIT 1";
		$result_array = $database->getObj($query, 'User', array(':user' => $user));
		return !empty($result_array) ? array_shift($result_array) : false;

	}

    public static function updateUsersPassword($pass, $user) {
        global $database;
        $query = "UPDATE cms_users SET password=:password WHERE user=:user";
        $result = $database->updateRow($query, array(
            ':password'          => $pass,
            ':user'           => $user,
        ));
        return $result;
    }

}


?>

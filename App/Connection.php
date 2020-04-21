<?php

namespace App;

class Connection {

	public static function getDb() {
		try {

			$conn = new \PDO(
				"mysql:host=localhost;dbname=your-dbname;charset=utf8",
				"username",
				"password" 
			);

			return $conn;

		} catch (\PDOException $e) {
			var_dump($e);
		}
	}
}

?>
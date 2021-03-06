<?php

require_once('/config/config.php');

class Database {

	protected static $connection;

	// Connect to the database. Configuration file is in /config/config.php
	// Establish the connection first, if it's not set.
	public function connect() {
		if (!isset(self::$connection)) {
			self::$connection = @new mysqli(db_hostname, db_username, db_password, db_dbname);
		}

		if (self::$connection->connect_error) {
			return false;
		} else {
			return self::$connection;
		}
	}

	public function error() {
		$conn = $this->connect();
		if (!$conn) {
			return self::$connection->connect_error;
		} else {
			return $conn->error;
		}
	}

	// Queries to the database with prepared statement.
	// The input bindings are from the array of bindings.
	public function query($stmt_string, $types, $array_of_bindings) {
		if (!($conn = $this->connect())) {
			return false;
		}

		if (!($stmt = $conn->prepare($stmt_string))) {
			return false;
		}

		for ($i = 0; $i < count($array_of_bindings); $i++) {
			$bind_name = 'bind' . $i;
			$$bind_name = $array_of_bindings[$i];
			$bind_names[] = &$$bind_name;
		}

		if (!call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $bind_names))) {
			return false;
		}

		if (!($result = $stmt->execute())) {
			return false;
		}

		$stmt->close();
		return $result;
	}

	// Query with zero inputs.
	public function simple_query($stmt) {
		if (!($conn = $this->connect())) {
			return false;
		}

		if (!$result = $conn->query($stmt)) {
			return false;
		}

		return true;
	}

	// Fetches data from the database, e.g. SELECT. Returns as a 2D array
	public function fetch($stmt_string, $types, $array_of_bindings) {
		if (!($conn = $this->connect())) {
			return false;
		}

		if (!($stmt = $conn->prepare($stmt_string))) {
			return false;
		}

		for ($i = 0; $i < count($array_of_bindings); $i++) {
			$bind_name = 'bind' . $i;
			$$bind_name = $array_of_bindings[$i];
			$bind_names[] = &$$bind_name;
		}

		if (!call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $bind_names))) {
			return false;
		}

		if (!($stmt->execute())) {
			return false;
		}

		$result = $stmt->get_result();
		if (!$result) {
			return false;
		} else {
			$rows = [];
			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}
		}

		$stmt->close();
		return $rows;
	}

	// simple_query, only it fetches.
	public function simple_fetch($stmt_string) {
		if (!($conn = $this->connect())) {
			return false;
		}

		if (!($stmt = $conn->prepare($stmt_string))) {
			return false;
		}

		if (!$stmt->execute()) {
			return false;
		}

		$result = $stmt->get_result();
		if (!$result) {
			return false;
		} else {
			$rows = [];
			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}
		}

		return $rows;
	}
}
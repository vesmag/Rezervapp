<?php
	//potrebno podesiti
    $servername = '';
    $username = '';
    $password = '';
    $database = '';

	//uspostavljanje konekcije, mysqli_connect alias je od mysqli::__construct
	$conn = new mysqli ($servername, $username, $password)
		or die ("Connection failed: ".mysqli_connect_error()); //vraća string description zadnjeg errora
	//stvaranje baze
	$conn->select_db($database);
/*	if (!$conn->select_db($database)) {
		$query = "CREATE DATABASE ".$database;
		if ($conn->query($query)) echo "Db created successfully!</br>";
			else echo "Error creating db: ".$conn->error."</br>";
	}
	else echo "Database already created"."</br>";

	$requests_create = "CREATE TABLE REQUESTS (
		username VARCHAR(20),
		password VARCHAR(20),
		email VARCHAR(30),
		name2show VARCHAR(30),
		id INT,
		primary key (username))";

	$users_create = "CREATE TABLE USERS (
		username VARCHAR(20),
		password VARCHAR(20),
		email VARCHAR(30),
		name2show VARCHAR(30),
		id INT,
		primary key (username))";

	//DATE - A date. Format: YYYY-MM-DD
	$reservations_create = "CREATE TABLE RESERVATIONS (
		classroom_name VARCHAR(5),
		date DATE,
		time INT(2),
		username VARCHAR(20),
		id INT,
		primary key (classroom_name, date, time))";

	$classrooms_create = "CREATE TABLE CLASSROOMS (
		classroom_name VARCHAR(5),
		capacity INT,
		id INT,
		primary key (classroom_name))";
		if ($conn->query($requests_create))
		echo "Table REQUESTS created successfully!</br>";
	else
		echo "Error creating REQUESTS table: ".$conn->error."</br>";
	if ($conn->query($users_create))
		echo "Table USERS created successfully!</br>";
	else
		echo "Error creating USERS table: ".$conn->error."</br>";
	if ($conn->query($reservations_create))
		echo "Table RESERVATIONS created successfully!</br>";
	else
		echo "Error creating REQUESTS table: ".$conn->error."</br>";
	if ($conn->query($classrooms_create))
		echo "Table CLASSROOMS created successfully!</br>";
	else
		echo "Error creating CLASSROOMS table: ".$conn->error."</br>";  */
?>

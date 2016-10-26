<?php

include_once('spajanje_na_bazu.php');

function insert_into_requests ($username, $password, $email, $name2show, $id) {
	global $conn;

	$query = "INSERT INTO REQUESTS VALUES ('".$username."', '".$password."', '".$email."', '".$name2show."', ".$id.");";
	if ($conn->query($query))
		return 1;
	else
		return 0;
}

function insert_into_users ($username, $password, $email, $name2show, $id) {
	global $conn;

	$query = "INSERT INTO USERS VALUES ('".$username."', '".$password."', '".$email."', '".$name2show."', ".$id.");";
	if ($conn->query($query))
		return 1;
	else
		return 0;
}

function insert_into_reservations ($classroom_name, $date, $time, $username, $id) {
	global $conn;

	$query = "INSERT INTO RESERVATIONS VALUES ('".$classroom_name."', '".$date."', '".$time."', '".$username."', ".$id.");";
	if ($conn->query($query))
		return 1;
	else
		return 0;
}

function insert_into_classrooms ($classroom_name, $capacity, $id) {
	global $conn;

	$query = "INSERT INTO CLASSROOMS VALUES ('".$classroom_name."', ".$capacity.", ".$id.");";
	if ($conn->query($query))
		return 1;
	else
		return 0;
}

function find_username ($username) {
	global $conn;

	$query = "SELECT username FROM USERS";
	$result = $conn->query($query);

    if ($result)
        while ($user = $result->fetch_assoc())
            if ($user['username'] == $username)
                return 1;

    $query = "SELECT username FROM REQUEST";
	$result = $conn->query($query);

    if ($result)
        while ($user = $result->fetch_assoc())
            if ($user['username'] == $username)
                return 1;

	return 0;
}

function find_email ($email) {
	global $conn;

	$query = "SELECT email FROM USERS";
	$result = $conn->query($query);

    if ($result)
        while ($user = $result->fetch_assoc())
            if ($user['email'] == $email)
                return 1;

    $query = "SELECT email FROM REQUESTS";
	$result = $conn->query($query);

    if ($result)
        while ($user = $result->fetch_assoc())
            if ($user['email'] == $email)
                return 1;

	return 0;
}

function find_name2show ($name2show) {
	global $conn;

	$query = "SELECT name2show FROM USERS";
	$result = $conn->query($query);

    if ($result)
        while ($user = $result->fetch_assoc())
            if ($user['name2show'] == $name2show)
                return 1;

    $query = "SELECT name2show FROM REQUESTS";
	$result = $conn->query($query);

    if ($result)
        while ($user = $result->fetch_assoc())
            if ($user['name2show'] == $name2show)
                return 1;
	return 0;
}


function get_name2show ($username) {
	global $conn;

	$query = "SELECT name2show FROM USERS WHERE username="."'".$username."';";
	$result = $conn->query($query);

    $name = $result->fetch_assoc()['name2show'];
	return $name;
}

function find_id ($id) {
	global $conn;

	$query = "SELECT id FROM USERS";
	$result = $conn->query($query);

    if ($result)
        while ($user = $result->fetch_assoc())
            if ($user['id'] == $id)
                return 1;

    $query = "SELECT id FROM REQUESTS";
	$result = $conn->query($query);

    if ($result)
        while ($user = $result->fetch_assoc())
            if ($user['id'] == $id)
                return 1;
	return 0;
}

function check_password ($username, $password) {
	global $conn;

	$query = "SELECT password FROM USERS WHERE username = "."'".$username."';";
	$result = $conn->query($query);

    if ($result)
        $user_password = $result->fetch_assoc()['password'];

	if ($user_password == $password)
		return 1;
	else return 0;
}


//vraca dvodimenzionalni array npr. $list = array( array(18, 'A5', Valentina Dumbovic), array(15, 'A8', Ana Marija Karlovic), array(14, 'A2', Vesna Magjarevic'))
//$list[2][2] =  'Vesna Magjarevic'
function get_reservations ($date) {
	global $conn;
	$list = array();

	$query = "SELECT time, classroom_name, name2show FROM USERS, RESERVATIONS WHERE date = "."'".$date."' AND USERS.username = RESERVATIONS.username;";
	$result = $conn->query($query);

  //  if ($result)
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push ($list, array ($row['time'], $row['classroom_name'], $row['name2show']));
        }

	return $list;
}


function get_username ($classroom_name, $date, $time) {
	global $conn;

	$query = "SELECT username FROM RESERVATIONS WHERE date = "."'".$date."' AND time = ".$time." AND classroom_name = '".$classroom_name."';";
	$result = $conn->query($query);

    if ($result)
        $username = $result->fetch_assoc()['username'];

	return $username;
}

function get_users () {
	global $conn;
	$list = array();

	$query = "SELECT username, email, name2show, id FROM USERS;";
	$result = $conn->query($query);

    if ($result)
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push ($list, array ($row['username'], $row['email'], $row['name2show'], $row['id']));
        }

	return $list;
}

function get_requests () {
	global $conn;
	$list = array();

	$query = "SELECT username, email, name2show, id FROM REQUESTS;";
	$result = $conn->query($query);

    if ($result)
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push ($list, array ($row['username'], $row['email'], $row['name2show'], $row['id']));
        }

	return $list;
}

function get_classrooms () {
	global $conn;
	$list = array();

	$query = "SELECT classroom_name FROM CLASSROOMS;";
	$result = $conn->query($query);

    if ($result)
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push ($list, $row['classroom_name']);
        }

	return $list;
}


function get_all_reservations_by_user ($username) {
	global $conn;
	$list = array();

	$query = "SELECT date, time, classroom_name FROM RESERVATIONS WHERE username = '".$username."';";
	$result = $conn->query($query);

   // if ($result)
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push ($list, array ($row['date'], $row['time'], $row['classroom_name']));
        }

	return $list;

}

function delete_from_requests ($id) {
	global $conn;

	$query = "DELETE FROM REQUESTS WHERE id = '".$id."';";
	if ($conn->query($query))
		return 1;
	else
		return 0;
}

function delete_from_users ($id) {
	global $conn;

	$query = "DELETE FROM USERS WHERE id = '".$id."';";
	if ($conn->query($query))
		return 1;
	else
		return 0;
}

function move_to_users ($id) {
		global $conn;
		$query = "SELECT * FROM REQUESTS WHERE id = '".$id."';";
		$result = $conn->query($query);
		if ($result)
		$user = $result->fetch_array(MYSQLI_ASSOC);
		$username = $user['username'];
		$password = $user['password'];
		$email = $user['email'];
		$name2show = $user['name2show'];
		delete_from_requests ($id);
		if (insert_into_users($username, $password, $email, $name2show, $id))
			return 1;
		else
			return 0;
}

function delete_from_reservations ($date, $time, $classroom_name) {
		global $conn;
		$query = "DELETE FROM RESERVATIONS WHERE date = '".$date."' AND time = ".$time." AND classroom_name = '".$classroom_name."';";
		if ($conn->query($query))
			return 1;
		else
			return 0;
}

function is_available ($dayofweek, $time, $classroom_name) {
		global $conn;
		$query = "SELECT * FROM RESERVATIONS WHERE dayname(date) = '".$dayofweek."' AND time = '".$time."' AND classroom_name = '".$classroom_name."';";
		$result = $conn->query($query);
		if ($result->num_rows == 0)
			return 1;
		else return "0";
}

function delete_from_reservations_user ($username) {
		global $conn;
		$query = "DELETE FROM RESERVATIONS WHERE username='".$username."';";
		if ($conn->query($query))
			return 1;
		else
			return 0;
}

function get_all_reservations_sort () {
	global $conn;
	$list = array();
	$query = "SELECT username, date, time, classroom_name FROM RESERVATIONS order by date;";
	$result = $conn->query($query);
	// if ($result)
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push ($list, array ($row['username'],$row['date'], $row['time'], $row['classroom_name']));
        }
	return $list;
}

?>

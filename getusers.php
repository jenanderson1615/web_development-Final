<?php
	//session_save_path("/nfs/stak/students/a/anderjen/sessions"); stored in htaccss
	session_start();
	ini_set('display_errors',1);  

	//--------------------------------------------------------------------------
	// Connect to mysql database
	//--------------------------------------------------------------------------
	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "anderjen-db", "Fd9lqRLTkguN4PNO", "anderjen-db");
	if ($mysqli->connect_errno) 
	{
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	//--------------------------------------------------------------------------
	// Get all the usernames and passwords
	//--------------------------------------------------------------------------
	if ( !($stmt = $mysqli->prepare("SELECT username, password FROM users_logins WHERE permission_level = 0") ) ) 
	{
		echo "prepare failed";
	}
	if (!$stmt->execute()) 
	{
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	if (!$stmt->bind_result($username, $password))
	{
		echo "Error binding result: (" . $stmt->errno . ") " . $stmt->error;
	}
	else 
	{
		//Buffer data
		$stmt->store_result();
		//$usernames = array();
		//$passwords = array();
		$users = array();
		$x = 0;
		//Gets rows from buffered data
		
		while ($stmt->fetch())
		{
			//puts rows into an array named users with the key being the username and the password being the value
			//$usernames[$x] = $username;
			$users[$username] = $password;
			$x++;
		}
		//Frees memory allocated for buffer
		$stmt->free_result();
		//Dealocates statement handle
		$stmt->close();
	}   
	//--------------------------------------------------------------------------
	// echo result as json 
	//--------------------------------------------------------------------------
	
	//$_SESSION['usernames'] = $usernames;
	//$_SESSION['users'] = $users;
	echo json_encode($users);
	//echo json_encode($usersnames);
	//$js_array_of_users = json_encode($users);
?>
	
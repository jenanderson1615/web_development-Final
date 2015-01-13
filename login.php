<!DOCTYPE html>
<html>
<head>
<?php
	session_start();
?>
	<link rel="stylesheet" type="text/css" href="projectstyle.css">
	<title>Login</title>
	<meta charset="UTF-8">
	<script src = 'jquery.min.js'></script>
	<script src = 'jquery.cookie.js'></script>
	<style>
		#wrap 
		{ 
			width: 900px; 
			padding: 210px;
		}
	</style>
</head>
<body>
	<div id = "wrap">
	<h1 id = "header">SOAR client website</h1>
	<?php
		if (isset($_POST['newusername']))
		{		
			$newusername = $_POST['newusername'];
			$newuserpass = $_POST['newpassword'];
			$newuserfname = $_POST['newfirstname'];
			$newuserlname = $_POST['newlastname'];
			$newuserphone = $_POST['newphone'];
			$newuserstreet1 = $_POST['newstreet1'];
			$newuserstreet2 = $_POST['newstreet2'];
			$newusercity = $_POST['newcity'];
			$newuserstate = $_POST['newstate'];
			$newuserzip = $_POST['newzip'];
				
			//Enter the new user login information to users_logins
			$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "anderjen-db", "Fd9lqRLTkguN4PNO", "anderjen-db");
			if ($mysqli->connect_errno) 
			{
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			}
			if ( !($stmt = $mysqli->prepare("INSERT INTO users_logins(`username`, `password`) values('$newusername', '$newuserpass')") ) ) 
			{
				echo "prepare failed";
			}
			if (!$stmt->execute()) 
			{
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
				
			//Enter the new user login information to clients table
			$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "anderjen-db", "Fd9lqRLTkguN4PNO", "anderjen-db");
			if ($mysqli->connect_errno) 
			{
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			}
			if ( !($stmt = $mysqli->prepare("INSERT INTO clients(`first_name`, `last_name`, `street1`, 
											`street2`, `city`, `state`, `zip`, `username`, `phone`) 
											values('$newuserfname', '$newuserlname', '$newuserstreet1', 
													'$newuserstreet2', '$newusercity', '$newuserstate', 
													'$newuserzip', 
													(SELECT username FROM users_logins WHERE username = '$newusername'), 
													'$newuserphone')") ) ) 
			{
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			if (!$stmt->execute()) 
			{
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		}
	?>
	Username: <input type = "text" id = "name"><br>
	Password: <input type = "password" id = "password"><br>
	<input type = "submit" id = "submitbutton" value = "Log in">
	<div id = "pagetext"></div>
	<form action = "usercreate.html" method = "POST" id = "newuserbutton">
		<input type = "submit" value = "Create a new user">
	</form>
	<script>
	$("#submitbutton").click
	(
		function()
		{	
			var name = $("#name").val();
			var password = $("#password").val();
			if((name == "") || (password == ""))
			{
				$("#pagetext").text("You must type name and password");
			}
			else
			{
				$("#pagetext").text("Checking your login information");
				$.ajax
				({
					url: "getusers.php",
					dataType: "json",
					success: function(data, info)
					{
						if(data.hasOwnProperty(name))
						{
							if(data[name] == password)
							{ 
								$.cookie('username', name);
								window.location.replace("userpage.php");
							}
							else
								$("#pagetext").text("Login information is incorrect.");
						}
						else
							$("#pagetext").text("Login information is incorrect.");
					}
				});
			}
		}
	);
	</script>
</div>
</body>
</html>


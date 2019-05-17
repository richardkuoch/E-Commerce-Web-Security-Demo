<?php
	session_start();
	include("rsa.php");
?>
<html>
	<body>
		<?php
			//Receive username from client side
			$received_username = $_POST['username'];
			//Receive password from client side
			$received_password = $_POST['password'];

			if($received_username!="" & $received_password != ""){
				$find = 0;
				//read users.txt line by line
				foreach(file('../database/users.txt') as $line) {
					//split each line as two parts
					list($username, $hashed_password) = explode(",",$line);
					//verify if there exists a user with the same username
					if($username == $received_username){
						$find = 1;
						//verify the password		
						$privateKey = get_rsa_privatekey('private.key');
						$decrypted = rsa_decryption($received_password, $privateKey);
						
						//echo "Decrypted received_password".$decrypted."<br/><br/>";
						// $decrypted should be 'hashed_password + & + timestamp
						$split_value = explode("&", $decrypted);
						// $split_value[0] should be hashed_password
						// $split_value[1] should be timestamp
						
						//echo "Split decrypted value as 2 parts: ";
						//echo "<br/>".$split_value[0];
						//echo "<br/>".$split_value[1]."<br/><br/>";
						//get current timestamp on server-side
						$timestamp = time();
							
						// compare the timestamp, to make sure the submitted information did not be copied and used later
						if($timestamp - (int)$split_value[1] <= 150 ){
							// compare the submitted hashed password and the server stored hashed password
							if($hashed_password == $split_value[0]."\n"){
								$_SESSION['login'] = "YES";
								$_SESSION["username"] = $received_username;
								header('Location: ../client/shop.php');
								$login = 1;
								break;
							}else{
								echo "Wrong Password";
							}
						}else{
							echo "<br/><br/>The difference between the client-side submitted timestamp and the current server-side timestamp is greater than 150 seconds. Invalid login request!<br/><br/>";
						}
						break;
					}
				}
				if($find==0){
					echo "<br/>Could not find the user ".$received_username." in the database<br/>";
				}
				
				echo "<br/>Go back to <a href='http://titan.csit.rmit.edu.au/~s3563242/assignment/client/login.html'>login</a> page";
			}else{
				echo "Username and Password cannot be empty!";
				echo "<br/>Go back to <a href='http://titan.csit.rmit.edu.au/~s3563242/assignment/client/login.html'>login</a> page";
			}
		?>
	</body>
</html>

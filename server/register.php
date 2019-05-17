<html>
	<body>

		<?php
			
			//Receive username from client side
			$entered_username = $_POST['username'];
			//Receive password from client side
			$entered_password = $_POST['password'];
			
			$register = 0;
			//read users.txt line by line
			foreach(file('../database/users.txt') as $line) {
				//split each line as two parts
				list($username, $entered_password) = explode(",",$line);
				//verify if an exist user with the same username
				if($username == $entered_username){
					$register = 1;
					break;
				}
			}
			
			if($register == 1){
				echo "The user already exists!<br/>";
				echo "<br/><br/>Click here to go back to <a href='http://titan.csit.rmit.edu.au/~s3563242/assignment/client/register.html'>register</a> page";
			}else{
				
				//open a file named "users.txt"
				$file = fopen("../database/users.txt","a");
				//insert this user into the users.txt file
				fwrite($file,$entered_username.",".$entered_password."\n");
				//close the "$file"
				fclose($file);
				echo "The user has been added to the users.txt file<br/>";
			}
			
			echo "<br/><br/>Click here to go to <a href='http://titan.csit.rmit.edu.au/~s3563242/assignment/client/login.html'>login</a> page";
		?>

	</body>
</html>

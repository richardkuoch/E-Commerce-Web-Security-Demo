<?php
	session_start();
	include("rsa.php");
	include('des.php');
?>
<html>
	<head>
		<!--css style for shopping cart-->
		<style>
			table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 400px;
            }
            td,
            th {
                width: 100px;
                text-align: center;
                padding: 8px;
            }
		</style>
		<title>Confirmation Page</title>
	</head>
	<body>
		<h2>Confirmation of Order</h2>
		<table>
			<tr>
                <th>Products</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            <tr>
                <td>
                	Product A
                </td>
                <td>
                	$10
                </td>   
                <td>
                	<?php
                	if(isset($_POST['ProductAquantity'])){
                		echo $_POST['ProductAquantity'];
                	}
                	?>
                </td>
                <td> 
                	<?php
                	if(isset($_POST['ProductAtotal'])){
                		echo "$".$_POST['ProductAtotal'];
                	}
                	?>
                </td>
            </tr>
            <tr>
                <td>
                	Product B
                </td>
                <td>
                	$15
                </td>   
                <td>
                	<?php
                	if(isset($_POST['ProductBquantity'])){
                		echo $_POST['ProductBquantity'];
                	}
                	?>
                </td>
                <td> 
                	<?php
                	if(isset($_POST['ProductBtotal'])){
                		echo "$".$_POST['ProductBtotal'];
                	}
                	?>
                </td>
            </tr>
            <tr>
                <td>
                	Product C
                </td>
                <td>
                	$15
                </td>   
                <td>
                	<?php
                	if(isset($_POST['ProductCquantity'])){
                		echo $_POST['ProductCquantity'];
                	}
                	?>
                </td>
                <td> 
                	<?php
                	if(isset($_POST['ProductCtotal'])){
                		echo "$".$_POST['ProductCtotal'];
                	}
                	?>
                </td>
            </tr>
          <tr>
                <th>Total</th>
                <th></th>
                <th>
                	<?php
                	if(isset($_POST['total'])){
                		echo $_POST['total'];
                	}
                	?>
                </th>
                <th>
                    <?php
                	if(isset($_POST['totalPrice'])){
                		echo "$".$_POST['totalPrice'];
                	}
                	?>
                </th>
            </tr>
        </table>
        <br/>
        <br/>
		<?php

			//Receive user input from client side
            $client = $_SESSION['username']; 
			$productAquantity = $_POST['ProductAquantity'];
			$productBquantity = $_POST['ProductBquantity'];
			$productCquantity = $_POST['ProductCquantity'];
			$totalPrice = $_POST['totalPrice'];
			$creditCard = $_POST['creditCard'];
	
			//set up a key
			$DES_key = $_POST['DES_key'];
			
			echo "Received encrypted DES key: ".$DES_key."<br/><br/>";
	
			// Q4 part c ii. The server retrieves the key with RSA decryption algorithm and keeps the DES key for this user
			$privateKey = get_rsa_privatekey('private.key');
			$key = rsa_decryption($DES_key, $privateKey);
	
			echo "Recovered DES key: ".$key."<br/><br/>";
	
			//decrypt the received message using the key and PHP des decryption AIP
		
			$recovered_creditCard = php_des_decryption($key, $creditCard);

			echo "Received encrypted credit card number: " . $creditCard . "<br/><br/>";

			echo "Recovered credit card number:". $recovered_creditCard . "<br/><br/>";


			//open a file named "orders.txt"
			$file = fopen("../database/orders.txt","a");
			//insert this user into the orders.txt file
			fwrite($file,
				   "--------------------------------------------------"."\n".
                   "Client: ".$client."\n".
				   "ProductA: ".$productAquantity."\n".
				   "ProductB: ".$productBquantity."\n".
				   "ProductC: ".$productCquantity."\n".
				   "Total Price: ".$totalPrice."\n".
				   "Credit Card number: ".$recovered_creditCard."\n");

			//close the "$file"
			fclose($file);
			echo "The order has been added to the orders.txt file<br/>";
            unset($_SESSION);
		?>	
	</body>
</html>

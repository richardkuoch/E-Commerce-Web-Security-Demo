<?php
	session_start();
	if(!isset($_SESSION['login'])){
		header('Location: login.html');
	}
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
		<title>Shopping Cart Page</title>
	</head>
	<body>
		<h2 style="text-indent: 5em;">Shopping Cart</h2>
		<form action="../server/order.php" method="post">
			<table>
				<tr>
                    <th>Products</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
                <tr>
                    <td>Product A
                        <input type="hidden" name="ProductA" id="ProductA" value="ProductA" />
                    </td>
                    <td>$10
                        <input type="hidden" name="ProductAprice" id="ProductAprice" value="10" /></td>
                    <td>
                        <input id="ProductAquantity" name="ProductAquantity" type="number" value="0" min="0" max="10" onchange="updateCart()"/>
                    </td>
                    <td>
                        <p id="ProductAsubtotal">0</p>
                        <input id="ProductAtotal" name="ProductAtotal" type="hidden" value="0" />
                    </td>
                </tr>
                <tr>
                    <td>Product B
                        <input type="hidden" name="ProductB" id="ProductB" value="ProductB" />
                    </td>
                    <td>$15
                        <input type="hidden" name="ProductBprice" id="ProductBprice" value="15" />
                    </td>
                    <td>
                        <input id="ProductBquantity" name="ProductBquantity" type="number" value="0" min="0" max="10" onchange="updateCart()"/>
                    </td>
                    <td>
                        <p id="ProductBsubtotal">0</p>
                        <input id="ProductBtotal" name="ProductBtotal" type="hidden" value="0" />    
                    </td>
                </tr>
                <tr>
                    <td>Product C
                        <input type="hidden" name="ProductC" id="ProductC" value="ProductC" />
                    </td>
                    <td>$20
                        <input type="hidden" name="ProductCprice" id="ProductCprice" value="20" />
                    </td>
                    <td>
                        <input id="ProductCquantity" name="ProductCquantity" type="number" value="0" min="0" max="10" onchange="updateCart()"/>
                    </td>
                    <td>
                        <p id="ProductCsubtotal">0</p>
                        <input id="ProductCtotal" name="ProductCtotal" type="hidden" value="0" />
                    </td>
                </tr>
                <tr>
                    <th>Total</th>
                    <th></th>
                    <th>
                        <p id="Quantity">0</p>
                        <input id="totalQuantity" name="total" type="hidden" value="0" />
                    </th>
                    <th>
                        <p id="Price">0</p>
                        <input id="totalPrice" name="totalPrice" type="hidden" value="0" />
                    </th>
                </tr>
                <tr>
                    <td colspan="4">
                        Credit Card Number:
                        <input type = "text" id="creditCard" name = "creditCard" required />
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"> 
                    	DES Encryption Key: 
                        <input type="text" id="DES_key" name="DES_key" />
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><button type="submit" id="submit"  onclick="encrypt_before_submit()">Submit</button></td>
                </tr>
			</table>
		</form>
		
		<script type="text/javascript" src="js/rsa.js"></script>
		<script type="text/javascript" src="js/sha256.js"></script>
		<script type="text/javascript" src="js/des.js"></script>
		
		<script type="text/javascript">
			
			function encrypt_before_submit(){
				
				var DES_key = document.getElementById("DES_key").value;
				var encrypted_DES_key = RSA_encrypt(DES_key);
				document.getElementById("DES_key").value = encrypted_DES_key;
				
				var creditCard = document.getElementById("creditCard").value;
				var encrypted_creditCard = javascript_des_encryption(DES_key, creditCard);
				document.getElementById("creditCard").value = encrypted_creditCard;
			}
			
			
			function RSA_encrypt(message){
	
				var public_key = "-----BEGIN PUBLIC KEY-----MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzdxaei6bt/xIAhYsdFdW62CGTpRX+GXoZkzqvbf5oOxw4wKENjFX7LsqZXxdFfoRxEwH90zZHLHgsNFzXe3JqiRabIDcNZmKS2F0A7+Mwrx6K2fZ5b7E2fSLFbC7FsvL22mN0KNAp35tdADpl4lKqNFuF7NT22ZBp/X3ncod8cDvMb9tl0hiQ1hJv0H8My/31w+F+Cdat/9Ja5d1ztOOYIx1mZ2FD2m2M33/BgGY/BusUKqSk9W91Eh99+tHS5oTvE8CI8g7pvhQteqmVgBbJOa73eQhZfOQJ0aWQ5m2i0NUPcmwvGDzURXTKW+72UKDz671bE7YAch2H+U7UQeawwIDAQAB-----END PUBLIC KEY-----";		
			
				var encrypt = new JSEncrypt();
				encrypt.setPublicKey(public_key);
				var encrypted = encrypt.encrypt(message);
			  
				return encrypted;	
			}
			
		</script>
		
		
        <script type="text/javascript">
            
            var calcSubTotal = function(productName) {

                var quantity = parseInt(document.getElementById(productName + 'quantity').value);
                if (quantity > 0) {
                    var price = parseInt(document.getElementById(productName + 'price').value);
                    var subtotal = price * quantity;
                    document.getElementById(productName + "subtotal").innerHTML = subtotal;
                    document.getElementById(productName + "total").value = subtotal;
                    return subtotal;
                }
                document.getElementById(productName + "subtotal").innerHTML = 0;
                document.getElementById(productName + "total").value = 0;
                return 0;
            }
            
            function updateCart() {

                var total = calcSubTotal('ProductA') + calcSubTotal('ProductB') + calcSubTotal('ProductC');

                var quantity = parseInt(document.getElementById('ProductAquantity').value) + parseInt(document.getElementById('ProductBquantity').value) + parseInt(document.getElementById('ProductCquantity').value);

                document.getElementById("Quantity").innerHTML = quantity;
                document.getElementById("totalQuantity").value = quantity;

                document.getElementById("Price").innerHTML = total;
                document.getElementById("totalPrice").value = total;

            }
            
        </script>
	</body>
</html>

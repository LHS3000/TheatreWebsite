<?php
	// The connection to the SQL database is made and the session is started
	require("connect.php");
	$conn = myconnect();
	
	session_start();
	// Session variables are stored in standard php variables
	$PerfDate = $_SESSION['PerfDate'];
	$PerfTime = $_SESSION['PerfTime'];
	$title = $_SESSION["title"];
?>
<!DOCTYPE html>
<html>
	<!--The same head and body (with title and images) are put in but without text beneath as an if statement will be used-->
	<head>
		<link rel="stylesheet" type="text/css" href="mystyles.css">
		<title>The Globule Theatre</title>
		<meta name = "The Globule Theatre" 
		content = "Welcome to the Globule, where there's no fear-only theatre.">
	</head>
	<body>
		<div class="div-left"></div>
		<div class="div-right"></div>
		<h1>The Globule Theatre</h1>
		<div class="div-img"><img class="img-theatre" src="images/theatre.jpg" alt = "Missing image of a theatrical production." width="148" height="133"></div>
		<?php 
			// This conditional determines if a seat has been selected and assigns a value to the variable noSeat so that it can be used
			// in the second conditional.			
			if(isset($_POST['seat'])){
				$noSeat = 1;
			}
			else{
				$noSeat = 0;
			}
			// This if else conditional is used to dictate the behaviour of the rest of the page-working as a check on if an email 
			// has been added and seats have been booked.
			if ($_POST["email"] == "" || $noSeat == 0){ 
			?>
				<!--ERROR MESSAGE PATH, with no seat or email selected the user should be directed to this text.-->
				<h2>Booking error</h2>
					<div class="div-err">
						<p class="err">
						Unfortunately we have not been able to make your booking.<br><br>
						Please return to our home page attempt your order again, making sure to select the seat(s) you wish to book and that you enter an email address. <br>
						You can use the "check order summary" button to check your order details are correct before attempting to book again.<br><br>
						<!--In earlier version of this project there was a bug where if you press back on the browser the seats.php page 
						would not always output correctly so I put in the below warning message to mitigate this.-->
						<span>PLEASE DO NOT USE THE BACK BUTTON ON YOUR BROWSER!</span> This may cause errors in our booking system.<br>
						To try again from our "What's on?" index page <a class="homeErr" href="index.php">please click here.</a></p>
						</p>
					</div>
					<?php
			}
			else{ ?>
				<!--BOOKING PATH: this path is followed when at least one seat checkbox is ticked and 
				there has been some input in the email text field-->
				<h2>Thank you for your booking!</h2>
				<p class="inbox">Your booking of the <?php echo $PerfDate;?> showing of <?php echo $title;?> at <?php echo $PerfTime;?> was successful. <br>
				Please see confirmation of your booking details below:<br></p>
					<!--Table storing the booked seats and their prices.-->
					<table>
						<tr>
							<th>Selected Seat</th><th>Seat Price</th>
						</tr>
						<?php
						// I used htmlspecialchars on the email to try to make the database more secure.
						$email = htmlspecialchars($_POST["email"]);
						$email = htmlspecialchars($email);
						// Total price variable is initialised 
						$totalPrice = 0;
						//The below loop has several functions: displaying the table of booked seats, inserting the booking into the 
						// booking table on the database and adding the price of each seat to the total price variable.
						foreach($_POST['seat'] as $seatNo=>$seatPrice){
							echo "<tr>"."<td>".$seatNo."</td>"."<td>£".$seatPrice."</td>";
								
							$sql = "INSERT INTO Booking VALUES (('".$email."'),('".$PerfDate."'),('".$PerfTime."'),('".$seatNo."'));";
							$handle = $conn->prepare($sql);
							$handle->execute();
								
							$totalPrice += $seatPrice;
						}
						// Ends connection to the database outside the loop.
						$conn = null;
						?>
					</table>
					<!--Further summary of the booking is given below along with a link back to the index page -->
					<div class="div1">
						<p class="inbox">
						Your email was stored as: <?php echo $email; ?>. <br>
						The total price of your order is: £<?php echo number_format($totalPrice, 2); ?>.
						<a class="home" href="index.php">Click here to return to our home page.</a></p>
						</p>
					</div>
				<?php 
				}
				?>
	</body>
</html>
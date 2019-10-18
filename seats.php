<?php
	session_start();
	// The session is started and the session information is stored into variables for easy access.
	$title = $_SESSION["title"];
	$BasicTicketPrice = $_SESSION["BasicTicketPrice"];
	$PerfDate = $_SESSION['PerfDate'];
	$PerfTime = $_SESSION['PerfTime'];
?>
<!DOCTYPE html>
<html>
	<!--The same information with customised text is put in.-->
	<head>
		<link rel="stylesheet" type="text/css" href="mystyles.css">
		<title>The Globule Theatre</title>
		<meta name = "The Globule Theatre" 
		content = "Welcome to the Globule, where there's no fear-only theatre.">
	</head>
	<body>
		<div class="div-left2"></div>
		<div class="div-right2"></div>
		<h1><a name="top" class="navOffset">Anchor point</a>The Globule Theatre</h1>
		<div class="div-img"><img class="img-theatre" src="images/theatre.jpg" alt = "Missing image of a theatrical production." width="148" height="133"></div>
		<h2>Seats for the <?php echo $PerfDate;?> performance of <?php echo $title;?> at <?php echo $PerfTime;?></h2>
			<p>Please see our below list the remaining available seats for your selected performance of <?php echo $title;?>.<br>
			You can click the below links to navigate to a seating zone<br>
			Or <a class="home" href="index.php">click here to return to our home page.</a></p>
			</p>
			<!--The below div holds a navigation bar with buttons acting as links to the rest of the page.-->
			<div id="navbar">
				<a href="#top">top</a>
				<a href="#balcony">balcony</a>
				<a href="#box 1">box 1</a>
				<a href="#box 2">box 2</a>
				<a href="#box 3">box 3</a>
				<a href="#box 4">box 4</a>
				<a href="#front stalls">front stalls</a>
				<a href="#rear stalls">rear stalls</a>
				<a href="#booking">complete booking</a>
			</div>
	<!--Below is the rest of the page forming a very long table so this is stored in a content div for the navigation bar.
	This is followed by the SQL query to populate the table with the remaining available seats for the selected performance.-->
	<div class="content">
				<?php
					require("connect.php");
					$conn = myconnect();

					$sql = "SELECT Seat.RowNumber, Seat.Zone, Round((Zone.PriceMultiplier * ('".$BasicTicketPrice."')), 2) AS Price
							FROM Seat, Zone 
							WHERE Zone.Name=Seat.Zone 
							AND Seat.RowNumber NOT IN 
							(SELECT Booking.RowNumber FROM Booking 
							WHERE Booking.PerfTime=('".$PerfTime."') 
							AND Booking.PerfDate=('".$PerfDate."'))";
		
					$handle = $conn->prepare($sql);
					$handle->execute();
					$conn = null;
					$show = $handle->fetchAll();
				?>
				<!--On this page the form is held outside the loop as it needs to hold all the checkboxes in the table and an email field underneath.
				It also uses post instead of storing the information in a session (I tried this before with inconsistent results...)-->
				<form name="form3" action = "book.php" method="post">
				<table>
							<?php
							// The below zone variable will be used to check if a new the is needed in the table for a seating zone.
							// Should the current seat in the loop not have the same value as the zone variable a heading is dynamically
							// generated along with an anchor tag allowing the navigation bar to find it.
							$zone = null;
							foreach($show as $row){ 
								if($zone!== $row['Zone']){
								?>
									<tr ><th class="zoneHead" colspan="3">
									<a name="<?php echo $row['Zone'] ?>" class="navOffset">Anchor point</a><?php echo $row['Zone'] ?>
									</th></tr>
									<tr><th>Seat number</th><th>Seat Price</th><th>Tick to select seat</th></tr>
								<?php	
								}
								?>
								<!--This portion populates the table with seats and checkboxes. Each checkbox takes the seat as its name
								and the price as its value, within a an array called seat, so that these can be looped through on the book page. -->
								<tr><td><?php echo $row['RowNumber'];?></td><td>£<?php echo $row['Price'];?></td>
									<td>
									<input type="checkbox" class="seat" name="seat[<?php echo $row['RowNumber']; ?>]" value="<?php echo $row['Price']; ?>" />														
									</td>
								</tr>
							<?php
								$zone = $row['Zone'];
							} ?>
					</table>
					<!--The end of the form has a field for the user's email, another hidded anchor tag for the navigation bar and 
					a button to activate the javascript which checks the order and returns an alert window.-->
					<div class="div1">
						<br>
						<p class="inbox"><a name="booking" class="navOffset">Anchor point</a>Please input your email address:
							<input type="text" name="email" name="bookerName" value="">
							<button class="click" type = "button" onclick=check() />Check order summary</button>
							<input class="click" type = "submit" value = "Make booking" name = "submit"/>
						</P>
					</div>
				</form>
	</div>
	<!--The below javascript creates the alert window for the check order summary button.-->
		<script>
			function check(){
				// Variable for number of seats booked
				var length = 0;
				// Variable for total price
				var totalPrice= 0;
				//Variable for a string of the selected seats 
				var bookedString = "";
				// Array is initialised to hold selected seats
				var seat = document.getElementsByClassName("seat");
					// A loop runs through the seats and adds them to bookedString
					for(var counter = 0; seat[counter]; counter++){
						if(seat[counter].checked){
							bookedString += seat[counter].name + ", ";
							// var length is incremented with each seat
							length++;
						}
					}
				// Another array holds the seats to be iterated through
				var seatPrice = document.getElementsByClassName("seat");
					// A loop adds up the total price
					for(var counter = 0; seat[counter]; counter++){
						if(seat[counter].checked){
							totalPrice += parseFloat(seatPrice[counter].value);
						}
					}
						// A conditional changes one of the strings returned depending on if seats are booked or not
						if (length < 1){
							var bookingCheck = "Please select at which seat(s) you wish to book.";
						}
						else{
							var bookingCheck = "You have selected: " + bookedString + "\n the total price is: £" + totalPrice.toFixed(2) + ".";
						}
				// A conditional changes the other string returned depending on if an email has been inputted
				var userEmail = document.forms["form3"]["email"].value;
					if (userEmail == ""){
						var emailCheck = "Please enter a valid email address.";
					}
					else{
						var emailCheck = "Your email address is: " + userEmail + ".";
					}
				window.alert(emailCheck + "\n" + bookingCheck);
			}
		</script>
		<!-- NOTE: The below javascript code for a navigation bar was taken from "https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_navbar_sticky"
		I modified the corresponding CSS(changing styling, nav bar width, button types, scroll style, adjusting where scrolling stopped to match 
		seating zones) and HTML (inserting hidden anchor tags and a series of href links in the navbar) but as per the university's plagiarism
		policy I cannot take credit for the below javascript.-->
		<script>
				window.onscroll = function() {myFunction()};

				var navbar = document.getElementById("navbar");
				var sticky = navbar.offsetTop;

				function myFunction() {
					if (window.pageYOffset >= sticky) {
					navbar.classList.add("sticky")
				} else {
					navbar.classList.remove("sticky");
					}
				}
			
		</script>	
	</body>
</html>
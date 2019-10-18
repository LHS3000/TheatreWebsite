<?php
	session_start();
	// started a session and gained access to the title variable from the previous page.
	$title = $_SESSION["title"];
?>
<!DOCTYPE html>
<html>
	<!--The same formatting is present as the index page.-->
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
		<h2>Performance schedule for <?php echo $title;?></h2>
			<p>We have performances of the show <?php echo $title;?> on the following dates.<br>
			Click the links for a list of available seats for your selected performance.<br>
			Or <a class="home" href="index.php">click here to return to our home page.</a></p>
			<!--A link is added above for quick access to the index page-this is featured in all subsequent pages. 
			Below is the SQL query followed by another dynamically populated table with form submit buttons and hidden inputs.-->	
				<?php
					require("connect.php");
					$conn = myconnect();

					$sql = "SELECT * FROM Performance WHERE Performance.Title = ('".$title."')";
					$handle = $conn->prepare($sql);
					$handle->execute();
					$conn = null;
					$show = $handle->fetchAll();
				?>
						<table>
							<tr>
								<th>Production Name</th><th>Performance Date</th><th>Performance Time</th><th>Click for available seats</th>
							</tr>
							<?php foreach($show as $row){ ?>
									<tr>
										<td><?php echo $row['Title']; ?></td><td><?php echo $row['PerfDate']; ?></td><td><?php echo $row['PerfTime']; ?></td>
										<td>
											<form action = <?php echo $_SERVER['PHP_SELF']; ?> method="post">
												<input type="hidden" name="PerfDate" value="<?php echo $row['PerfDate'] ?>" />
												<input type="hidden" name="PerfTime" value="<?php echo $row['PerfTime'] ?>" />
												<input class="click" type = "submit" value = "Show availability" name = "submit"/>
											</form>
										</td>
									</tr> 
								<?php } ?>
						</table>
				<?php  
					// The new information about the user's selection (the performance date and time) is passed on by the php session.
					if(isset($_POST['submit'])){
						$_SESSION["PerfDate"] = $_POST['PerfDate'];
						$_SESSION["PerfTime"] = $_POST['PerfTime'];
	
						header('Location: seats.php');
					}
				?>
			</table>
	</body>
</html>
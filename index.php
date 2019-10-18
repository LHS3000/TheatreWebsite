<?php
	session_start();
	
	//started a session so I can transfer the selected show title to other pages.
?>
<!DOCTYPE html>
<html>
	<head>
		<!--The below section is copied to all the pages and sets up the head with the standard information and
		a body with a title, a centred image, two div boxes containing the same tiled border image followed by
		some text explaining the page.-->
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
		<h2>What's on?</h2>
			<p>Welcome to the Globule, it's like the Globe theatre but smaller!<br>
			Please see our below list of shows for this season's exciting program.<br>
			Click the links for a list of the available performances.</p>
			<!--This section calls on the myconnect function to access the SQL database.-->
				<?php
					require("connect.php");
					$conn = myconnect();

					$sql = "SELECT * FROM Production;";
					$handle = $conn->prepare($sql);
					$handle->execute();
					$conn = null;
					$show = $handle->fetchAll();?>
					<!--This section dynamically populates a table with two columns showing the production title and basic ticket price
					and a third column containing a submit button to access the details of that show.-->
					<table>
						<tr>
							<th>Production Name</th><th>Tickets Prices From</th><th>Click for showtimes</th>
						</tr>
						<?php 
						foreach($show as $row){ ?>
							<tr><td><?php echo $row['Title']; ?></td><td>£ <?php echo $row['BasicTicketPrice']; ?></td>
								<td>
									<!--Each submit button holds a form with hidden inputs-these are posted back to this index.php page
									then stored in sessions to pass to the other pages.-->
									<form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
										<input class="invisible" type="hidden" name="title" value="<?php echo $row['Title']; ?>" />
										<input class="invisible" type="hidden" name="BasicTicketPrice" value="<?php echo $row['BasicTicketPrice'] ?>" />
										<input class="click" type = "submit" value = "Show availability" name = "submit"/>
									</form>
								</td>
							</tr>
						<?php	
						}  
						?>
					</table>
		<?php
			if(isset($_POST['submit'])){
				$_SESSION["title"] = $_POST['title'];
				$_SESSION["BasicTicketPrice"] = $_POST['BasicTicketPrice'];
	
				header('Location: perf.php');
			}
		?>
	</body>
</html>
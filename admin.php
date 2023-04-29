<?php session_start(); // Startar en session, för att kunna använda sessioner på denna sidan ?> 
<?php include "header.php" ?>

<?php 
	// Kollar om man har en giltig inloggning, om man har det så visas admin-funktionerna.
	if(isset($_SESSION['loggedin'])){
		
		// Inkluderar en administrationsmeny för lättare administration
		include "adminnav.php" ;

		// Hämtar in dagens datum
		$todayDate = date("Y-m-d");

		// FÖRSTA TABELLEN - Event som inte varit
	
		// Skriver ut funktionen av delen av sidan
		echo "<h2>Calender upcoming events</h2>";
		
		// Förbereder frågan till databasen, hämtar endast event som ännu inte varit och listar dem i ordning efter datum.
		$sql = "SELECT * FROM `Events` WHERE `Date` >= '$todayDate'
		ORDER BY `Date` ASC";

		// Kopplar upp mot databasen
		include "dbconnect.php";      

		// Ställer frågan till databasen
		$result = mysqli_query($dbconnect, $sql);

		// Om något går fel vid frågeställningen
		if(!$result) {
			die("Kunde inte utföra frågan, avslutar...");
		} 
			
		// Förbereder tabellen
		echo "<table class='table ms-5 mb-5 table-hover my-table'> <tr>"; 
		echo "<th>Event</th>"; 
		echo "<th>Date</th>"; 
		echo "<th>Start time</th>"; 
		echo "<th>End time</th>"; 
		echo "<th>Location</th> </tr>"; 

		// Loop som hämtar och skriver ut alla event som ännu inte varit.
		while( $row = mysqli_fetch_array($result))
		{
			$Event_id = $row['Event_id'];
			$Event = $row['Event'];
			$Date = $row['Date'];
			$Start_time = $row['Start_time']; 
			$End_time = $row['End_time'];
			$Location = $row['Location'];
			
			// Skriver ut informationen i tabellen
			echo "<tr>
			<td>$Event</td>
			<td>$Date</td> 
			<td>$Start_time</td> 
			<td>$End_time</td> 
			<td>$Location</td> 
			</tr>";	
		}
			echo "</table>"; 
			echo "<hr>";  



			
			// ANDRA TABELLEN - Event som varit

			

			// Förbereder frågan till databasen, hämtar endast event som har varit. 
			$sqlpassed = "SELECT * FROM `Events` WHERE `Date` < '$todayDate'
			ORDER BY `Date` DESC";
		
			// Ställer frågan till databasen
			$resultpassed = mysqli_query($dbconnect, $sqlpassed);
		
			// Om något går fel vid frågeställningen
			if(!$resultpassed) {
				die("Kunde inte utföra frågan, avslutar...");
			} 
				
			// Om det finns event som varit, så visas de här. Annars visas ingen tabell
			if(mysqli_num_rows($resultpassed) != 0){
				
				// Skriver ut funktionen av delen av sidan
				echo "<h2>Calender former events</h2>";
				
				// Förbereder tabellen
				echo "<table class='table ms-5 mb-5 table-hover my-table'> <tr>"; 
				echo "<th>Event</th>"; 
				echo "<th>Date</th>"; 
				echo "<th>Start time</th>"; 
				echo "<th>End time</th>"; 
				echo "<th>Location</th> </tr>"; 

				// Loop som hämtar och skriver ut alla event som har varit.
				while( $row2 = mysqli_fetch_array($resultpassed) )
				{
					$passed_Event_id = $row2['Event_id'];
					$passed_Event = $row2['Event'];
					$passed_Date = $row2['Date'];
					$passed_Start_time = $row2['Start_time']; 
					$passed_End_time = $row2['End_time'];
					$passed_Location = $row2['Location'];
					
					// Skriver ut informationen i tabellen
					echo "<tr>  
					<td>$passed_Event</td>
					<td>$passed_Date</td> 
					<td>$passed_Start_time</td> 
					<td>$passed_End_time</td> 
					<td>$passed_Location</td> 
					</tr>";		
				}

				echo "</table>"; 
				echo "<hr>";  

			}

			//Stänger ner koppling till databasen
			mysqli_close($dbconnect);   
		}

	
	
	// Om man inte har en giltig inloggning får man ett felmeddelande, och en länk till inloggningssidan.
	else {
			echo "<h2> You are not logged in. </h2>
			<a href='login.php' class='m-5 btn btn-secondary'>Login here </a>"; 
		
	}
?>
<?php include "footer.php" ?>
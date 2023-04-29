<?php session_start(); // Startar en session, för att kunna använda sessioner på denna sidan ?> 
<?php include "header.php" ?>

<?php
    // Kollar om man har en giltig inloggning, om man har det så visas admin-funktionerna.
    if(isset($_SESSION['loggedin'])){
	  
        // Inkluderar en administrationsmeny för lättare administration
        include "adminnav.php" ;
    
        // Skriver ut funktionen av sidan
        echo "<h2>Add Events</h2>";

        // Man kan komma till filen på två sätt
        // 1. Man har fyllt i formuläret, och vill lägga till eventet(i databasen).
        // 2. Man vill lägga till event, och får fram ett formulär


        //Skapar en funktion som skapar ett formulär där man kan fylla i information om eventet. 
        function makeForm(){

            echo "<form action='add.php' method='post'>";
            echo "<table class='table ms-5 mb-5 table-hover' id='my-formtable'>";
            echo "<tr><td>Event name</td> <td><input type='text' name='Event' placeholder='Event name' /> </td> </tr> ";
            echo "<tr><td> Date </td> <td><input type='text' name='Date' placeholder='YYYY-MM-DD' /> </td> </tr> ";
            echo "<tr><td> Start Time </td> <td><input type='text' name='Start_time' placeholder='HH:MM'/> </td> </tr> ";
            echo "<tr><td> End Time </td> <td><input type='text' name='End_time' placeholder='HH:MM' /> </td> </tr> ";
            echo "<tr><td> Location </td> <td><input type='text' name='Location' placeholder='Location' /> </td> </tr> ";
            echo "<tr><td><input type='submit' Value='Add Event' /> <input type='hidden' name='want_to_add_event' value='1' /> </td> <td></td> </tr> ";
            echo "</table> </form>";
        }

        

        // 1. Man har fyllt i formuläret, och vill lägga till eventet (i databasen).
        // Om variablen "want_to_add_event" är satt, och är lika med 1 betyder det att användaren har fyllt i formuläret.
        if(isset($_POST['want_to_add_event'])){
            if($_POST['want_to_add_event'] == "1"){

                // Nollställer och tar bort variablen
                $_POST['want_to_add_event'] = "0";
                unset ($_POST['want_to_add_event']);

                // Kopplar upp mot databasen
                include "dbconnect.php";

                // Hämtar in alla data från tabellen(skickade som post-variabler), htmlentities motverkar mot felaktiga tecken. 
                // Skyddas även mot SQL-injektioner med mysqli_real_escape_string.
                $Event = htmlentities ($_POST['Event']);
                $Event = mysqli_real_escape_string($dbconnect,$Event);
                $Date = htmlentities ($_POST['Date']);
                $Date = mysqli_real_escape_string($dbconnect,$Date);
                $Start_time = htmlentities ($_POST['Start_time']);
                $Start_time = mysqli_real_escape_string($dbconnect,$Start_time);
                $End_time = htmlentities ($_POST['End_time']);
                $End_time = mysqli_real_escape_string($dbconnect,$End_time);
                $Location = htmlentities ($_POST['Location']);
                $Location = mysqli_real_escape_string($dbconnect,$Location);

                // Förbereder frågan till databasen.
                $sql = "INSERT INTO `Events`(`Event`, `Date`, `Start_time`, `End_time`, `Location`) 
                VALUES ('$Event','$Date','$Start_time','$End_time','$Location')";

                //Ställer frågan till databasen.
                $result = mysqli_query($dbconnect, $sql);

                // Om något går fel vid frågeställningen 
                if(!$result) {
                    die("Kunde inte utföra frågan, avslutar...");
                } 

                //Man meddelar att eventet är tillagt, och länkar vidare till admin-sidan.  
                echo "<h4 class='m-3'>You have added this event to the calender: $Event.</h4>";
                echo "<form action='admin.php'> <input class='btn-secondary btn m-5' type='submit' Value='Continue' /> </form>";
                
                //Stänger ner koppling till databasen
                mysqli_close($dbconnect);  

            }
        }

        // 2. Man vill lägga till event, och får fram ett formulär
        else{
           // Funktion som skriver ut formuläret anropas.
           makeForm();
        }
    }   
    
    // Om man inte har en giltig inloggning får man ett felmeddelande, och en länk till inloggningssidan.
    else{
		echo "<h2> You are not logged in. </h2>
        <a href='login.php' class='m-5 btn btn-secondary'>Login here </a>"; 
	}

?>
<?php include "footer.php" ?>
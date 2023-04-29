<?php session_start(); // Startar en session, för att kunna använda sessioner på denna sidan ?> 
<?php include "header.php" ?>

<?php 

    // Kollar om man har en giltig inloggning, om man har det så visas admin-funktionerna.
    if(isset($_SESSION['loggedin'])){
        
        // Inkluderar en administrationsmeny för lättare administration
        include "adminnav.php" ;
    
        // Skriver ut funktionen av sidan
        echo "<h2>Update Upcoming Event</h2>";

        // Man kan komma till filen på tre sätt 
        // 1. Man får en vy att editera eventet med textrutor
        // 2. Man utför förändingen i databasen och får ett svarsmeddelande
        // 3. Man har inte besökt sidan innan, och väljer därför vilket event man vill uppdatera

        //Skriver en funktion som visar alla eventen för föreningen
        function showEvents(){

            // Kopplar upp mot databasen
            include "dbconnect.php";   

            // Hämtar in dagens datum
            $todayDate = date("Y-m-d");

            // Förbereder frågan till databasen, hämtar endast event som ännu inte varit och listar dem i ordning efter datum. 
            // De passerade eventen syns inte på grund att dem anser jag att man inte behöver uppdatera.
            $select = "SELECT * FROM `Events` WHERE `Date` >= '$todayDate'
            ORDER BY `Date` ASC";

            // Ställer frågan till databasen
            $result = mysqli_query($dbconnect, $select);

            // Om något går fel vid frågeställningen
            if(!$result) {
                die("Kunde inte utföra frågan, avslutar...");
            } 

            // Förbereder tabellen
            echo "<table class='table ms-5 mb-5 table-hover my-table'> <tr>"; 
            echo "<th></th>";
            echo "<th>Event</th>"; 
            echo "<th>Date</th>"; 
            echo "<th>Start time</th>"; 
            echo "<th>End time</th>"; 
            echo "<th>Location</th> </tr>"; 

            // Loop som hämtar och skriver ut alla event.
            while($row = mysqli_fetch_array($result)){

                $Event_id = $row['Event_id'];
                $Event = $row['Event'];
                $Date = $row['Date'];
                $Start_time = $row['Start_time']; 
                $End_time = $row['End_time'];
                $Location = $row['Location'];

                // Skriver ut informationen i tabellen. All information sparas i POST-variabler vis submit av knappen
                echo "<tr>";
                echo "<td>";
                echo "<form action ='update.php' method='post'>";
                echo "<input type='hidden' name='want_to_update_event' value='1' />";
                echo "<input type='hidden' name='Event_id' value='$Event_id' />";
                echo "<input type='hidden' name='Event' value='$Event' />";
                echo "<input type='hidden' name='Date' value='$Date' />";
                echo "<input type='hidden' name='Start_time' value='$Start_time' />";
                echo "<input type='hidden' name='End_time' value='$End_time' />";
                echo "<input type='hidden' name='Location' value='$Location' />";
                echo "<input type='submit' value='Update' />";
                echo "</form>";
                echo "</td>";
                echo "<td>$Event</td>";
                echo "<td>$Date</td>";
                echo "<td>$Start_time</td>";
                echo "<td>$End_time</td>";
                echo "<td>$Location</td>";
                echo "</tr>";	
            }
            echo "</table>"; 

            // Stänger ner koppling till databasen 
            mysqli_close($dbconnect);

        }

        // 1. Man får en vy att editera eventet med textrutor
        // Om variablen "want_to_update_event" är satt, och är lika med 1 betyder det att användaren har valt event att uppdatera.
        if(isset($_POST['want_to_update_event'])){
            if($_POST['want_to_update_event'] == "1"){

                // Nollställer variablen
                $_POST['want_to_update_event'] = "0"; 
                unset ($_POST['want_to_update_event']);


                //Kopplar upp mot databasen
                include "dbconnect.php"; 

                // Hämtar in alla data från tabellen(skickade som post-variabler), htmlentities motverkar mot felaktiga tecken. 
                // Skyddar även mot SQL-injektioner med mysqli_real_escape_string.
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
                $Event_id = mysqli_real_escape_string($dbconnect,$_POST['Event_id']);
                $Event_id = intval ($Event_id);
                

                // Skapar ett formulär, där de hämtade värdena skrivs ut för att kunna editeras. Eventets id skickas med som variabel. 
                echo "<form action='update.php' method='post'>";
                echo "<table class='table ms-5 mb-5 table-hover' id='my-formtable'>";
                echo "<tr><td>Event name</td> <td><input type='text' name='Event' value='$Event' /> </td> </tr> ";
                echo "<tr><td> Date </td> <td><input type='text' name='Date' value='$Date' /> </td> </tr> ";
                echo "<tr><td> Start Time </td> <td><input type='text' name='Start_time' value='$Start_time' /> </td> </tr> ";
                echo "<tr><td> End Time </td> <td><input type='text' name='End_time' value='$End_time' /> </td> </tr> ";
                echo "<tr><td> Location </td> <td><input type='text' name='Location' value='$Location' /> </td> </tr> ";
                echo "<tr><td><input type='submit' Value='Save' />  ";
                echo "<input type='hidden' name='has_been_updated' value='1' />";
                echo "<input type='hidden' name='to_be_updated' value='$Event_id' /> </td> <td> </td> </tr> ";
                echo "</table> </form>";

                //Stänger ner koppling till databasen
                mysqli_close($dbconnect);
            }

        }

        // 2. Man utför förändingen i databasen och får ett svarsmeddelande
        // Om variablen "has_been_updated" är satt, och är lika med 1 betyder det att användaren har uppdaterat eventet.
        else if(isset($_POST['has_been_updated'])){
            if($_POST['has_been_updated'] == "1"){
                
                //Nollställer variablen att man vill uppdatera.
                $_POST['has_been_updated'] = "0";
                unset ($_POST['has_been_updated']); 
                
                //Kopplar upp mot databasen
                include "dbconnect.php"; 

                // Hämtar in all uppdaterad data från formuläret, htmlentities motverkar mot felaktiga tecken. 
                // Skyddar även mot SQL-injektioner med mysqli_real_escape_string.
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
                $toEdit = mysqli_real_escape_string($dbconnect,$_POST['to_be_updated']);
                $toEdit = intval ($toEdit);
                
                // Förbereder frågan till databasen.
                $sql = "UPDATE `Events` SET `Event`='$Event',`Date`='$Date',`Start_time`='$Start_time',
                `End_time`='$End_time',`Location`='$Location' WHERE `Event_id`= '$toEdit' ";

                
                // Ställer frågan till databasen.
                $result = mysqli_query($dbconnect, $sql);

                // Om något går fel vid frågeställningen
                if(!$result) {
                    die("Kunde inte utföra frågan, avslutar...");
                } 

                // Man meddelar användaren att eventet är borttaget, och länkar vidare till admin-sidan. 
                echo "<h4 class='m-3'>You have updated this event from the calender: $Event. </h4>";
                echo "<form action='admin.php'> <input class='btn-secondary btn m-5' type='submit' Value='Continue' /> </form>";
                
                //Stänger ner koppling till databasen 
                mysqli_close($dbconnect); 
            }    
        }


        // 3. Man har inte besökt sidan innan, och väljer därför vilket event man vill uppdatera
        else{
                // Anropar funktionen som visar tabell med alla event. 
                showEvents();
        }


    }

    // Om man inte har en giltig inloggning får man ett felmeddelande, och en länk till inloggningssidan.
    else{
        echo "<h2> You are not logged in. </h2><a href='login.php' class='m-5 btn btn-secondary'>Login here </a>"; 
    }

?>
<?php include "footer.php" ?>
<?php session_start(); // Startar en session, för att kunna använda sessioner på denna sidan ?> 
<?php include "header.php" ?>

<?php 

    // Kollar om man har en giltig inloggning, samt att man har rätt behörighet. Om man har det så visas admin-funktionerna.
    if(isset($_SESSION['loggedin']) && $_SESSION['admin_level'] == 2){
        
        // Inkluderar en administrationsmeny för lättare administration
        include "adminnav.php" ;
        
        // Skriver ut funktionen av sidan
        echo "<h2>Delete Events</h2>";

        // Skapar en funktion som visar alla eventen
        function showEvents(){

            // Kopplar upp mot databasen
            include "dbconnect.php";    

            // Hämtar in dagens datum
            $todayDate = date("Y-m-d");

            // FÖRSTA TABELLEN - Event som inte varit

            // Förbereder frågan till databasen, hämtar endast event som ännu inte varit och listar dem i ordning efter datum.
            $select = "SELECT * FROM `Events` WHERE `Date` >= '$todayDate'
            ORDER BY `Date` ASC";

            // Ställer frågan till databasen
            $result = mysqli_query($dbconnect, $select);

            // Om något går fel vid frågeställningen
            if(!$result) {
                die("Kunde inte utföra frågan, avslutar...");
            } 

            // Förbereder tabellen
            echo "<h4 class='ms-5 mt-4'>Upcoming Events</h4>";
            echo "<table class='table ms-5 mb-5 table-hover my-table'> <tr>"; 
            echo "<th></th>";
            echo "<th>Event</th>"; 
            echo "<th>Date</th>"; 
            echo "<th>Start time</th>"; 
            echo "<th>End time</th>"; 
            echo "<th>Location</th> </tr>"; 

                // Loop som hämtar och skriver ut alla event som ännu inte varit.
            while( $row = mysqli_fetch_array($result) )
            {
                    $Event_id = $row['Event_id'];
                    $Event = $row['Event'];
                    $Date = $row['Date'];
                    $Start_time = $row['Start_time']; 
                    $End_time = $row['End_time'];
                    $Location = $row['Location'];

                    // Skriver ut informationen i tabellen
                    echo "<tr>";
                    echo "<td>";
                    echo "<form action ='delete.php' method='post'>";
                    echo "<input type='hidden' name='want_to_delete_event' value='1' />";
                    echo "<input type='hidden' name='delete_this_event' value='$Event_id' />";
                    echo "<input type='hidden' name='name_deleted_event' value='$Event' />";
                    echo "<input type='submit' value='Delete' />";
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


            // ANDRA TABELLEN - Event som varit

            // Förbereder frågan till databasen, hämtar endast event som har varit.
            $selectpassed = "SELECT * FROM `Events` WHERE `Date` < '$todayDate'
            ORDER BY `Date` ASC";

            // Ställer frågan till databasen
            $resultpassed = mysqli_query($dbconnect, $selectpassed);

            // Om något går fel vid frågeställningen
            if(!$resultpassed) {
                die("Kunde inte utföra frågan, avslutar...");
            } 

            // Om det finns event som varit, så visas de här. Annars visas ingen tabell
            if(mysqli_num_rows($resultpassed) != 0){

                // Skriver ut funktionen av delen av sidan
                echo "<h4 class='ms-5 mt-4'>Former Events</h4>";

                // Förbereder tabellen
                echo "<table class='table ms-5 mb-5 table-hover my-table'> <tr>"; 
                echo "<th></th>";
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

                        //skriver ut informationen i tabellen, och skickar med postvariabler.
                        echo "<tr>";
                        echo "<td>";
                        echo "<form action ='delete.php' method='post'>";
                        echo "<input type='hidden' name='want_to_delete_event' value='1' />";
                        echo "<input type='hidden' name='delete_this_event' value='$passed_Event_id' />";
                        echo "<input type='hidden' name='name_deleted_event' value='$passed_Event' />";
                        echo "<input type='submit' value='Delete' />";
                        echo "</form>";
                        echo "</td>";
                        echo "<td>$passed_Event</td>";
                        echo "<td>$passed_Date</td>";
                        echo "<td>$passed_Start_time</td>";
                        echo "<td>$passed_End_time</td>";
                        echo "<td>$passed_Location</td>";
                        echo "</tr>";	
                }
                echo "</table>"; 

            }

            //Stänger ner koppling till databasen
            mysqli_close($dbconnect);  

    }

    // Man kan komma till filen på två sätt
    // 1. Har valt event att ta bort, och tar bort eventet (från databasen)
    // 2. Vill ta bort event, och väljer från en lista

        // 1. Har valt event att ta bort, och tar bort eventet (från databasen)
        // Om variablen "want_to_delete_event" är satt, och är lika med 1 betyder det att användaren har valt event att ta bort.
        if(isset($_POST['want_to_delete_event'])){
            if($_POST['want_to_delete_event'] == "1"){
                
                // Nollställer och tar bort variablen
                $_POST['want_to_delete_event'] = "0"; 
                unset ($_POST['want_to_delete_event']);

                //Kopplar upp mot databasen
                include "dbconnect.php"; 
                
                // Sparar eventets id i en variabel (kan endast bli ett heltal)
                // Skyddas även mot SQL-injektioner med mysqli_real_escape_string.
                $deleteThis = mysqli_real_escape_string($dbconnect,$_POST['delete_this_event']);
                $deleteThis = intval ($deleteThis);

                // Sparar ner namnet på eventet
                $nameEvent = htmlentities($_POST['name_deleted_event']);
                $nameEvent = mysqli_real_escape_string($dbconnect,$nameEvent);

                // Förbereder frågan till databasen
                $sql = "DELETE FROM `Events` WHERE `Event_id` =  $deleteThis";   
                
                //Ställer frågan till databasen.
                $result = mysqli_query($dbconnect, $sql);

                // Om något går fel vid frågeställningen 
                if(!$result) {
                    die("Kunde inte utföra frågan, avslutar...");
                } 

                // Man meddelar användaren att eventet är borttaget, och länkar vidare till admin-sidan. 
                echo "<h4 class='m-3'>You have deleted this event from the calender: $nameEvent. </h4>";
                echo "<form action='admin.php'> <input class='btn-secondary btn m-5' type='submit' Value='Continue' /> </form>";

                //Stänger ner koppling till databasen
                mysqli_close($dbconnect);  
            }

        }

        // 2. Vill ta bort event, och väljer från en lista
        else{
            //Anropar funktion som skriver ut alla event.
            showEvents();
        }


    }

    // Om man inte har behörighet till att ta bort event, men är inloggad visas denna layout. (Visar då enbart eventen)
    else if((isset($_SESSION['loggedin'])) && $_SESSION['admin_level'] == 1){
        
        // Inkluderar en administrationsmeny för lättare administration
        include "adminnav.php" ;
    
        //Visar funktionen av sidan, samt meddelar att man inte får ta bort event
        echo "<h2>Delete Event</h2>";
        echo "<h3 class='ms-5  text-danger'> You do not have permission to delete events.  </h3>";
        
        // Kopplar upp mot databasen
        include "dbconnect.php";    

        // Hämtar in dagens datum
        $todayDate = date("Y-m-d");

        // FÖRSTA TABELLEN - Event som inte varit

        // Förbereder frågan till databasen, hämtar endast event som ännu inte varit och listar dem i ordning efter datum.
        $select = "SELECT * FROM `Events` WHERE `Date` >= '$todayDate'
        ORDER BY `Date` ASC";

        // Ställer frågan till databasen
        $result = mysqli_query($dbconnect, $select);

        // Om något går fel vid frågeställningen
        if(!$result) {
            die("Kunde inte utföra frågan, avslutar...");
        } 

        // Förbereder tabellen
        echo "<h4 class='ms-5 mt-4'>Upcoming Events</h4>";
        echo "<table class='table ms-5 mb-5 table-hover my-table'> <tr>"; 
        echo "<th>Event</th>"; 
        echo "<th>Date</th>"; 
        echo "<th>Start time</th>"; 
        echo "<th>End time</th>"; 
        echo "<th>Location</th> </tr>"; 

            // Loop som hämtar och skriver ut alla event som ännu inte varit.
        while( $row = mysqli_fetch_array($result) )
        {
                $Event_id = $row['Event_id'];
                $Event = $row['Event'];
                $Date = $row['Date'];
                $Start_time = $row['Start_time']; 
                $End_time = $row['End_time'];
                $Location = $row['Location'];

                // Skriver ut informationen i tabellen
                echo "<tr>";
                echo "<td>$Event</td>";
                echo "<td>$Date</td>";
                echo "<td>$Start_time</td>";
                echo "<td>$End_time</td>";
                echo "<td>$Location</td>";
                echo "</tr>";	
        }
        echo "</table>"; 


        // ANDRA TABELLEN - Event som varit

        // Förbereder frågan till databasen, hämtar endast event som har varit.
        $selectpassed = "SELECT * FROM `Events` WHERE `Date` < '$todayDate'
        ORDER BY `Date` ASC";

        // Ställer frågan till databasen
        $resultpassed = mysqli_query($dbconnect, $selectpassed);

        // Om något går fel vid frågeställningen
        if(!$resultpassed) {
            die("Kunde inte utföra frågan, avslutar...");
        } 

        // Om det finns event som varit, så visas de här. Annars visas ingen tabell
        if(mysqli_num_rows($resultpassed) != 0){

            // Skriver ut funktionen av delen av sidan
            echo "<h4 class='ms-5 mt-4'>Former Events</h4>";

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

                    //skriver ut informationen i tabellen.
                    echo "<tr>";
                    echo "<td>$passed_Event</td>";
                    echo "<td>$passed_Date</td>";
                    echo "<td>$passed_Start_time</td>";
                    echo "<td>$passed_End_time</td>";
                    echo "<td>$passed_Location</td>";
                    echo "</tr>";	
            }
            echo "</table>"; 
        }

        //Stänger ner koppling till databasen
        mysqli_close($dbconnect);  
    }

    // Om man inte har en giltig inloggning får man ett felmeddelande, och en länk till inloggningssidan.
    else{
        echo "<h2> You are not logged in. </h2>
        <a href='login.php' class='m-5 btn btn-secondary'>Login here </a>"; 
    }
?>
<?php include "footer.php" ?>
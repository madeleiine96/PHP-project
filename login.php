<?php session_start(); // Startar en session, för att kunna använda sessioner på denna sidan ?>
<?php include "header.php" ?>

<!--  Man kan komma till sidan på tre olika sätt: 
1. Man är redan inloggad. 
2. man har fyllt i formuläret och försöker logga in. 
3. man har inte besökt sidan förut(vill logga in).  -->
<?php

        // 1. Man är redan inloggad, och skickas därför vidare till admin-sidan
        if(isset($_SESSION['loggedin'])){
            header("Location: admin.php");
        }

        // 2. Man försöker logga in, och kollar med databasen om användaren har skrivit in korrekta inloggningsuppgifter.
        // Om variablen "want_to_login" är satt, och är lika med 1 betyder det att användaren har fyllt i formuläret.
        else if(isset($_POST['want_to_login']) && $_POST['want_to_login'] == "1"){

                // Nollställer variablen
                $_POST['want_to_login'] = 0; 

                // Sparar inloggningen som variabler, och krypterar lösenordet med hög säkerhetsnivå. Htmlentities motverkar dåliga tecken och "städar" strängen.
                $username = htmlentities ($_POST['txt_username']);
                $password = htmlentities ($_POST['txt_password']);
                $enc_pass = hash("sha256", $password); 

                // Kopplar upp mot databasen
                include "dbconnect.php";

                // Använder "prepared statements" för att jämföra inloggningsuppgifterna mot databasen för att motverka SQL-injections
                $stmt = $dbconnect->prepare("SELECT * FROM `Users` WHERE `db_username` = ? AND `db_password` = ? LIMIT 1") or die("Can not execute");
                $stmt ->bind_param('ss', $username, $enc_pass);
                $stmt->execute();
                $stmt->bind_result($id, $username, $password, $adminlevel);
                $stmt->store_result();

                
                // Loopar för att hämta behörigheten
                while( $stmt->fetch() )
                {
                        //Binder svaret till en variabel
                        $admin_level = $adminlevel; 
                }   

                // Anger behörigheten till en session
                if(isset($admin_level)){

                        $_SESSION['admin_level'] = $admin_level;  

                } 
        
                
                // Om det finns en rad i $stmt har inloggningen matchats, och man blir inloggad.
                if($stmt->num_rows == 1){

                        // Man sätter en sessionsvariabel som blir användarens användarnamn
                        $_SESSION['loggedin'] = $username; 

                        // Skickar vidare till admin-sidan
                        header("Location: admin.php"); 
                }

                //Om man inte får några rader så har man inte matchat till databasens inloggningar, man blir därför nekad att logga in. 
                else{
                    echo "<h2> You entered wrong details. </br> </br> <a class='btn btn-secondary' href='login.php'> Try again </a>  </h2>";
                }
                
                //Stänger ner koppling till databasen  
                mysqli_close($dbconnect);

        }


        // 3. Man har inte besökt sidan förut(vill logga in), och sidan skriver ut inloggningsformuläret
        else{
            
                echo "<h2> Please log in to continue </h2>";
                echo "<form action='login.php' method='post' class='login-form'>"; 
                echo "<input type='text' name='txt_username' placeholder='Enter your username'> <br/>";
                echo "<input type='password' name='txt_password' placeholder='Enter your password'><br/>";
                echo "<input type='hidden' name='want_to_login' value='1'> "; // Skickar med en parameter som håller koll på om man försökt logga in, eller om man är på sidan för första gången
                echo "<input type='submit' class='btn btn-secondary' value='Log in'>";
                echo "</form>"; 
        }
?>




<?php include "footer.php" ?>
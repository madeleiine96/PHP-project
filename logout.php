<?php session_start(); // Startar en session, för att kunna använda sessioner på denna sidan ?> 
<?php include "header.php" ?>

<!--  Man kan komma till sidan på tre olika sätt:
1. Man är inloggad, och vill logga ut
2. Man är inte inloggad, kan inte loggas ut. Ett felmeddelande skrivs då ut.   -->
<?php

// 1. Man är inloggad, och vill logga ut (man har sin sessionsvariabel)
if(isset($_SESSION['loggedin'])) {
    
        // Sparar ner användarnamnet lokalt
        $name = $_SESSION['loggedin'];

        // Tar bort sessionsvariablerna (utloggningen)
        unset($_SESSION['loggedin']);
        unset($_SESSION['admin_level']);

        // Meddelar användaren att man blivit utloggad.
        echo "<h2> You have now logged out $name. See you soon!  </h2> ";
        
        // Tar bort användarens namn.
        $name = null; 
}

// 2. Man är inte inloggad, kan inte loggas ut.
else{
        // Felmeddelande skrivs ut, med länk till inloggningssidan.
        echo "<h2> You are not logged in, and can therefore not log out. 
        <a class='btn btn-secondary' href='login.php'>Login here</a> </h2>";
}
?>
<?php include "footer.php" ?>
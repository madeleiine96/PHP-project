<?php include "header.php" ?>


<div class="container mt-5">
  <div class="row">

  <!--  Visar de kommande spelningarna  -->
    <div class="col-sm-4">
      <h3>Concerts</h3>
      <h3 class="mt-4">Upcoming activities</h3>
      <p>Please buy tickets in advance</p>


      <?php

            // Hämtar in dagens datum
            $todayDate = date("Y-m-d");

            // Förbereder frågan till databasen, hämtar endast 5 stycken Event vars datum ännu inte varit.
            $sql = "SELECT * FROM `Events` WHERE `Date` >= '$todayDate'
            ORDER BY `Date` ASC LIMIT 5";
          
            // Kopplar upp mot databasen
            include "dbconnect.php";      

            // Ställer frågan till databasen
            $result = mysqli_query($dbconnect, $sql);

            // Om något går fel vid frågeställningen
            if(!$result) {
                  die("Could not execute");
            } 
                
            // Loop som hämtar och skriver ut listor med de fem nästa eventen
            while($row = mysqli_fetch_array($result)){
                  $Event = $row['Event'];
                  $Date = $row['Date'];
                  echo "<ul class='list-group list-group-horizontal'>";
                  echo "<li class='list-group-item list-group-item-secondary flex-fill noborder'>$Event</li>";
                  echo "<li class='list-group-item list-group-item-secondary flex-fill noborder'>$Date</li>"; 
                  echo "</ul>"; 
            } 

            // Stänger ner koppling till databasen  
            mysqli_close($dbconnect); 
      ?>
    </div>
     


    <!--  Artikel om spelning  -->
    <div class="col-sm-8">
          <h3>SCHOOL CONCERT</h3>
          <h5>Jude's School, 7 Oct 2021</h5>
          <img class="img-music" src="woodwind.jpg" alt="Picture of concert band" title="Picture of concert band" />
          <p>
            Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.
            Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.
            Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.
          </p>
    </div>
  </div>
</div>

<?php include "footer.php" ?>
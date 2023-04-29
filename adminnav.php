<!--  Exempel-nav från Bootstrap  -->

<nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample10" aria-controls="navbarsExample10" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample10">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="admin.php">See all events</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="add.php">Add event</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="delete.php">Delete event</a>
            </li>
            <li class="nav-item border-end">
              <a class="nav-link" href="update.php">Update event</a>
            </li>
            <li class="nav-item  ms-5" >
             <a class="nav-link disabled"> You are logged in as <?php echo $_SESSION['loggedin']; //Visar vem man är inloggad som ?> </a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-secondary" href="logout.php">Log out</a>
            </li>
          </ul>
        </div>
      </div>
</nav>
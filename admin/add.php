<?php
    require_once(__DIR__ . '/../config.php');
    require_once(__DIR__ . '/../db.php');

    if(!isset($_SESSION['oauth2_email'])) {
        header('Location: ' . $CFG->wwwroot . '/auth/oauth2callback.php');
    }
    elseif (!in_array($_SESSION['oauth2_email'], $CFG->admins)) {
        header('Location: ' . $CFG->wwwroot . '/error.html');
    }

    if (isset($_POST['sessions'])) {
        foreach ($_POST['sessions'] as $session) {
            $stmt = $db->prepare("INSERT INTO sessions_available (`datetime`) VALUES (?)");
            $stmt->bind_param("s", $session);
            $stmt->execute();
            $stmt->close();
        }

        header('Location: ' . $CFG->wwwroot . '/admin/add.php?success=1');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	
	<!-- Font Awesome -->
	<script src="https://kit.fontawesome.com/f1d81b1e61.js" crossorigin="anonymous"></script>

	<!-- JK CSS -->
	<link rel="stylesheet" href="../styles.css">
    <title>Judah Keys | PT</title>
</head>
<body style="padding-top: 70px; color: var(--brand-secondary);">
    <nav class="navbar bg-body-tertiary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">jdkeys<span style="color: #fff;">fitness</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">jdkeys<span style="color: #fff;">fitness</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/">Available sessions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Add sessions</a>
                        </li>
                    </ul>
                </div>       
            </div>
        </div>
    </nav>

    <div class="container">
        <?php
		if(isset($_GET['success'])){
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  					<strong>Success!</strong> New sessions added and available for booking.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			    </div>';
		}
		?>

        <h3>Add sessions</h3>
        <form method="POST">
            <div id="session-inputs">
                <div id="dynamicInput[0]">
                    <div class="row">
                        <div class="col">
                            <input class="form-control" type="datetime-local" name="sessions[]">
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary" onClick="addInput();">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Add sessions</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="../custom.js"></script>
</body>
</html>
<?php
    require_once(__DIR__ . '/../config.php');
    require_once(__DIR__ . '/../db.php');

    session_start();

    if(!isset($_SESSION['oauth2_email'])) {
        header('Location: ' . $CFG->wwwroot . '/auth/oauth2callback.php');
    }
    elseif (!in_array($_SESSION['oauth2_email'], $CFG->admins)) {
        header('Location: ' . $CFG->wwwroot . '/error.html');
    }

    if (isset($_GET['delete'])) {
        $stmt = $db->prepare("DELETE FROM sessions_available WHERE id = ?");
        $stmt->bind_param("s", $_GET['delete']);
        $stmt->execute();
        $stmt->close();

        header('Location: ' . $CFG->wwwroot . '/admin/index.php?success=1');
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
                            <a class="nav-link active" aria-current="page" href="#">Available sessions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add.php">Add sessions</a>
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
  					<strong>Success!</strong> Session deleted.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			    </div>';
		}
		?>

        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th scope="col">Session date</th>
                    <th scope="col">Session time</th>
                    <th scope="col">Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $db->prepare("SELECT * FROM sessions_available WHERE `datetime` > ?");
                $stmt->bind_param("s", date('Y-m-d H:i:s', strtotime('midnight')));
                $stmt->execute();
            
                $result = $stmt->get_result();
                if($result->num_rows === 0) {
                    echo 'No upcoming sessions available';
                }
                else
            
                while($row = $result->fetch_assoc()) {
                    echo '<tr><td>'.date('l jS F Y', strtotime($row['datetime'])).'</td><td>'.date('H:i', strtotime($row['datetime'])).' - '.date('H:i', strtotime($row['datetime'] . '+ 1 hour')).'</td><td><a href="index.php?delete='.$row['id'].'"><i class="fa-solid fa-xmark"></i></a></td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
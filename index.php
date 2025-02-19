<?php
    require_once(__DIR__ . '/config.php');
    require_once(__DIR__ . '/db.php');
    require_once(__DIR__ . '/email.php');

    if (isset($_POST['booking-date']) && isset($_POST['booking-name']) && isset($_POST['booking-email'])) {
        $stmt = $db->prepare("INSERT INTO sessions_booked (`session`, fullname, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", date('Y-m-d H:i:s', strtotime($_POST['booking-date'])), $_POST['booking-name'], $_POST['booking-email']);
        $stmt->execute();
        $stmt->close();

        $stmt = $db->prepare("DELETE FROM sessions_available WHERE `datetime` = ?");
        $stmt->bind_param("s", date('Y-m-d H:i:s', strtotime($_POST['booking-date'])));
        $stmt->execute();
        $stmt->close();

        send_booking_confirmation(date('l jS F H:i', strtotime($_POST['booking-date'])));

        header('Location: ' . $CFG->wwwroot . '/?success=1');
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
	<link rel="stylesheet" href="styles.css">
    <title>Judah Keys | PT</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <img class="rounded-circle mx-auto d-block" alt="judah keys" src="pix/judahkeys.jpg" style="width:12.5em; padding-bottom: 10px;">
            <h2>Judah Keys</h2>
        </div>

        <?php
		if(isset($_GET['success'])){
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  					<strong>Success!</strong> Your session has now been booked. I look forward to seeing you soon! 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			    </div>';
		}
        elseif(isset($_GET['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  					<strong>Oops, something went wrong!</strong> We encountered an error when attempting to book your session. If this persists, please contact me directly. Thank you!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			    </div>';
        }
		?>
        
        <?php
        $stmt = $db->prepare("SELECT * FROM sessions_available WHERE `datetime` > ? ORDER BY `datetime` ASC");
        $stmt->bind_param("s", date('Y-m-d H:i:s', strtotime('midnight')));
        $stmt->execute();
    
        $result = $stmt->get_result();
        if($result->num_rows === 0) {
            echo 'No upcoming sessions available';
        }
        else {
            while($row = $result->fetch_assoc()) {
                echo '<div class="card align-middle">
                        <div class="card-body">
                            <p class="booking-info"><i class="fa-regular fa-calendar-days"></i> '.date('l jS F', strtotime($row['datetime'])).'&emsp;<i class="fa-solid fa-clock"></i> '.date('H:i', strtotime($row['datetime'])).' - '.date('H:i', strtotime($row['datetime'] . '+ 1 hour')).'&emsp;<i class="fa-solid fa-location-dot"></i> PureGym Coleraine</p><button type="button" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#bookingModal" data-booking="'.date('l jS F Y H:i', strtotime($row['datetime'])).'">Book</button> 
                        </div>
                    </div>
                    &nbsp;';
            }
        }
        ?>
    
        <!-- Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel">Session booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="index.php">
                            <div class="form-group">
                                <label for="booking-date" class="col-form-label">Session date/time</label>
                                <input type="text" class="booking form-control" id="booking-date" name="booking-date" readonly>
                            </div>
                            <div class="form-group">
                                <label for="booking-name" class="col-form-label">Full name*</label>
                                <input type="text" class="form-control" id="booking-name" name="booking-name" required>
                            </div>
                            <div class="form-group">
                                <label for="booking-email" class="col-form-label">Email address*</label>
                                <input type="email" class="form-control" id="booking-email" name="booking-email" required>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Confirm booking</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="custom.js"></script>
</body>
</html>
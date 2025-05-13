<?php

// Database connection details
...

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch card data from the database
$cards = [];
$check_query = "SELECT * FROM kart_banen WHERE active = 1 ORDER BY date ASC";
$result = $conn->query($check_query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$maanden = array(
    "01" => "Januari",
    "02" => "Februari",
    "03" => "Maart",
    "04" => "April",
    "05" => "Mei",
    "06" => "Juni",
    "07" => "Juli",
    "08" => "Augustus",
    "09" => "September",
    "10" => "Oktober",
    "11" => "November",
    "12" => "December"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="../img/RLogo.png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/3a3e211029.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
    crossorigin="anonymous">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <title>Karten</title>

<style>
.imgBg{
    background-image: url('https://media.lastnightoffreedom.co.uk/i/Articles/id_571/571-karting-5.gif');
    background-size: cover;
    height: 150px;
}

.old-img{
    -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
    filter: grayscale(100%);
}
</style>                                                       -->
</head>
<body class="bg-light vh-100 mt-5">
    <div class="container">
  
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <header class="text-center mb-3 imgBg rounded d-flex align-items-center justify-content-center">
                    <h1 class="display-3 text-bolder text-white">Welkom bij de kartplanning</h1>
                </header>

                <div class="card rounded border-0 shadow-sm position-relative">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center mb-4 pb-4 border-bottom"><i class="far fa-calendar-alt fa-3x"></i>
                            <div class="ms-3">
                                <h4 class="text-uppercase fw-weight-bold mb-0">Race calendar 2k24</h4>
                                <p class="text-gray fst-italic mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <?php
                    // Loop through the card data and generate
                    foreach ($cards as $card) {
                        if ((strtotime($card['date']) > time() && $card['active'] == 1)) {
                            $check_query = "SELECT count(*) AS count FROM kart_gegevens WHERE kartbaan =" . $card['id'];
                            $result = $conn->query($check_query);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $count = $row['count'];

                                $month = date('m', strtotime($card['date']));
                                $datumnummer = date('d', strtotime($card['date']));

                                echo '
                                <!-- Kartbaan ' . $card['id'] . ' -->
                                <div class="card mb-3 shadow p-2">
                                    <div class="row g-0">
                                        <div class="col-md-3 d-flex justify-content-center rounded" style="background-color:#AC9381;">
                                            <img src="' . $card['image'] . '" class="img-fluid rounded" alt="...">
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-body d-flex flex-row justify-content-center">
                                                <div class="row mt-4">
                                                    <div class="col-md-8 col-12">
                                                        <h1 class="card-title text-Chestnut">' . $card['name'] . ' | <span class="text-primary">€' . $card['price'] . '</span></h1>
                                                        <p class="card-text text-bold fs-5 mt-4">Adres: ' . $card['address'] . '</p>
                                                        <p class="card-text fs-5 mt-4 text-muted">' . $card['description'] . '</p>
                                                        <a class="text-Chestnut fw-light" href="' . $card['website'] . '" target="_blank">Website kartbaan bekijken</a>
                                                    </div>
                                                    <hr class="d-block d-md-none my-2">
                                                    <div class="col-md-4 col-12">
                                                        <div class="row">
                                                            <div class="col-md-12 col-12 d-flex justify-content-center flex-column text-center">
                                                                <h3 class="card-text fw-bold text-Chestnut">' .  $datumnummer . ' ' . $maanden[$month];
                                                                if ($count < $card['person_limit']) {
                                                                    echo '<h5 class="card-text fw-bold text-muted">Aanmeldingen: <span class="text-primary">'.$count.'/' . $card['person_limit'] . '</span></h5>
                                                                    <div class="col-md-12 col-12 mt-3 d-flex justify-content-center">
                                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal' . $card['id'] . '">Ik ben er bij!</button>
                                                                    </div>';
                                                                } else {
                                                                    echo '<h5 class="card-text fw-bold text-muted">Aanmeldingen: <span class="text-danger">'.$count.'/' . $card['person_limit'] . '</span></h5>
                                                                    <div class="col-md-12 col-12 mt-3 d-flex justify-content-center">
                                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" disabled data-bs-target="#exampleModal0"><span class="text-decoration-line-through">Ik ben er bij!</span></button>
                                                                    </div>';
                                                                }
                                                            echo '
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            }
                        }else{
                            $check_query = "SELECT count(*) AS count FROM kart_gegevens WHERE kartbaan =" . $card['id'];
                            $result = $conn->query($check_query);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $count = $row['count'];

                                $month = date('m', strtotime($card['date']));
                                $datumnummer = date('d', strtotime($card['date']));

                                echo '
                                <!-- Kartbaan ' . $card['id'] . ' -->
                                <div class="card mb-3 shadow p-2">
                                    <div class="row g-0">
                                        <div class="col-md-3 d-flex justify-content-center rounded" style="background-color:#AC9381;">
                                            <img src="' . $card['image'] . '" class="img-fluid rounded old-img" alt="...">
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-body d-flex flex-row justify-content-center">
                                                <div class="row mt-4">
                                                    <div class="col-md-8 col-12">
                                                        <h1 class="card-title text-Chestnut">' . $card['name'] . ' | <span class="text-muted">€' . $card['price'] . '</span></h1>
                                                        <p class="card-text text-bold fs-5 mt-4">Adres: ' . $card['address'] . '</p>
                                                        <p class="card-text fs-5 mt-4 text-muted">' . $card['description'] . '</p>
                                                        <a class="text-muted fw-light" href="' . $card['website'] . '" target="_blank">Website kartbaan bekijken</a>
                                                    </div>
                                                    <hr class="d-block d-md-none my-2">
                                                    <div class="col-md-4 col-12">
                                                        <div class="row">
                                                            <div class="col-md-12 col-12 d-flex justify-content-center flex-column text-center">
                                                                <h3 class="card-text fw-bold text-danger">' .  $datumnummer . ' ' . $maanden[$month] . '</h3>
                                                                
                                                                <h5 class="card-text fw-bold text-muted">Aanmeldingen: <span class="text-muted">'.$count.'/' . $card['person_limit'] . '</span></h5>
                                                                <div class="col-md-12 col-12 mt-3 d-flex justify-content-center">
                                                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" disabled data-bs-target="#exampleModal0"><span class="text-decoration-line-through">Ik ben er bij!</span></button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            }
                        }
                    }
                    

                    // Loop through the card data again and generate modal
                    foreach ($cards as $card) {
                        echo '
                            <div class="modal fade" id="exampleModal' . $card['id'] . '" tabindex="-1" aria-labelledby="exampleModalLabel' . $card['id'] . '" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">' . $card['name'] . '</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                                <div class="mb-3">
                                                    <label for="fullName" class="form-label">Volledige naam</label>
                                                    <input type="fullName" class="form-control" id="fullName' . $card['id'] . '" >
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phoneNumber" class="form-label">Telefoon nummer</label>
                                                    <input type="phoneNumber" class="form-control"  placeholder="0612345678" id="phoneNumber' . $card['id'] . '">
                                                    <small id="phoneNumber" class="form-text text-muted">Naar dit nummer word een betaalverzoek gestuurd</small>                       
                                                </div>
                                                <div class="mb-3 form-check">
                                                    <input type="checkbox" class="form-check-input" id="imThere' . $card['id'] . '">
                                                    <label class="form-check-label" for="imThere">Ik ga akkoord dat de inschrijven pas compleet is na betaling.</label>
                                                    <input type="number" class="form-check-input" id="Kartbaan' . $card['id'] . '" value="' . $card['id'] . '" hidden>
                                                </div>
                                                <button type="button" class="btn btn-primary" id="submitForm' . $card['id'] . '">Versturen</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    
                        // Add JavaScript code for form submission inside the loop
                        echo '
                            <script>
                                $(document).ready(function() {
                                    $(document).on("click", "#submitForm' . $card['id'] . '", function(event) {
                                        event.preventDefault(); // Prevent default form submission
                                        
                                        if ($("#imThere' . $card['id'] . '").prop("checked")) {
                                            let fullName = $("#fullName' . $card['id'] . '").val();
                                            let phoneNumber = $("#phoneNumber' . $card['id'] . '").val();
                                            let Kartbaan = $("#Kartbaan' . $card['id'] . '").val();
                                            let imThere = true;
                                            
                                            $.ajax({
                                                type: "POST",
                                                url: "kartVerwerk.php",
                                                data: {
                                                    fullName: fullName,
                                                    phoneNumber: phoneNumber,
                                                    imThere: imThere,
                                                    Kartbaan: Kartbaan
                                                },
                                                success: function(response) {
                                                    console.log(response);
                                                    $("#exampleModal' . $card['id'] . '").modal("hide");
                                                    alert("Form submitted successfully!");
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error(xhr.responseText);
                                                    alert("Je hebt jezelf al opgegeven.");
                                                }
                                            });
                                        } else {
                                            alert("Please check the checkbox to submit the form.");
                                        }
                                    });
                                });
                            </script>';
                    }                                      
                    
                    ?>
                </div>
            </div>
        </div>

    </div>
</body>

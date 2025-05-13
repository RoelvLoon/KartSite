<?php

// Database connection details
...

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch card data from the database
$cards = [];
$check_query = "SELECT * FROM kart_banen ORDER BY date ASC";
$result = $conn->query($check_query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
</style>
</head>
<body class="bg-light vh-100">
    <div class="container d-flex flex-row align-items-center justify-content-center mt-5">
        <div class="row d-flex flex-row align-items-center justify-content-center">
        <?php
            foreach ($cards as $card) {
                $kart_data = "SELECT * FROM kart_gegevens WHERE kartbaan =" . $card['id']; // Fetch all data for kartbaan 1
                $kart_result = $conn->query($kart_data);
                $row_count = $kart_result->num_rows; // Get the count of rows
                if ($kart_result->num_rows > 0) {
                    echo'
                <div class="col-md-4 col-xs-3 d-flex flex-row align-items-center justify-content-center mb-3">
                    <div class="card shadow-sm">
                        <div class="image-container" style="max-height: 200px; overflow: hidden;">
                            <img src="' . $card['image'] . '" class="card-img-top" alt="' . $card['name'] . '" style="width: 100%; height: auto;">
                        </div>
                        <div class="card-body">
                            <div style="overflow-y: auto; max-height: 250px;"> <!-- Apply overflow-y and max-height to control scrolling -->
                                <div class="row">
                                    <div class="col-7">
                                        <h4 class="fw-bold text-primary">' . $card['name'] . '</h4>
                                    </div>
                                    <div class="col-5 d-flex justify-content-center">
                                        <!-- Form for toggling active state -->
                                        <form id="kartbaan_form_' . $card['id'] . '" action="kartVerwerk.php" method="POST">
                                            <input type="hidden" name="kartbaan_id" value="' . $card['id'] . '">
                                            <input type="hidden" name="isUpdate" value="1">
                                            <input type="hidden" name="active_switch" value="' . ($card['active'] == 1 ? '1' : '0') . '"> <!-- Hidden input for active_switch -->
                                            <div class="form-check form-switch checkbox-xl d-flex align-items-center">
                                                <p class="fw-bold">Actief:</p>
                                                <input class="form-check-input mt-4" type="checkbox" id="active_switch_' . $card['id'] . '" name="active_switch" ' . ($card['active'] == 1 ? 'checked' : '') . ' onchange="document.getElementById(\'kartbaan_form_' . $card['id'] . '\').submit();">
                                            </div>
                                        </form>
                                    </div>
                                </div>';
                                if ($row_count < $card['person_limit']) {
                                    echo '<h5 class="card-text fw-bold text-muted">Aanmeldingen: <span class="text-dark">'.$row_count.'/' . $card['person_limit'] . '</span></h5>';
                                } else {
                                    echo '<h5 class="card-text fw-bold text-muted">Aanmeldingen: <span class="text-danger">'.$row_count.'/' . $card['person_limit'] . '</span></h5>';
                                }
                                echo '
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Naam</th>
                                            <th scope="col">Nummer</th>
                                        </tr>
                                    </thead>
                                    test:'.$card['id'].'
                                    <tbody>';
                                    while ($row = $kart_result->fetch_assoc()) {
                                        echo '
                                        <tr>
                                            <td>' . $row['name'] . '</td>
                                            <td>0' . $row['phoneNumber'] . '</td>
                                        </tr>';
                                    }
                                    echo '
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>';
                
                }
            }
            ?>
            <div class="col-md-4 col-xs-3 d-flex flex-row align-items-center justify-content-center mb-3 h-100">
                <div class="card h-100 shadow-sm bg-light" style="width: 200px!important;">
                    <div class="card-body h-100">
                        <h5 class="card-title text-center">Toevoegen</h5>
                        <div class="d-flex align-items-center justify-content-center" style="height: 100px!important;">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="exampleModalLabel">Kartbaan toevoegen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="kartbaanForm">
                                <div class="form-floating my-3">
                                    <input type="input" class="form-control" id="naam" placeholder="Kartfabrique">
                                    <label for="naam">Naam</label>
                                </div>
                                <div class="form-floating my-3">
                                    <textarea class="form-control" id="floatingBeschrijving" rows="5" placeholder="Beschrijving" style="height: 100px!important;"></textarea>
                                    <label for="floatingBeschrijving">Beschrijving</label>
                                </div>
                                <div class="form-floating my-3">
                                    <input type="input" class="form-control" id="floatingLocatie" placeholder="Locatie">
                                    <label for="floatingLocatie">Locatie</label>
                                </div>
                                <div class="d-flex flex-row align-items-center justify-content-center">
                                    <div class="form-floating me-2 w-100">
                                        <input type="input" class="form-control" id="floatingWebsite" placeholder="Website">
                                        <label for="floatingWebsite">Website</label>
                                    </div>
                                    <div class="form-floating ms-2 w-100">
                                        <input type="input" class="form-control" id="floatingAfbeelding" placeholder="Afbeelding">
                                        <label for="floatingAfbeelding">Afbeelding</label>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center justify-content-center my-3">
                                    <div class="form-floating me-2 w-25">
                                        <input type="date" class="form-control" id="floatingDatum" placeholder="Datum">
                                        <label for="floatingDatum">Datum</label>
                                    </div>
                                    <div class="form-floating mx-2 w-25">
                                        <input type="number" class="form-control" id="floatingAantaldeelnemers" value="20" placeholder="Aantaldeelnemers">
                                        <label for="floatingAantaldeelnemers">Aantal deelnemers</label>
                                    </div>
                                    <div class="input-group ms-2 w-25">
                                        <span class="input-group-text" id="basic-addon1">€</span>
                                        <input type="number" class="form-control" placeholder="Kosten" aria-label="Kosten" aria-describedby="basic-addon1">
                                        <input type="number" class="form-control" hidden value="1" id="AddKartbaan" placeholder="Kosten" aria-label="Kosten" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <p class="fw-bold">Actief:</p>
                                        <input class="form-check-input mt-4" type="checkbox" id="flexSwitchCheckDefault">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitForm">Versturen</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
function submitForm(kartbaanId) {
    document.getElementById('kartbaan_form_' + kartbaanId).submit();
}

$(document).ready(function() {
    $(document).on("submit", "#kartbaanForm", function(event) {
        event.preventDefault(); // Prevent default form submission
        
        if ($("#flexSwitchCheckDefault").prop("checked")) {
            let naam = $("#naam").val();
            let beschrijving = $("#floatingBeschrijving").val();
            let locatie = $("#floatingLocatie").val();
            let website = $("#floatingWebsite").val();
            let afbeelding = $("#floatingAfbeelding").val();
            let datum = $("#floatingDatum").val();
            let aantaldeelnemers = $("#floatingAantaldeelnemers").val();
            let kosten = $("#basic-addon1 + input").val(); // Assuming this is the input for kosten
            let actief = $("#flexSwitchCheckDefault").prop("checked");

            $.ajax({
                type: "POST",
                url: "kartVerwerk.php",
                data: {
                    naam: naam,
                    beschrijving: beschrijving,
                    locatie: locatie,
                    website: website,
                    afbeelding: afbeelding,
                    datum: datum,
                    aantaldeelnemers: aantaldeelnemers,
                    kosten: kosten,
                    actief: actief,
                    AddKartbaan: 1
                },
                success: function(response) {
                    console.log(response);
                    $("#exampleModal").modal("hide");
                    displayAlert('success', 'Data saved successfully!');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    displayAlert('danger', 'Error saving data. Please try again later.');
                }
            });
        } else {
            alert("Please check the checkbox to submit the form.");
        }
    });
});

function displayAlert(type, message) {
    var alertMessage = document.getElementById('alertMessage');
    alertMessage.textContent = message;
    alertMessage.className = 'alert alert-' + type + ' role="alert"';
    alertMessage.style.display = 'block';

    setTimeout(function() {
        alertMessage.style.display = 'none';
    }, 5000);
}
</script>
<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Database connection details
    ...

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['isUpdate']) && $_POST['isUpdate'] == 1) {
        // Update kartbaan active state
        if (isset($_POST['kartbaan_id'], $_POST['active_switch'])) {
            $kartbaan_id = (int) $_POST['kartbaan_id'];
            $active = ($_POST['active_switch'] === 'on') ? 1 : 0;

            $update_query = "UPDATE kart_banen SET active = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ii", $active, $kartbaan_id);

            if ($stmt->execute()) {
                echo "Record updated successfully";
                header("Location: admin/");
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }

            $stmt->close();
        } else {
            echo "Error: Missing kartbaan ID or active state";
        }

    } if (isset($_POST['AddKartbaan'])) {
        // Get and check input
        $naam = trim($_POST["naam"] ?? '');
        $beschrijving = trim($_POST["beschrijving"] ?? '');
        $locatie = trim($_POST["locatie"] ?? '');
        $website = trim($_POST["website"] ?? '');
        $afbeelding = trim($_POST["afbeelding"] ?? '');
        $datum = $_POST["datum"] ?? '';
        $aantaldeelnemers = filter_var($_POST["aantaldeelnemers"] ?? 0, FILTER_VALIDATE_INT);
        $kosten = filter_var($_POST["kosten"] ?? 0.0, FILTER_VALIDATE_FLOAT);
        $actief = filter_var($_POST["actief"] ?? 0, FILTER_VALIDATE_INT);
    
        if (empty($naam) || empty($beschrijving) || empty($locatie) || empty($datum) || $aantaldeelnemers === false || $kosten === false) {
            die("Ongeldige invoer. Controleer alle velden.");
        }
    
        $sql = "INSERT INTO kart_banen (name, description, image, website, address, date, price, person_limit, active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssssidi", $naam, $beschrijving, $afbeelding, $website, $locatie, $datum, $kosten, $aantaldeelnemers, $actief);
    
            if ($stmt->execute()) {
                header("Location: admin/");
                exit();
            } else {
                error_log("Databasefout: " . $stmt->error);
                die("Er is iets misgegaan. Probeer het later opnieuw.");
            }
    
            $stmt->close();
        } else {
            die("Voorbereiden van de query is mislukt.");
        }
    }
    elseif ($_POST['AddKartbaan'] == 1) {
        $naam = $conn->real_escape_string($_POST['naam']);
        $beschrijving = $conn->real_escape_string($_POST['beschrijving']);
        $locatie = $conn->real_escape_string($_POST['locatie']);
        $website = $conn->real_escape_string($_POST['website']);
        $afbeelding = $conn->real_escape_string($_POST['afbeelding']);
        $datum = $conn->real_escape_string($_POST['datum']);
        $aantaldeelnemers = $conn->real_escape_string($_POST['aantaldeelnemers']);
        $kosten = $conn->real_escape_string($_POST['kosten']);
        $actief = $conn->real_escape_string($_POST['actief']);

        // Insert query to add the new kartbaan
        $sql = "INSERT INTO kart_banen (name, description, address, website, image, date, person_limit, price, active) 
                VALUES ('$naam', '$beschrijving', '$locatie', '$website', '$afbeelding', '$datum', '$aantaldeelnemers', '$kosten', '$actief')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
    // Handling the Edit operation
    } elseif ($_POST['AddKartbaanEdit'] == 2) {
        $id = $conn->real_escape_string($_POST['id']);
        $naam = $conn->real_escape_string($_POST['naam']);
        $beschrijving = $conn->real_escape_string($_POST['beschrijving']);
        $locatie = $conn->real_escape_string($_POST['locatie']);
        $website = $conn->real_escape_string($_POST['website']);
        $afbeelding = $conn->real_escape_string($_POST['afbeelding']);
        $datum = $conn->real_escape_string($_POST['datum']);
        $aantaldeelnemers = $conn->real_escape_string($_POST['aantaldeelnemers']);
        $kosten = $conn->real_escape_string($_POST['kosten']);
        $actief = $conn->real_escape_string($_POST['actief']);

        // Update query to edit the existing kartbaan
        $sql = "UPDATE kart_banen 
                SET name='$naam', description='$beschrijving', address='$locatie', website='$website', image='$afbeelding', date='$datum', person_limit='$aantaldeelnemers', price='$kosten', active='$actief' 
                WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    




    } else {
        // Add participant to a kartbaan
        $fullName = htmlspecialchars($_POST["fullName"]);
        $phoneNumber = htmlspecialchars($_POST["phoneNumber"]);
        $imThere = isset($_POST["imThere"]) ? 1 : 0;
        $Kartbaan = htmlspecialchars($_POST["Kartbaan"]);

        // Check if the name already exists in the database
        $check_query = "SELECT * FROM kart_gegevens WHERE name = ? AND kartbaan = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("ss", $fullName, $Kartbaan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Error: The name '$fullName' already exists in the database.";
            http_response_code(400);
        } else {
            $insert_query = "INSERT INTO kart_gegevens (name, phoneNumber, im_there, kartbaan)
                             VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssis", $fullName, $phoneNumber, $imThere, $Kartbaan);

            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }

    // Close the database connection
    $conn->close();
}
?>

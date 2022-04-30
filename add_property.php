<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property</title>
    <link href="styles/styles.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php
    include 'db/connect_db.php';

    $uid = $_SESSION["uid"];
    $isValid = true;
    $errors = [];

    if (isset($_POST['Submit'])) {
        $city_state = isset($_POST['city_state']) ? $_POST['city_state'] : '';
        $street = isset($_POST['street']) ? $_POST['street'] : '';
        $zip = isset($_POST['zip']) ? $_POST['zip'] : '';
        $price = isset($_POST['price']) ?  str_replace(',', '', $_POST['price']) : '';
        $type = isset($_POST['type']) ? $_POST['type'] : '';
        $squareFt = isset($_POST['squareFt']) ? str_replace(',', '', $_POST['squareFt']) : '';
        $bed = isset($_POST['bed']) ? $_POST['bed'] : '';
        $bath = isset($_POST['bath']) ? $_POST['bath'] : '';

        if (isset($_FILES['imgURL']) && $_FILES['imgURL']['error'] === UPLOAD_ERR_OK) {
            $imgName = $_FILES['imgURL']['name'];
            $imgTemp = $_FILES['imgURL']['tmp_name'];
            $imgNameSplit = explode(".", $imgName);
            $imgExtension = strtolower(end($imgNameSplit));
            $imgPath = "img/";
            $allowedfileExtensions = array('jpg', 'jpeg', 'gif', 'png', 'webp');

            if (!in_array($imgExtension, $allowedfileExtensions)) {
                $errors[] = "Selected image format is not allowed";
            }

            if (move_uploaded_file($imgTemp, $imgPath . $imgName)) {
                $imgURL = $imgName;
            } else {
                echo "<div class='error'><p>
                        <ul>
                            <li>Failed to upload your image</li>
                        </ul>
                        <input type='button' value='Go Back' onClick='history.back()'>
                        </p></div>";
                exit;
            }
        }
    }

    if ($city_state == "") {
        $isValid = false;
        $errors[] = "City and State field is empty.";
    }

    if ($street == "") {
        $isValid = false;
        $errors[] = "Street Address field is empty.";
    }

    if ($zip == "") {
        $isValid = false;
        $errors[] = "Zip Code field is empty.";
    }

    if ($price == "") {
        $isValid = false;
        $errors[] = "Listing Price field is empty.";
    }

    if ($squareFt == "") {
        $isValid = false;
        $errors[] = "Total squareFt field is empty.";
    }

    if ($bed == "") {
        $isValid = false;
        $errors[] = "Total bedrooms field is empty.";
    }

    if ($bath == "") {
        $isValid = false;
        $errors[] = "Total bathrooms field is empty.";
    }
    if ($imgURL == "") {
        $isValid = false;
        $errors[] = "No image uploaded.";
    }

    if (!$isValid) {
        echo "<div class='error'><p>Please fix the following errors:<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul><input type='button' value='Go Back' onClick='history.back()'></p></div>";
        exit;
    } else {
        $sql = "INSERT INTO Property(sellerId, city_state, street, zip, squareFt, type, bed, bath, price, imgURL)
        VALUES('$uid', '$city_state', '$street', '$zip', '$squareFt', '$type', '$bed', '$bath', '$price', '$imgURL')";
        if (mysqli_query($conn, $sql)) {
            header("location:seller.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" .  mysqli_error($conn);
        }
    }
    mysqli_close($conn);
    ?>
</body>

</html>
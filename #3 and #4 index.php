<?php

include "db.php";

$message = "";

/* INSERT RECORD */
if (isset($_POST["register"])) {

    $name = trim($_POST["name"]);
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);
    $contact = trim($_POST["contact"]);

    if (
        empty($name) ||
        empty($age) ||
        empty($gender) ||
        empty($email) ||
        empty($address) ||
        empty($contact)
    ) {

        $message = "Please fill in all fields.";

    } elseif ($age < 1 || $age > 100) {

        $message = "Please enter a valid age.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } elseif (!preg_match("/^[0-9]{11}$/", $contact)) {

        $message = "Contact number must contain exactly 11 digits.";

    } else {

        $sql = "INSERT INTO persons
                (name, age, gender, email, address, contact)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sissss",
            $name,
            $age,
            $gender,
            $email,
            $address,
            $contact
        );

        if ($stmt->execute()) {
            $message = "Registration successful!";
        } else {
            $message = "Error: " . $conn->error;
        }

        $stmt->close();
    }
}


/* DELETE RECORD */
if (isset($_GET["delete"])) {

    $id = $_GET["delete"];

    $stmt = $conn->prepare("DELETE FROM persons WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}


/* GET RECORD FOR EDITING */
$editData = null;

if (isset($_GET["edit"])) {

    $id = $_GET["edit"];

    $stmt = $conn->prepare("SELECT * FROM persons WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();

    $stmt->close();
}


/* UPDATE RECORD */
if (isset($_POST["update"])) {

    $id = $_POST["id"];
    $name = trim($_POST["name"]);
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);
    $contact = trim($_POST["contact"]);

    if (
        empty($name) ||
        empty($age) ||
        empty($gender) ||
        empty($email) ||
        empty($address) ||
        empty($contact)
    ) {

        $message = "Please fill in all fields.";

    } elseif ($age < 1 || $age > 100) {

        $message = "Please enter a valid age.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email.";

    } elseif (!preg_match("/^[0-9]{11}$/", $contact)) {

        $message = "Contact number must contain exactly 11 digits.";

    } else {

        $sql = "UPDATE persons
                SET name=?, age=?, gender=?, email=?, address=?, contact=?
                WHERE id=?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sissssi",
            $name,
            $age,
            $gender,
            $email,
            $address,
            $contact,
            $id
        );

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Registration System</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <h1>Registration System</h1>

    <?php if (!empty($message)): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>


    <!-- REGISTRATION FORM -->

    <div class="form-box">

        <h2>
            <?php
            echo $editData ? "Edit Registered Person" : "Register Person";
            ?>
        </h2>

        <form method="POST">

            <?php if ($editData): ?>

                <input type="hidden"
                       name="id"
                       value="<?php echo $editData["id"]; ?>">

            <?php endif; ?>


            <label>Name</label>

            <input type="text"
                   name="name"
                   value="<?php
                   echo $editData
                       ? htmlspecialchars($editData["name"])
                       : "";
                   ?>"
                   required>


            <label>Age</label>

            <input type="number"
                   name="age"
                   min="1"
                   max="100"
                   value="<?php
                   echo $editData
                       ? $editData["age"]
                       : "";
                   ?>"
                   required>


            <label>Gender</label>

            <select name=
<?php
$name = $age = $gender = $email = $address = $contact = "";
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Name
    if (empty($_POST["name"])) {
        $errors["name"] = "Name is required.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Age
    if (empty($_POST["age"])) {
        $errors["age"] = "Age is required.";
    } elseif ($_POST["age"] < 1 || $_POST["age"] > 100) {
        $errors["age"] = "Please enter a valid age.";
    } else {
        $age = $_POST["age"];
    }

    // Gender
    if (empty($_POST["gender"])) {
        $errors["gender"] = "Please select your gender.";
    } else {
        $gender = $_POST["gender"];
    }

    // Email
    if (empty($_POST["email"])) {
        $errors["email"] = "Email is required.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Address
    if (empty($_POST["address"])) {
        $errors["address"] = "Address is required.";
    } else {
        $address = trim($_POST["address"]);
    }

    // Contact Number
    if (empty($_POST["contact"])) {
        $errors["contact"] = "Contact number is required.";
    } elseif (!preg_match("/^[0-9]{11}$/", $_POST["contact"])) {
        $errors["contact"] = "Contact number must be 11 digits.";
    } else {
        $contact = trim($_POST["contact"]);
    }

    if (empty($errors)) {
        $success = "Registration successful!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 40px;
        }

        .container {
            width: 500px;
            max-width: 100%;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            background: #333;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #555;
        }

        .error {
            color: red;
            font-size: 13px;
        }

        .success {
            color: green;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Registration Form</h2>

    <?php if (!empty($success)): ?>
        <div class="success">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">

        <label>Name:</label>
        <input type="text" name="name"
               value="<?php echo htmlspecialchars($name); ?>"
               required>
        <?php if (isset($errors["name"])) echo "<div class='error'>{$errors["name"]}</div>"; ?>


        <label>Age:</label>
        <input type="number" name="age"
               min="1" max="100"
               value="<?php echo htmlspecialchars($age); ?>"
               required>
        <?php if (isset($errors["age"])) echo "<div class='error'>{$errors["age"]}</div>"; ?>


        <label>Gender:</label>
        <select name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male" <?php if ($gender == "Male") echo "selected"; ?>>Male</option>
            <option value="Female" <?php if ($gender == "Female") echo "selected"; ?>>Female</option>
        </select>
        <?php if (isset($errors["gender"])) echo "<div class='error'>{$errors["gender"]}</div>"; ?>


        <label>Email:</label>
        <input type="email" name="email"
               value="<?php echo htmlspecialchars($email); ?>"
               required>
        <?php if (isset($errors["email"])) echo "<div class='error'>{$errors["email"]}</div>"; ?>


        <label>Address:</label>
        <textarea name="address" rows="3" required><?php
            echo htmlspecialchars($address);
        ?></textarea>
        <?php if (isset($errors["address"])) echo "<div class='error'>{$errors["address"]}</div>"; ?>


        <label>Contact Number:</label>
        <input type="tel" name="contact"
               placeho
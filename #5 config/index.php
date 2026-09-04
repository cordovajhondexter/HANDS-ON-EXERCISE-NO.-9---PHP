<?php

require_once "config/database.php";
require_once "models/Faculty.php";
require_once "controllers/FacultyController.php";

$database = new Database();
$db = $database->connect();

$faculty = new Faculty($db);
$controller = new FacultyController($faculty);

$action = $_GET['action'] ?? 'list';

$errors = [];
$message = "";


/* =========================
   CREATE / UPDATE
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['faculty_id'] ?? "";

    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = trim($_POST['address']);
    $position = trim($_POST['position']);
    $salary = $_POST['salary'];


    /* VALIDATION */

    if ($first_name == "") {
        $errors[] = "First Name is required.";
    }

    if ($last_name == "") {
        $errors[] = "Last Name is required.";
    }

    if (!filter_var($age, FILTER_VALIDATE_INT) || $age < 18 || $age > 100) {
        $errors[] = "Age must be between 18 and 100.";
    }

    if (!in_array($gender, ["Male", "Female", "Other"])) {
        $errors[] = "Please select a valid gender.";
    }

    if ($address == "") {
        $errors[] = "Address is required.";
    }

    if ($position == "") {
        $errors[] = "Position is required.";
    }

    if (!is_numeric($salary) || $salary < 0) {
        $errors[] = "Salary must be a valid amount.";
    }


    /* SAVE */

    if (empty($errors)) {

        $data = [
            ':first_name' => $first_name,
            ':middle_name' => $middle_name,
            ':last_name' => $last_name,
            ':age' => $age,
            ':gender' => $gender,
            ':address' => $address,
            ':position' => $position,
            ':salary' => $salary
        ];


        // CREATE
        if ($id == "") {

            $controller->create($data);

            header("Location: index.php?message=added");
            exit;

        }

        // UPDATE
        else {

            $data[':faculty_id'] = $id;

            $controller->update($data);

            header("Location: index.php?message=updated");
            exit;
        }
    }
}


/* =========================
   DELETE
========================= */

if ($action == "delete" && isset($_GET['id'])) {

    $controller->delete($_GET['id']);

    header("Location: index.php?message=deleted");
    exit;
}


/* =========================
   EDIT
========================= */

$editFaculty = null;

if ($action == "edit" && isset($_GET['id'])) {

    $editFaculty = $controller->getById($_GET['id']);
}


/* =========================
   MESSAGE
========================= */

if (isset($_GET['message'])) {

    if ($_GET['message'] == "added") {
        $message = "Faculty successfully added!";
    }

    if ($_GET['message'] == "updated") {
        $message = "Faculty successfully updated!";
    }

    if ($_GET['message'] == "deleted") {
        $message = "Faculty successfully deleted!";
    }
}


/* =========================
   GET FACULTY
========================= */

$facultyList = $controller->index();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Faculty CRUD System</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f5f5f5;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        input, select {
            padding: 8px;
            margin: 5px 0 15px;
            width: 300px;
        }

        button {
            padding: 10px 20px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #ddd;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

    </style>

</head>


<body>

<div class="container">

<h1>Faculty Management System</h1>


<?php if (!empty($errors)): ?>

    <div class="error">

        <?php foreach ($errors as $error): ?>

            <p><?= htmlspecialchars($error) ?></p>

        <?php endforeach; ?>

    </div>

<?php endif; ?>


<?php if ($message): ?>

    <p class="success">
        <?= htmlspecialchars($message) ?>
    </p>

<?php endif; ?>


<h2>
    <?= $editFaculty ? "Edit Faculty" : "Add Faculty" ?>
</h2>


<form method="POST">


<?php if ($editFaculty): ?>

    <input
        type="hidden"
        name="faculty_id"
        value="<?= $editFaculty['faculty_id'] ?>"
    >

<?php endif; ?>


<label>First Name</label><br>

<input
    type="text"
    name="first_name"
    required
    value="<?= htmlspecialchars($editFaculty['first_name'] ?? '') ?>"
>

<br>


<label>Middle Name</label><br>

<input
    type="text"
    name="middle_name"
    value="<?= htmlspecialchars($editFaculty['middle_name'] ?? '') ?>"
>

<br>


<label>Last Name</label><br>

<input
    type="text"
    name="last_name"
    required
    value="<?= htmlspecialchars($editFaculty['last_name'] ?? '') ?>"
>

<br>


<label>Age</label><br>

<input
    type="number"
    name="age"
    min="18"
    max="100"
    required
    value="<?= htmlspecialchars($editFaculty['age'] ?? '') ?>"
>

<br>


<label>Gender</label><br>

<select name="gender" required>

    <option value="">Select Gender</option>

    <option value="Male"
        <?= (($editFaculty['gender'] ?? '') == "Male") ? "selected" : "" ?>>
        Male
    </option>

    <option value="Female"
        <?= (($editFaculty['gender'] ?? '') == "Female") ? "selected" : "" ?>>
        Female
    </option>

    <option value="Other"
        <?= (($editFaculty['gender'] ?? '') == "Other") ? "selected" : "" ?>>
        Other
    </option>

</select>

<br>


<label>Address</label><br>

<input
    type="text"
    name="address"
    required
    value="<?= htmlspecialchars($editFaculty['address'] ?? '') ?>"
>

<br>


<label>Position</label><br>

<input
    type="text"
    name="position"
    required
    value="<?= htmlspecialchars($editFaculty['position'] ?? '') ?>"
>

<br>


<label>Salary</label><br>

<input
    type="number"
    name="salary"
    min="0"
    step="0.01"
    required
    value="<?= htmlspecialchars($editFaculty['salary'] ?? '') ?>"
>

<br>


<button type="submit">

    <?= $editFaculty ? "Update Faculty" : "Add Faculty" ?>

</button>


</form>


<hr>


<h2>List of Faculty</h2>


<table>

<tr>

    <th>ID</th>
    <th>First Name</th>
    <th>Middle Name</th>
    <th>Last Name</th>
    <th>Age</th>
    <th>Gender</th>
    <th>Address</th>
    <th>Position</th>
    <th>Salary</th>
    <th>Action</th>

</tr>


<?php while ($row = $facultyList->fetch(PDO::FETCH_ASSOC)): ?>

<tr>

    <td><?= $row['faculty_id'] ?></td>

    <td><?= htmlspecialchars($row['first_name']) ?></td>

    <td><?= htmlspecialchars($row['middle_name']) ?></td>

    <td><?= htmlspecialchars($row['last_name']) ?></td>

    <td><?= $row['age'] ?></td>

    <td><?= htmlspecialchars($row['gender']) ?></td>

    <td><?= htmlspecialchars($row['address']) ?></td>

    <td><?= htmlspecialchars($row['position']) ?></td>

    <td><?= number_format($row['salary'], 2) ?></td>

    <td>

        <a href="index.php?action=edit&id=<?= $row['faculty_id'] ?>">
            Edit
        </a>

        |

        <a
            href="index.php?action=delete&id=<?= $row['faculty_id'] ?>"
            onclick="return confirm('Are you sure you want to delete this faculty?');"
        >
            Delete
        </a>

    </td>

</tr>

<?php endwhile; ?>


</table>

</div>

</body>

</html>
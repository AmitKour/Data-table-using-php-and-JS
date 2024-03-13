<?php
session_start();
require 'vendor/autoload.php';
use MongoDB\Client;

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$client = new Client("mongodb://localhost:27017");
$collection = $client->login_system->form;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    $document = [
        'name' => $name,
        'age' => $age,
        'gender' => $gender
    ];

    $collection->insertOne($document);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectID($_GET['id'])]);
}


if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $entry = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($id)]);
    $editMode = true;
} else {
    $editMode = false;
}

$dataCursor = $collection->find();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
        body {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            max-width: 400px;
            margin: 20px 0;
        }

        form label {
            margin-bottom: 5px;
        }

        form input,
        form select {
            margin-bottom: 15px;
        }

        .btn-action {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <h1 class="mb-4">Welcome to the Dashboard!</h1>

    <?php if (!$editMode): ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" name="age" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender" class="form-control" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-action">Add Data</button>
        </form>
    <?php else: ?>
        <div class="mt-3">
            <h2>Edit Entry</h2>
            <form action="?action=edit&id=<?php echo $entry['_id']; ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $entry['_id']; ?>">

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" value="<?php echo $entry['name']; ?>" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" name="age" value="<?php echo $entry['age']; ?>" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select name="gender" class="form-control" required>
                        <option value="male" <?php echo ($entry['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo ($entry['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?php echo ($entry['gender'] === 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-action">Save Changes</button>
            </form>
        </div>
    <?php endif; ?>

    <form action="logout.php" method="post">
        <input type="submit" value="Logout">
    </form>

    <h2>Data Table</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataCursor as $data): ?>
                <tr>
                    <td><?php echo $data['name']; ?></td>
                    <td><?php echo $data['age']; ?></td>
                    <td><?php echo $data['gender']; ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $data['_id']; ?>">Edit</a>
                        <a href="?action=delete&id=<?php echo $data['_id']; ?>" onclick="return confirm('Are you sure you want to delete this entry?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button onclick="window.location.href='contacts.php'" class="btn btn-success">Contact Numbers</button>

</body>
</html>

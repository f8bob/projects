<?php
$index = json_decode(file_get_contents("../tests/index.json"), true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        body,
        p,
        h2,
        input,
        textarea,
        button {
            font-family: "Montserrat", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #logo {
            display: flex;
            width: 250px;
            margin: 80px auto 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .details {
            display: none;
        }

    </style>
    <script>
        function toggleDetails(id) {
            var details = document.getElementById(id);
            if (details.style.display === "table-row") {
                details.style.display = "none";
            } else {
                details.style.display = "table-row";
            }
        }
    </script>
</head>

<body>
<img src="../logo.svg" id="logo">
<div class="container">
    <h1>Manage tests</h1>
    <table>
        <thead>
        <tr>
            <th>Test name</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($index as $testName => $shortTestName): ?>
            <tr>
                <td><?php echo $testName; ?></td>
                <td><a href="https://amptive.com/test/admin/index.php?file=<?=$shortTestName?>"><button class="edit">Edit</button></a></td>
                <td><button class="delete" data-file="<?=$shortTestName?>">Delete</button></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>

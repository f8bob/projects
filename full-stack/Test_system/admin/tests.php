
<?php
date_default_timezone_set('America/Los_Angeles');
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
            max-width: 900px;
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
    </style>
</head>

<body>
<img src="../logo.svg" id="logo">
<?php include 'header.php'; ?>
<div class="container">
    <table>
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            <?php

            $json = file_get_contents("../tests/index.json");

            $data = json_decode($json, true);

            foreach ($data as $key => $value) { ?>
                <tr>
                    <td><?= $key ?></td>
                    <td><?= $value ?></td>
                    <td><a href="results.php?test=<?= $value ?>">Results</a></td>
                    <td><a href="../index.php?test=<?=$value?>">Open test</a></td>
                    <td><a href="index.php?test=<?= $value ?>">Edit</a></td>
                </tr>
            <?php }

            ?>
        </tbody>

    </table>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>

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

        table td:not(.left), table th:not(.left) {
            text-align: right;
        }

        .search-container {
            text-align: right;
            margin-bottom: 20px;
        }

        .search-container input[type=text] {
            padding: 10px;
            margin-top: 8px;
            font-size: 17px;
            border: none;
        }

        .search-container input[type=text]:focus {
            outline: 3px solid #ddd;
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

        function search() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementsByTagName("table")[0];
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                var found = false;
                td = tr[i].getElementsByTagName("td");
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                if (found) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    </script>
</head>

<body>
<img src="../logo.svg" id="logo">
<?php include 'header.php'; ?>
<div class="container">
    <div class="search-container">
        <input type="text" id="searchInput" onkeyup="search()" placeholder="Search..." style="border: 1px solid #ccc">
    </div>
    <?php

    $files = glob("../results/*.json");
    usort($files, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });

    echo "<table>";
    echo "<thead><tr>
            <th>Name</th>
            <th>Email</th>
            <th>Test</th>
            <th>Finished Time</th>
            <th>Attempt</th>
            <th></th>
        </tr></thead>";
    echo "<tbody>";

    $index = json_decode(file_get_contents("../tests/index.json"), true);

    foreach ($files as $file) {
        if (isset($_GET['test']) && stripos($file, $_GET['test']) == false) continue;
        $data = json_decode(file_get_contents($file), true);
        echo "<tr>";
        echo "<td>" . $data['fullName'] . "</td>";
        echo "<td>" . $data['email'] . "</td>";

        $key = array_search($data['test'], $index);

        if ($key !== false) {
            $testName = $key;
        } else {
            $testName = $data['test'];
        }
        echo "<td>" . $testName . "</td>";
        echo "<td>" . date('Y-m-d H:i:s A', filemtime($file)) . "</td>";

        // Извлекаем номер попытки из имени файла
        if (strpos($file, "attempt=") !== false) {
            $attempt_pos = strpos($file, "attempt=");
            $attempt = substr($file, $attempt_pos + strlen("attempt="), -5); // -5 для удаления ".json"
        } else {
            $attempt = false;
        }

        // Показываем количество попыток
        echo "<td>" . ($attempt !== false ? $attempt : "1") . "</td>";

        echo "<td><button onclick=\"toggleDetails('" . basename($file, '.json') . "')\">Details</button></td>";
        echo "</tr>";

        echo "<tr class=\"details\" id=\"" . basename($file, '.json') . "\">";
        echo "<td colspan=\"6\">";
        echo "<table><thead><tr><th colspan='2' class='left'>Question</th><th class='left'>Answer</th>
                   <th>Real answer time</th>
                   <th style='cursor: pointer' title='Not necessarily Google. The user just left the question. But, most likely, in order to find the answer'>Google (?)</th>
                   <th>Total</th>
                   </tr></thead><tbody>";
        $tInside = 0;
        $tOutside = 0;
        $tTotal = 0;
        foreach ($data['answers'] as $answer) {
            $outside = isset($answer['questionInactiveTime']) ? $answer['questionInactiveTime'] : '0';
            $total = isset($answer['answerTime']) ? $answer['answerTime'] : '0';
            $inside = $total - $outside;
            $tInside += $inside;
            $tOutside += $outside;
            $tTotal += $total;
            if ($answer['question'] == "Full Name"|| $answer['question'] == "Email") continue;
            echo "<tr>";
            echo "<td colspan='2' class='left' style='width:30%; font-weight: 500'>" . $answer['question'] . "</td>";
            echo "<td class='left' style='width:40%'>" . str_replace("\n", "<br>", $answer['answer']) . "</td>";
            echo "<td>" . $inside . "</td>";
            echo "<td>" . $outside . "</td>";
            echo "<td>" . $total . "</td>";
            echo "</tr>";
        }
// Convert seconds to minutes and hours
        $tInsideMinutes = floor($tInside / 60);
        $tInsideSeconds = $tInside % 60;
        $tOutsideMinutes = floor($tOutside / 60);
        $tOutsideSeconds = $tOutside % 60;
        $tTotalMinutes = floor($tTotal / 60);
        $tTotalSeconds = $tTotal % 60;
        echo "<tr><td colspan='3' style='text-align: right'><b>Total:</b></td><td>$tInsideMinutes m $tInsideSeconds s</td><td>$tOutsideMinutes m $tOutsideSeconds s</td><td>$tTotalMinutes m $tTotalSeconds s</td></tr>";
        echo "</tbody></table>";
        echo "</td></tr>";

    }

    echo "</tbody>";
    echo "</table>";

    ?>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>

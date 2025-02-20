<!DOCTYPE html>
<html>
<head>
    <title>Candidate Testing System</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>body {
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

        h1, h2 {
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="button"], button[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .answer-types {
            display: flex;
            justify-content: space-around;
            margin-bottom: 10px;
        }

        .answer-type {
            padding: 10px 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }

        .answer-type.selected {
            border: 2px solid gray
        }

        .answer-inputs {
            display: none;
        }
        .answer-type {
            display: inline-block;
            width: 100px;
            height: 50px;
            margin-right: 5%;
            cursor: pointer;
        }

        textarea {
            width: 100%;
            width: -webkit-fill-available;
            padding: 10px;
        }

        .answer-inputs {
            display: none;
        }

        .answer-types {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .answer-types > div {
            text-align: center;
        }

        .answer-type.input {
            height: 20px;
            border-radius: 3px;
            padding: 2px 20px;
        }
        .answer-type.text {
            height: 20px;
            border-radius: 3px;
            padding: 20px 20px;
        }
        .answer-type.option {
            margin-top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .answer-type.option ul {
            margin: 0;
        }
        .question {
            padding: 20px;
        }
        .question:nth-child(even) {
            background-color: #f2f2f2;
        }
        .buttons {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        button {
        }
        button[type="button"] {
            width: 150px;
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #cea23d; /* Blue color */
            color: white;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
        }

        button.submit {
            width: 200px;
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50; /* Green color */
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
        }

        h1 {
            padding-top: 40px;
        }
        /* Модальное окно */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            max-width: 500px;
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        /* Стили для формы в модальном окне */
        .modal-content form {
            display: flex;
            flex-direction: column;
        }

        .modal-content input[type="text"] {
            margin-bottom: 10px;
            padding: 5px;
            font-size: 16px;
        }

        .modal-content button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        #myModal {
            display: none;
        }
        #modalForm > * {
            margin-bottom: 20px;
        }

    </style>
    <script>


        function loadTestForEditing(fileName) {
            $.getJSON('../tests/' + fileName + '.json?<?=time()?>', function(data) {
                data.forEach(function(questionData) {
                    var questionType = questionData.type;
                    var $questionDiv = $('<div class="question">');
                    $questionDiv.append('<label for="question">Question:</label>');
                    $questionDiv.append('<input type="text" name="question[]" value="' + questionData.question + '" required><br><br>');
                    $questionDiv.append('<label for="description">Description:</label>');
                    $questionDiv.append('<textarea name="description[]" rows="5">' + questionData.description + '</textarea><br><br>');
                    $questionDiv.append('<label>Type for answer field:</label><br>');
                    var $answerTypesDiv = $('<div class="answer-types">');
                    $answerTypesDiv.append('<div class="answer-type input' + (questionType === 'text' ? ' selected' : '') + '" onclick="selectAnswerType(this, \'text\')">Short</div>');
                    $answerTypesDiv.append('<div class="answer-type text' + (questionType === 'textarea' ? ' selected' : '') + '" onclick="selectAnswerType(this, \'textarea\')">Long</div>');
                    $answerTypesDiv.append('<div class="answer-type option' + (questionType === 'radio' ? ' selected' : '') + '" onclick="selectAnswerType(this, \'radio\')">Select<br>&ordm;&nbsp;&ordm;&nbsp;&ordm;&nbsp;&ordm;</div><br>');
                    $questionDiv.append($answerTypesDiv);
                    if (questionType === 'radio' && questionData.options && questionData.options.length > 0) {
                        var $optionsDiv = $('<div class="answer-inputs" style="display: block">');
                        $optionsDiv.append('<label for="options">Options:</label><br>');
                        questionData.options[0].forEach(function(option) {
                            $optionsDiv.append('<input type="text" name="options[0][]" value="' + option + '" class="option-input"><br>');
                        });
                        $questionDiv.append($optionsDiv);
                    } else {
                        $questionDiv.append('<div class="answer-inputs"><label for="options">Options:</label><br><input type="text" name="options[0][]" class="option-input"><br></div>');
                    }
                    $questionDiv.append('<input type="hidden" name="type[]" value="' + questionType + '">');
                    $('#questions').append($questionDiv);
                });
            });
        }
    </script>
</head>
<body>
<!-- Модальное окно -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <form id="modalForm">
            <label for="testName">Test name (only text):</label>
            <input type="text" id="testName" name="testName">
            <button type="button" onclick="saveTest()">Confirm</button>
        </form>
    </div>
</div>

<img src="../logo.svg" id="logo">
<?php include 'header.php'; ?>
<div class="container">
    <?php

    if (isset($_GET['test']) && !empty($_GET['test'])) {

        $fileName = $_GET['test'];

        $index = json_decode(file_get_contents("../tests/index.json"), true);
        $key = array_search($fileName, $index);

        if ($key !== false) {
            $testName = $key;
        } else {
            $testName = $fileName;
        }
    }
    ?>
    <h1><?=isset($testName) ? "Edit ''<i>$testName</i>''" : 'Create a Test'?></h1>
    <form id="testForm">
        <div id="questions">
        <?php if (isset($fileName)) {
            echo "<script>loadTestForEditing('$fileName')</script>";
        } else {
            // Показать один пустой вопрос по умолчанию
            echo '<div class="question">';
            echo '<label for="question">Question:</label>';
            echo '<input type="text" name="question[]" required><br><br>';
            echo '<label for="description">Description:</label>';
            echo '<textarea name="description[]" rows="5"></textarea><br><br>';
            echo '<label>Type for answer field:</label><br>';
            echo '<div class="answer-types">';
            echo '<div class="answer-type input" onclick="selectAnswerType(this, \'text\')">Short</div>';
            echo '<div class="answer-type text" onclick="selectAnswerType(this, \'textarea\')">Long</div>';
            echo '<div class="answer-type option" onclick="selectAnswerType(this, \'radio\')">Select<br>&ordm;&nbsp;&ordm;&nbsp;&ordm;&nbsp;&ordm;</div><br>';
            echo '</div>';
            echo '<div class="answer-inputs">';
            echo '<label for="options">Options:</label><br>';
            echo '<input type="text" name="options[0][]" class="option-input"><br>';
            echo '</div>';
            echo '<input type="hidden" name="type[]" value="">';
            echo '</div>';
        }
        ?>
        </div>
        <div class="buttons">
            <button type="button" onclick="addQuestion()">Add Question</button>
            <?php if (isset($fileName)) { ?>
                <button type="button" class="submit" onclick="saveTest()">Save</button>
            <?php } else { ?>
                <button type="button" class="submit" onclick="generateTest()">Generate Test</button>
            <?php } ?>
        </div>
    </form>
</div>
<script>
    function generateTest() {
        var allAnswerTypesSelected = true;
        $('.question').each(function() {
            var questionType = $(this).find('input[name="type[]"]').val();
            if (!questionType) {
                allAnswerTypesSelected = false;
                return false; // Exit the loop early if any question type is not selected
            }
        });

        if (allAnswerTypesSelected) {
            // Если все типы ответов выбраны, продолжаем создание теста
            openModal();
        } else {
            // Если не все типы ответов выбраны, выводим сообщение об ошибке
            alert('Please select answer type for all questions.');
        }
    }

    $('#testForm').on('keydown', function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            return false;
        }
    });

    $(document).on('input', '.option-input', function() {
        addOptionInput($(this).closest('.answer-inputs'));
    });

    function selectAnswerType(element, type) {
        var optionsDiv = $(element).closest('.question').find('.answer-inputs');
        $(element).siblings('.answer-type').removeClass('selected');
        $(element).addClass('selected');
        if (type === 'radio') {
            optionsDiv.css('display', 'block');
        } else {
            optionsDiv.css('display', 'none');
        }
        $(element).closest('.question').find('input[type="hidden"]').val(type);
    }


    function addOptionInput(questionDiv) {
        var inputs = questionDiv.find('.option-input');
        if (inputs.length === 0 || inputs.last().val().trim() !== '') {
            var questionIndex = questionDiv.closest('.question').index(); // Getting the index of the current question
            var optionIndex = inputs.length;
            var newInput = $('<input type="text" name="options[' + questionIndex + '][' + optionIndex + ']" class="option-input" placeholder="We hide empty inputs from users."><br>');
            questionDiv.append(newInput);
        }
    }

    function addQuestion() {
        var questionsDiv = $('#questions');
        var newQuestionDiv = $('<div class="question">');
        newQuestionDiv.html(`
        <label for="question">Question:</label>
        <input type="text" name="question[]" required><br><br>
        <label for="description">Description:</label>
        <textarea name="description[]" rows="5"></textarea><br><br>
        <label>Type for answer field:</label><br>
        <div class="answer-types">
            <div class="answer-type input" onclick="selectAnswerType(this, 'text')">Short</div>
            <div class="answer-type text" onclick="selectAnswerType(this, 'textarea')">Long</div>
            <div class="answer-type option" onclick="selectAnswerType(this, 'radio')">Select<br>&ordm;&nbsp;&ordm;&nbsp;&ordm;&nbsp;&ordm;</div><br>
        </div>
        <div class="answer-inputs">
            <label for="options">Options:</label><br>
            <input type="text" name="options[][]" class="option-input"><br>
        </div>
        <input type="hidden" name="type[]" value="">
    `);
        questionsDiv.append(newQuestionDiv);
    }

    function openModal() {

        document.getElementById('myModal').style.display = 'block';
        event.preventDefault();
    }

    function closeModal() {
        document.getElementById('myModal').style.display = 'none';
    }

    function saveTest() {

        const testName = <?=isset($fileName) ? "'$fileName'" : "document.getElementById('testName').value"?>;
        const dropzones = document.querySelectorAll('.dropzone');
        const testObject = {};
        dropzones.forEach((dropzone, index) => {
            const elements = dropzone.querySelectorAll('[data-type]');
            if (elements.length === 2) { // Проверяем, есть ли ровно два элемента в боксе
                const question = elements[0].tagName.toLowerCase() === 'textarea' ? elements[0].value : elements[1].innerText;
                const answerType = elements[1].getAttribute('data-type');
                testObject[index] = [{"question": question, "answer": answerType}];
            }
        });

        const testData = collectTestData(); // Функция, собирающая данные формы
        const formattedTestName = testName.toLowerCase().replace(/\s/g, '_');
        const xhr = new XMLHttpRequest();
        const url = 'create-test.php';
        const edit = '<?= (isset($fileName))  ? 'edit=true' : ''; ?>';
        const params = `testName=${testName}&${edit}&testData=${encodeURIComponent(testData)}`; // Кодируем данные для отправки

        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const response = xhr.responseText;
                if (response.startsWith("Test with the name")) {
                    alert(response);
                } else {
                    $("#modalForm label").html("Link to test:");
                    $("#modalForm input").hide();
                    $("#modalForm button").hide();
                    const testLink = `https://amptive.com/test/index.php?test=${formattedTestName}`;
                    const input = $('<input>', {
                        type: 'text',
                        id: 'testLink',
                        value: testLink
                    });

                    $('.modal-content').append(input);
                }
            }
        };
        xhr.send(params);
        alert("Test changes saved")
        location("")
    }

    function collectTestData() {
        const questions = document.querySelectorAll('.question');
        const testData = [];
        questions.forEach(question => {
            const questionText = question.querySelector('input[name="question[]"]').value;
            const description = question.querySelector('textarea[name="description[]"]').value;
            const type = question.querySelector('input[name="type[]"]').value;
            const options = [];
            const optionInputs = question.querySelectorAll('.option-input');
            optionInputs.forEach(input => {
                options.push(input.value);
            });
            testData.push({
                question: questionText,
                description: description,
                type: type,
                options: options
            });
        });
        return JSON.stringify(testData);
    }

</script>

</body>
</html>

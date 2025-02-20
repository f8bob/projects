<?php
if (isset($_GET['test'])) {
    $test = $_GET['test'];
    if (file_exists("tests/{$test}.json")) {
        $questions = json_decode(file_get_contents("tests/{$test}.json"), true);
    } else {
        exit();
    }
} else {
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test System</title>
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
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .question {
            display: none;
            /* Hide all questions initially */
        }

        h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
            font-weight: 600;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        textarea {
            height: 100px;
        }

        button {
            display: block;
            margin: auto;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #cea23d;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            opacity: 0.7
        }

        #questionTitle {
            text-align: left
        }

        #logo {
            width: 250px;
            margin: 80px auto 20px auto;
        }

        body {
            text-align: center
        }

        textarea {
            max-width: 100%;
        }

        @media (max-width: 978px) {
            .container {
                width: auto;
                margin: 0 20px;
            }
        }
        #inputContainer > div {
            display: flex;
            width: 20px;
            align-items: center;
        }
        input[type="radio"] {
            margin: 0 10px;
        }
        #questionDescription {
            text-align: left;
        }
        #thankYouMessage {
            padding: 20px;
        }
        #thankYouMessage p {
            line-height: 30px;
        }
    </style>
</head>

<body>
<img src="logo.svg" id="logo">
<div class="container">
    <div id="test">
        <div id="nameEmailForm">
            <input type="text" id="fullName" placeholder="Your full name">
            <br>
            <input type="email" id="email" placeholder="Your email">
            <br>
            <button onclick="startTest()">Start Test</button>
        </div>
        <div class="question" id="question">
            <h2 id="questionTitle">Question 1</h2>
            <p id="questionDescription"></p>
            <div id="inputContainer"></div>
            <button onclick="nextQuestion()">Next</button>
        </div>
    </div>
    <div id="thankYouMessage" style="display: none;">
        <h2>Thank you for taking the time to complete our test!</h2>
        <p>We genuinely appreciate your participation and effort in completing the test. Your input is invaluable to us.</p>
        <p>Rest assured that your answers will be carefully reviewed by our team. We strive to analyze each response thoroughly to gain valuable insights.</p>
        <p>Expect to hear from us shortly. We are eager to discuss the results with you and explore any further opportunities for collaboration.</p>
        <p>Once again, thank you for your valuable contribution. We look forward to connecting with you soon.</p>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const questions = <?php echo json_encode($questions); ?>;
    const questionTitle = document.getElementById('questionTitle');
    const questionDescription = document.getElementById('questionDescription');
    const inputContainer = document.getElementById('inputContainer');
    const question = document.getElementById('question');
    const nameEmailForm = document.getElementById('nameEmailForm');
    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    let currentQuestion = -1;
    let totalInactiveTime = 0;
    let totalQuestionInactiveTime = 0;
    let answers = [];

    function startTest() {
        const fullName = fullNameInput.value.trim();
        const email = emailInput.value.trim();
        if (fullName === '' || email === '') {
            alert('Please enter your full name and email.');
            return;
        }
        answers.push({
            question: 'Full Name',
            answer: fullName,
            questionInactiveTime: 0
        });
        answers.push({
            question: 'Email',
            answer: email,
            questionInactiveTime: 0
        });
        nameEmailForm.style.display = 'none';
        nextQuestion();
    }

    function finishTest() {
        // Скрыть блок с вопросами
        document.querySelector('#test').style.display = 'none';

        // Показать блок с благодарностью
        document.getElementById('thankYouMessage').style.display = 'block';
    }


    function nextQuestion() {
        if (currentQuestion !== -1) recordAnswer();
        if (currentQuestion < questions.length - 1) {
            question.style.display = 'block';
            currentQuestion++;
            const currentQ = questions[currentQuestion];
            questionTitle.textContent = `Question ${currentQuestion + 1} of ${questions.length}: ${currentQ.question}`;
            questionDescription.textContent = currentQ.description;
            inputContainer.innerHTML = '';

            // Сохраняем время, когда мы показали вопрос
            lastQuestionTime = Date.now();

            if (currentQ.type === 'text') {
                inputContainer.innerHTML = `<input type="text" placeholder="Your answer here" id="textInput">`;
            } else if (currentQ.type === 'textarea') {
                inputContainer.innerHTML = `<textarea placeholder="Your answer here" id="textArea"></textarea>`;
            } else if (currentQ.type === 'radio') {
                const options = currentQ.options[0]; // Получаем массив вариантов ответа
                options.forEach((option, index) => {
                    const div = document.createElement('div');

                    const radioInput = document.createElement('input');
                    radioInput.type = 'radio';
                    radioInput.id = `option${index}`;
                    radioInput.name = `question${currentQuestion}`;
                    radioInput.value = option;
                    div.appendChild(radioInput);

                    const label = document.createElement('label');
                    label.htmlFor = `option${index}`;
                    label.textContent = option;
                    div.appendChild(label);

                    inputContainer.appendChild(div);
                });
            }
        } else {
            saveResults();
        }
        totalQuestionInactiveTime = 0;
    }

    function recordInactiveTime() {
        let currentTime = Date.now();
        let inactiveTimeSeconds = (currentTime - lastActiveTime) / 1000;
        totalInactiveTime += inactiveTimeSeconds;
        totalQuestionInactiveTime += inactiveTimeSeconds;
        lastActiveTime = currentTime;
    }

    window.onload = function () {
        window.addEventListener("blur", function () {
            lastActiveTime = Date.now();
        });

        window.addEventListener("focus", function () {
            recordInactiveTime();
        });
    };

    function saveResults() {
        // Сохраняем результаты в переменную
        const fullName = fullNameInput.value.trim();
        const email = emailInput.value.trim();
        const username = fullName.toLowerCase().replace(/\s/g, "_");

        const results = {
            fullName: fullName,
            test: '<?= $test ?>',
            email: email,
            answers: answers, // Передаем ответы на вопросы
            totalQuestionInactiveTime: totalInactiveTime
        };

        const jsonData = JSON.stringify(results);

        // Отправка данных на сервер
        $.ajax({
            url: 'save_results.php', // Путь к файлу на сервере для обработки запроса
            type: 'POST',
            contentType: 'application/json',
            data: jsonData,
            success: function(response) {
                finishTest();
            },
            error: function(xhr, status, error) {
                console.error('Error saving results:', error);
            }
        });
    }

    function recordAnswer() {
        const currentQ = questions[currentQuestion];
        const answer = currentQ.type === 'radio' ? document.querySelector(`input[name="question${currentQuestion}"]:checked`).value : (currentQ.type === 'text' ? document.getElementById('textInput').value : document.getElementById('textArea').value);
        let questionInactiveTime = currentQuestion >= 0 ? totalQuestionInactiveTime : 0;

        // Записываем время, проведенное на ответе в секундах
        const answerTime = (Date.now() - lastQuestionTime) / 1000;

        answers.push({
            question: currentQ.question,
            answer: answer,
            questionInactiveTime: questionInactiveTime,
            answerTime: answerTime  // Добавляем время ответа на вопрос
        });
    }

</script>
</body>

</html>

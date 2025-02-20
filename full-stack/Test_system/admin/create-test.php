<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $testName = isset($_POST['testName']) ? $_POST['testName'] : '';
    $shortTestName = strtolower(str_replace(" ", "_", $testName));
    $testData = isset($_POST['testData']) ? $_POST['testData'] : '';
    $edit = isset($_POST['edit']) ? $_POST['edit'] : false;

    // Decode JSON test data
    $decodedTestData = json_decode($testData, true);

    // Prepare test questions array
    $questions = [];
    foreach ($decodedTestData as $index => $questionData) {
        $questionText = isset($questionData['question']) ? $questionData['question'] : '';
        $descriptionText = isset($questionData['description']) ? $questionData['description'] : '';
        $answerType = isset($questionData['type']) ? $questionData['type'] : '';
        $questions[$index]['question'] = $questionText;
        $questions[$index]['description'] = $descriptionText;
        $questions[$index]['type'] = $answerType;
        if ($answerType == 'radio' && isset($questionData['options']) && is_array($questionData['options']) && count($questionData['options']) > 0) {
            $questionData['options'] = array_filter($questionData['options'], function($value) {
                return !empty($value);
            });
            $questions[$index]['options'][] = $questionData['options'];
        }
    }

    // Encode questions array to JSON
    $json_data = json_encode($questions, JSON_PRETTY_PRINT);

    // Check if the test file already exists
    $filePath = "../tests/{$shortTestName}.json";
    if (file_exists($filePath) && !$edit) {
        echo "Test with the name '{$testName}' already exists!";
    } else {
        // Create or overwrite the test file with the provided data
        file_put_contents($filePath, $json_data);
        echo "Test '{$testName}' created successfully!";

        // Add test name and short test name to index.json
        $indexFilePath = "../tests/index.json";
        $indexData = [];
        if (file_exists($indexFilePath)) {
            $indexData = json_decode(file_get_contents($indexFilePath), true);
        }
        $indexData[$testName] = $shortTestName;
        file_put_contents($indexFilePath, json_encode($indexData, JSON_PRETTY_PRINT));
    }
}
?>

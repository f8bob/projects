
# Candidate Testing System
**Not fully finished. There are some bugs, some features are not implemented. But we are not use this system anymore so... Maybe later I'll work on this**

This web-based Candidate Testing System is designed for creating and editing tests for candidates. It allows you to create tests with various question types, including short answers, long answers, and multiple-choice options. The tests can be generated dynamically based on user input and saved for future editing or use.

The main difference of this project from standard testing systems lies in the following aspects:

## Features
- **User Activity Tracking**: The system tracks the user's inactivity time when they are not interacting with the test.
- Create and Edit Tests: You can create new tests or edit existing ones. Each test consists of questions that can have different types of answer fields (short answer, long answer, or multiple-choice).
- Dynamic Question Types: For each question, you can select an answer type:
  - Short text input `(text)`
  - Long text input `(textarea)`
  - Multiple-choice options `(radio)`
- Flexible Options for Multiple Choice: You can define multiple-choice options and add as many as needed.
- Test Customization: Customize the name and description of the test and its questions.
- Modal Confirmation: Once all questions are ready, a modal window is triggered for final confirmation and saving the test.

## Usage
- Creating a Test
  - On the main page, click on "Create a Test" to start creating a new test.
  Add questions using the "Add Question" button.
  For each question, define its description and select the answer type. You can add multiple options for questions with the "Select" answer type.
  Once all questions are added, click "Generate Test" to confirm and save the test.
  Editing an Existing Test

  - If you're editing an existing test, select it from the list.
The test’s questions will be loaded, and you can modify them as needed.
You can change the question text, description, and answer types.
After editing, click "Save" to apply the changes.

## Layout & Styles
  The layout is responsive, and the questions are presented in a clear and user-friendly format. The questions alternate between light and dark backgrounds to improve readability.

- Logo: The test editor includes a logo at the top for easy branding.
- Buttons: There are separate buttons for adding questions, generating tests, and saving the test, all designed to be visually distinct.
- Modal Window: Before finalizing a test, a confirmation modal appears, allowing the user to confirm the test name before saving.

## Technical Details
- Frontend:
  - Uses HTML, CSS, and JavaScript (with jQuery) for a dynamic, user-friendly interface.
  - The interface includes input fields for text, textareas for long answers, and radio buttons for multiple-choice options.
- Backend: PHP is used to manage file storage and retrieval for the tests.

## Demo
A demo of the Candidate Testing System is available <a href="https://f8bob.ru/code-templates/full-stack/Test_system/admin/index.php">here</a>.
## License
This project is released under the MIT License.

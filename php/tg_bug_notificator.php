<?php
/**
 * This PHP code:
 *
 * Handles errors when processing a callback query
 * Sends error messages to an administrator via Telegram
 * Logs errors for debugging
 * It uses a function sendMessageToAdmin to notify the administrator of errors.
 */

function sendMessageToTelegram($message) {
  $adminChatId = 'YOUR_ADMIN_CHAT_ID';
  $token = 'YOUR_BOT_TOKEN';
  $url = "https://api.telegram.org/bot$token/sendMessage";
  $params = array(
    "chat_id" => $adminChatId,
    "text" => $message
  );
  curl_setopt($ch = curl_init($url), CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
  curl_exec($ch);
  curl_close($ch);
}

function test($param1) {
  throw new Exception("Error handling message");
}

try {
  // Try to handle the request
  test("param1");
} catch (Exception $e) {
  // Log and notify administrator
  error_log("Error: " . $e->getMessage());
  sendMessageToTelegram("Error: " . $e->getMessage());
}
?>

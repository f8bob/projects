<?php
if (isset($_POST['delete_confirm']) && $_POST['delete_confirm'] == 'true') {
    $file = $_POST['filename_to_delete'];
    if (file_exists($file)) {
        unlink($file);
        echo "success";
    } else {
        echo "error #1";
    }
} else {
    echo "error #2";
}
?>

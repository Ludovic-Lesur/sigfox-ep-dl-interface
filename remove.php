<?php
    // Global variables.
    global $DL_MESSAGES_FILE;
    global $DL_MESSAGES_LIST_JSON_KEY;
    // Check if a remove button was pressed.
    if (isset($_POST['remove']) != 0) {
        // Get row index.
        $dl_message_index = preg_replace("/[^0-9]/", '', $_POST['remove']);
        // Open file.
        if (file_exists($DL_MESSAGES_FILE) == 0) {
            echo "ERROR: Downlink message file not found.";
            exit();
        }
        // Read current content.
        $file = file_get_contents($DL_MESSAGES_FILE);
        $json = json_decode($file, true);
        // Remove line.
        array_splice($json[$DL_MESSAGES_LIST_JSON_KEY], $dl_message_index, 1);
        // Write file.
        file_put_contents($DL_MESSAGES_FILE, json_encode($json, JSON_PRETTY_PRINT));
    }
?>
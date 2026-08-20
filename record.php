<?php
    // Global variables.
    global $dl_payload;
    global $DL_MESSAGES_FILE;
    global $DL_MESSAGES_LIST_JSON_KEY;
    global $DL_MESSAGES_LIST_JSON_KEY_RECORD_TIME;
    global $DL_MESSAGES_LIST_JSON_KEY_SIGFOX_EP_ID;
    global $DL_MESSAGES_LIST_JSON_KEY_DL_PAYLOAD;
    global $DL_MESSAGES_LIST_JSON_KEY_PERMANENT;
    // Check record action.
    if (isset($_POST['record_action']) != 0) {
        // Open file.
        if (file_exists($DL_MESSAGES_FILE) == 0) {
            echo "ERROR: Downlink message file not found.";
            exit();
        }
        // Read current content.
        $file = file_get_contents($DL_MESSAGES_FILE);
        $json = json_decode($file, true);
        // Compute record time.
        $record_time_epoch_ms_str = strval(time());
        $sigfox_ep_id_str = strtolower($_POST['sigfox_ep_id']);
        for ($idx = 0; $idx < count($dl_payload); $idx++) {
            $dl_payload[$idx] = sprintf("%02x", $dl_payload[$idx]);
        }
        $dl_payload_str = implode($dl_payload);
        $permanent_flag_str = isset($_POST['permanent_flag']) ? "true" : "false";
        // Build JSON item.
        $dl_message = [
            $DL_MESSAGES_LIST_JSON_KEY_RECORD_TIME => $record_time_epoch_ms_str,
            $DL_MESSAGES_LIST_JSON_KEY_SIGFOX_EP_ID => $sigfox_ep_id_str,
            $DL_MESSAGES_LIST_JSON_KEY_DL_PAYLOAD => $dl_payload_str,
            $DL_MESSAGES_LIST_JSON_KEY_PERMANENT => $permanent_flag_str
        ];
        // Append new downlink message.
        array_push($json[$DL_MESSAGES_LIST_JSON_KEY], $dl_message);
        // Write file.
        file_put_contents($DL_MESSAGES_FILE, json_encode($json, JSON_PRETTY_PRINT));
    }
?>
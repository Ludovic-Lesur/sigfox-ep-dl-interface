<?php
    // Global variables.
    global $dl_payload;
    global $DOWNLINK_MESSAGES_FILE;
    // Check record action.
    if (isset($_POST['record_action']) != 0) {
        // Open file.
        if (file_exists($DOWNLINK_MESSAGES_FILE) == 0) {
            echo "ERROR: Downlink message file not found.";
            exit();
        }
        // Read current content.
        $file = file_get_contents($DOWNLINK_MESSAGES_FILE);
        $json = json_decode($file, true);
        $downlink_messages_list = $json['downlink_messages_list'];
        // Compute record time.
        $record_time_epoch_ms_str = strval(time());
        $ep_id_str = strtolower($_POST['sigfox_ep_id']);
        for ($idx = 0; $idx < count($dl_payload); $idx++) {
            $dl_payload[$idx] = sprintf("%02x", $dl_payload[$idx]);
        }
        $dl_payload_str = implode($dl_payload);
        $permanent_flag_str = isset($_POST['permanent_flag']) ? "true" : "false";
        // Build JSON item.
        $downlink_message = [ 
            "record_time" => $record_time_epoch_ms_str,
            "ep_id" => $ep_id_str,
            "dl_payload" => $dl_payload_str,
            "permanent" => $permanent_flag_str
        ];
        // Append new downlink message.
        array_push($json['downlink_messages_list'], $downlink_message);
        // Write file.
        file_put_contents($DOWNLINK_MESSAGES_FILE, json_encode($json, JSON_PRETTY_PRINT));
    }
?>
<?php
    // Global variables.
    global $DL_MESSAGES_FILE;
    global $DL_MESSAGES_LIST_JSON_KEY;
    global $DL_MESSAGES_LIST_JSON_KEY_RECORD_TIME;
    global $DL_MESSAGES_LIST_JSON_KEY_SIGFOX_EP_ID;
    global $DL_MESSAGES_LIST_JSON_KEY_DL_PAYLOAD;
    global $DL_MESSAGES_LIST_JSON_KEY_PERMANENT;
    // Open file.
    if (file_exists($DL_MESSAGES_FILE) == 0) {
        echo "ERROR: Downlink message file not found.";
        exit();
    }
    $file = file_get_contents($DL_MESSAGES_FILE);
    $json = json_decode($file, true);
    $dl_messages_list = $json[$DL_MESSAGES_LIST_JSON_KEY];
    # Generate table.
    echo "<table cellpadding='5'>";
    echo "<tr>";
    echo "<th>Record time</th>";
    echo "<th>EP ID</th>";
    echo "<th>DL payload</th>";
    echo "<th>Permanent</th>";
    echo "</tr>";
    for ($idx = 0; $idx < count($dl_messages_list); $idx++) {
        // Convert timestamp to date.
        $record_time_epoch_ms = intval($dl_messages_list[$idx][$DL_MESSAGES_LIST_JSON_KEY_RECORD_TIME]);
        $record_time = new DateTime("@$record_time_epoch_ms");
        echo "<tr>";
        echo "<td>" . $record_time->format('d/m/Y H:i:s') . "</td>";
        echo "<td>" . $dl_messages_list[$idx][$DL_MESSAGES_LIST_JSON_KEY_SIGFOX_EP_ID] . "</td>";
        echo "<td>" . $dl_messages_list[$idx][$DL_MESSAGES_LIST_JSON_KEY_DL_PAYLOAD] . "</td>";
        echo "<td>" . $dl_messages_list[$idx][$DL_MESSAGES_LIST_JSON_KEY_PERMANENT] . "</td>";
        echo "<td><form method='POST' action=''><input type='submit' name='remove' value='Remove ";
        echo $idx;
        echo "'/></form></td>";
        echo "<tr>";
    }
?>
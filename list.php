<?php
    // Global variables.
    global $DOWNLINK_MESSAGES_FILE;
    // Open file.
    if (file_exists($DOWNLINK_MESSAGES_FILE) == 0) {
        echo "ERROR: Downlink message file not found.";
        exit;
    }
    $file = file_get_contents($DOWNLINK_MESSAGES_FILE);
    $json = json_decode($file, true);
    $downlink_messages_list = $json['downlink_messages_list'];
    # Generate table.
    echo "<table cellpadding='5'>";
    echo "<tr>";
    echo "<th>Record time</th>";
    echo "<th>EP ID</th>";
    echo "<th>DL payload</th>";
    echo "<th>Permanent</th>";
    echo "</tr>";
    for ($idx=0 ; $idx<count($downlink_messages_list) ; $idx++) {
        // Convert timestamp to date.
        $record_time_epoch_ms = intval($downlink_messages_list[$idx]['record_time']);
        $record_time = new DateTime("@$record_time_epoch_ms");
        echo "<tr>";
        echo "<td>".$record_time -> format('d/m/Y H:i:s')."</td>";
        echo "<td>".$downlink_messages_list[$idx]['ep_id']."</td>";
        echo "<td>".$downlink_messages_list[$idx]['dl_payload']."</td>";
        echo "<td>".$downlink_messages_list[$idx]['permanent']."</td>";
        echo "<td><form method='POST' action=''><input type='submit' name='remove' value='Remove ";
        echo $idx;
        echo "'/></form></td>";
        echo "<tr>";
    }
?>
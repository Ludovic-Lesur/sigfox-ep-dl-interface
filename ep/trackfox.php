<?php
    // Global variables.
    global $dl_payload;
    // Local variables.
    $OPERATION_CODE_NAME = array (
        "NOP",
        "Reset",
        "Set configuration"
    );
    $operation_code = 0;
    $operation_code_supported = true;
    // Read operation code.
    if (isset($_POST['operation_code']) != 0) {
        $operation_code = $_POST['operation_code'];
    }
    // Operation codes select form.
    echo "<br><label for='OperationCode'>Operation code </label>";
    echo "<select name='operation_code' onchange='this.form.submit()'>";
    for ($idx = 0; $idx < count($OPERATION_CODE_NAME); $idx++) {
        // Generate form line.
        $selected = ($idx == $operation_code) ? 'selected' : '';
        echo "<option value=$idx $selected> $OPERATION_CODE_NAME[$idx]</option>";
    }
    echo "</select>";
    echo "<br>";
    // First byte is the operation code.
    $dl_payload[0] = $operation_code;
    // Display parameters of the selected operation code.
    switch ($operation_code) {
    case 0:
        // NOP.
        break;
    case 1:
        // Reset.
        break;
    case 2:
        // Set configuration.
        echo "<br><label for='id_start_detection_windows'>Start detection windows (10s) </label>";
        echo "<input id='id_start_detection_windows' type='number' name='start_detection_windows' min='1' max='15' required />";
        echo "<br>";
        echo "<br><label for='id_start_detection_threshold_irq'>Start detection threshold (IRQs) </label>";
        echo "<input id='id_start_detection_threshold_irq' type='number' name='start_detection_threshold_irq' min='1' max='15' required />";
        echo "<br>";
        echo "<br><label for='id_stop_detection_threshold_seconds'>Stop detection threshold (s) </label>";
        echo "<input id='id_stop_detection_threshold_seconds' type='number' name='stop_detection_threshold_seconds' min='30' max='43200' required />";
        echo "<br>";
        echo "<br><label for='id_moving_geoloc_period_seconds'>Moving geolocation period (s) </label>";
        echo "<input id='id_moving_geoloc_period_seconds' type='number' name='moving_geoloc_period_seconds' min='300' max='43200' required />";
        echo "<br>";
        echo "<br><label for='id_stopped_geoloc_period_hours'>Stopped geolocation period (h) </label>";
        echo "<input id='id_stopped_geoloc_period_hours' type='number' name='stopped_geoloc_period_hours' min='1' max='168' required />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = (intval($_POST['start_detection_windows'], 10)) & 0xFF;
        $dl_payload[2] = (intval($_POST['start_detection_threshold_irq'], 10)) & 0xFF;
        $dl_payload[3] = (intval($_POST['stop_detection_threshold_seconds'], 10) >> 8) & 0xFF;
        $dl_payload[4] = (intval($_POST['stop_detection_threshold_seconds'], 10) >> 0) & 0xFF;
        $dl_payload[5] = (intval($_POST['moving_geoloc_period_seconds'], 10) >> 8) & 0xFF;
        $dl_payload[6] = (intval($_POST['moving_geoloc_period_seconds'], 10) >> 0) & 0xFF;
        $dl_payload[7] = (intval($_POST['stopped_geoloc_period_hours'], 10)) & 0xFF;
        break;
    default:
        $operation_code_supported = false;
        echo "<br>Unknown operation code.";
        break;
    }
    // Record button.
    if ($operation_code_supported == true) {
        echo "<br>";
        echo "<input type='submit' name='record_action' value='Record action'/>";
    }
?>
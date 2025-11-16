<?php
    // Global variables.
    global $dl_payload;
    // Local variables.
    $OPERATION_CODE_NAME = array (
        "NOP",
        "Reset",
        "Set timings"
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
        // Set weather data period.
        echo "<br><label for='id_monitoring_period_minutes'>Monitoring period (minutes) </label>";
        echo "<input id='id_monitoring_period_minutes' type='number' name='monitoring_period_minutes' min='10' max='10080' required />";
        echo "<br>";
        echo "<br><label for='id_air_quality_period_minutes'>Air quality period (minutes) </label>";
        echo "<input id='id_air_quality_period_minutes' type='number' name='air_quality_period_minutes' min='10' max='10080' required />";
        echo "<br>";
        echo "<br><label for='id_accelerometer_blanking_time_seconds'>Accelerometer blanking time (seconds) </label>";
        echo "<input id='id_accelerometer_blanking_time_seconds' type='number' name='accelerometer_blanking_time_seconds' min='60' max='17280' required />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = (intval($_POST['monitoring_period_minutes'], 10) >> 8) & 0xFF;
        $dl_payload[2] = (intval($_POST['monitoring_period_minutes'], 10) >> 0) & 0xFF;
        $dl_payload[3] = (intval($_POST['air_quality_period_minutes'], 10) >> 8) & 0xFF;
        $dl_payload[4] = (intval($_POST['air_quality_period_minutes'], 10) >> 0) & 0xFF;
        $dl_payload[5] = (intval($_POST['accelerometer_blanking_time_seconds'], 10) >> 8) & 0xFF;
        $dl_payload[6] = (intval($_POST['accelerometer_blanking_time_seconds'], 10) >> 0) & 0xFF;
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
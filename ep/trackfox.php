<?php
    // Global variables.
    global $dl_payload;
    // Local variables.
    $OPERATION_CODE_NAME = array (
        "NOP",
        "Reset",
        "Set monitoring period",
        "Set tracking parameters",
        "Set GPS settings"
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
        // Set monitoring period.
        echo "<br><label for='id_monitoring_period_minutes'>Monitoring period (minutes) </label>";
        echo "<input id='id_monitoring_period_minutes' type='number' name='monitoring_period_minutes' min='30' max='240' required />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = (intval($_POST['monitoring_period_minutes'], 10)) & 0xFF;
        break;
    case 3:
        // Set tracking parameters.
        echo "<br><label for='id_start_detection_windows'>Start detection windows </label>";
        echo "<input id='id_start_detection_windows' type='number' name='start_detection_windows' min='1' max='30' required />";
        echo "<br>";
        echo "<br><label for='id_start_detection_threshold_irq'>Start detection threshold (IRQs) </label>";
        echo "<input id='id_start_detection_threshold_irq' type='number' name='start_detection_threshold_irq' min='1' max='50' required />";
        echo "<br>";
        echo "<br><label for='id_stop_detection_threshold_seconds'>Stop detection threshold (minutes) </label>";
        echo "<input id='id_stop_detection_threshold_seconds' type='number' name='stop_detection_threshold_seconds' min='1' max='240' required />";
        echo "<br>";
        echo "<br><label for='id_geoloc_period_moving_minutes'>Moving geolocation period (minutes) </label>";
        echo "<input id='id_geoloc_period_moving_minutes' type='number' name='geoloc_period_moving_minutes' min='5' max='240' required />";
        echo "<br>";
        echo "<br><label for='id_geoloc_period_stopped_hours'>Stopped geolocation period (hours) </label>";
        echo "<input id='id_geoloc_period_stopped_hours' type='number' name='geoloc_period_stopped_hours' min='1' max='168' required />";
        echo "<br>";
        echo "<br><label for='horns'>Adaptative TX power</label>";
        echo "<input type='checkbox' name='adaptative_tx_power_flag'";
        if (isset($_POST['adaptative_tx_power_flag']) != 0) {
            echo " checked";
        }
        echo "/>";
        echo "<br>";
        echo "<br><label for='horns'>Adaptative UL bit rate</label>";
        echo "<input type='checkbox' name='adaptative_ul_bit_rate_flag'";
        if (isset($_POST['adaptative_ul_bit_rate_flag']) != 0) {
            echo " checked";
        }
        echo "/>";
        echo "<br>";
        // Get flags value.
        $adaptative_tx_power_flag = isset($_POST['adaptative_tx_power_flag']) ? 1 : 0;
        $adaptative_ul_bit_rate_flag = isset($_POST['adaptative_ul_bit_rate_flag']) ? 1 : 0;
        // Build DL payload.
        $dl_payload[1] = (intval($_POST['start_detection_windows'], 10)) & 0xFF;
        $dl_payload[2] = (intval($_POST['start_detection_threshold_irq'], 10)) & 0xFF;
        $dl_payload[3] = (intval($_POST['stop_detection_threshold_seconds'], 10)) & 0xFF;
        $dl_payload[4] = (intval($_POST['geoloc_period_moving_minutes'], 10)) & 0xFF;
        $dl_payload[5] = (intval($_POST['geoloc_period_stopped_hours'], 10)) & 0xFF;
        $dl_payload[6] = (($adaptative_tx_power_flag << 1) | ($adaptative_ul_bit_rate_flag << 0));
        break;
    case 4:
        // Set GPS settings.
        echo "<br><label for='id_gps_timeout_seconds'>GPS timeout (seconds) </label>";
        echo "<input id='id_gps_timeout_seconds' type='number' name='gps_timeout_seconds' min='30' max='180' required />";
        echo "<br>";
        echo "<br><label for='id_gps_altitude_stability_filter_moving'>GPS moving altitude stability filter </label>";
        echo "<input id='id_gps_altitude_stability_filter_moving' type='number' name='gps_altitude_stability_filter_moving' min='0' max='15' required />";
        echo "<br>";
        echo "<br><label for='id_gps_altitude_stability_filter_stopped'>GPS stopped altitude stability filter </label>";
        echo "<input id='id_gps_altitude_stability_filter_stopped' type='number' name='gps_altitude_stability_filter_stopped' min='0' max='15' required />";
        echo "<br>";
        // Get altitude stability filters value.
        $gps_altitude_stability_filter_moving = (intval($_POST['gps_altitude_stability_filter_moving'], 10)) & 0xFF;
        $gps_altitude_stability_filter_stopped = (intval($_POST['gps_altitude_stability_filter_stopped'], 10)) & 0xFF;
        // Build DL payload.
        $dl_payload[1] = (intval($_POST['gps_timeout_seconds'], 10)) & 0xFF;
        $dl_payload[2] = (($gps_altitude_stability_filter_moving << 4) | ($gps_altitude_stability_filter_stopped << 0));
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
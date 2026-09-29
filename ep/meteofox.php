<?php
    // Global variables.
    global $dl_payload;
    // Local variables.
    $OPERATION_CODE_NAME = array (
        "NOP",
        "Reset",
        "Set weather data period",
        "Set date and time",
        "Set lux and UV index calibration",
        "Start lux and UV index calibration",
    );
    $WEATHER_DATA_PERIOD_NAME = array (
        "60 minutes",
        "30 minutes",
        "20 minutes",
        "15 minutes",
        "12 minutes",
        "10 minutes",
    );
    $operation_code = 0;
    $operation_code_supported = true;
    $weather_data_period = 0;
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
        if (isset($_POST['weather_data_period']) != 0) {
            $weather_data_period = $_POST['weather_data_period'];
        }
        echo "<br><label for='WeatherDataPeriod'>Weather data period </label>";
        echo "<select name='weather_data_period' onchange='this.form.submit()'>";
        for ($idx = 0; $idx < count($WEATHER_DATA_PERIOD_NAME); $idx++) {
            // Generate form line.
            $selected = ($idx == $weather_data_period) ? 'selected' : '';
            echo "<option value=$idx $selected> $WEATHER_DATA_PERIOD_NAME[$idx]</option>";
        }
        echo "</select>";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = $weather_data_period;
        break;
    case 3:
        // Set date and time.
        // Timestamp will be filled by the server when the downlink request will occur.
        break;
    case 4:
        // Set lux and UV index calibration.
        echo "<br><label for='id_lux_gain_numerator'>Lux gain numerator </label>";
        echo "<input id='id_lux_gain_numerator' type='number' name='lux_gain_numerator' min='1' max='4095' required />";
        echo "<br>";
        echo "<br><label for='id_lux_gain_denominator'>Lux gain denominator </label>";
        echo "<input id='id_lux_gain_denominator' type='number' name='lux_gain_denominator' min='1' max='4095' required />";
        echo "<br>";
        echo "<br><label for='id_uvi_gain_numerator'>UV index gain numerator </label>";
        echo "<input id='id_uvi_gain_numerator' type='number' name='uvi_gain_numerator' min='1' max='4095' required />";
        echo "<br>";
        echo "<br><label for='id_uvi_gain_denominator'>UV index gain denominator </label>";
        echo "<input id='id_uvi_gain_denominator' type='number' name='uvi_gain_denominator' min='1' max='4095' required />";
        echo "<br>";
        // Extract fields.
        $lux_gain_numerator = intval($_POST['lux_gain_numerator'], 10);
        $lux_gain_denominator = intval($_POST['lux_gain_denominator'], 10);
        $uvi_gain_numerator = intval($_POST['uvi_gain_numerator'], 10);
        $uvi_gain_denominator = intval($_POST['uvi_gain_denominator'], 10);
        // Build DL payload.
        $dl_payload[1] = ($lux_gain_numerator >> 4) & 0xFF;
        $dl_payload[2] = (((($lux_gain_numerator >> 0) & 0x0F) << 4) | (($lux_gain_denominator >> 8) & 0x0F));
        $dl_payload[3] = (($lux_gain_denominator >> 0) & 0xFF);
        $dl_payload[4] = ($uvi_gain_numerator >> 4) & 0xFF;
        $dl_payload[5] = (((($uvi_gain_numerator >> 0) & 0x0F) << 4) | (($uvi_gain_denominator >> 8) & 0x0F));
        $dl_payload[6] = (($uvi_gain_denominator >> 0) & 0xFF);
        break;
    case 5:
        // Start lux and UV index calibration.
        echo "<br><label for='id_calibration_time_hours'>Calibration time (hours) </label>";
        echo "<input id='id_calibration_time_hours' type='number' name='calibration_time_hours' min='1' max='168' required />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = intval($_POST['calibration_time_hours'], 10);
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
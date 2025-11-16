<?php
    // Global variables.
    global $dl_payload;
    // Local variables.
    $OPERATION_CODE_NAME = array (
        "NOP",
        "Reset",
        "Set weather data period"
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
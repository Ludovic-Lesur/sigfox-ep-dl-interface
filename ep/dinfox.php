<?php
	// Global variables.
    global $dl_payload;
    // Local variables.
	$OPERATION_CODE_NAME = array(
	    "NOP",
	    "Single full read",
	    "Single full write",
	    "Single masked write",
	    "Temporary full write",
	    "Temporary masked write",
	    "Successive full write",
	    "Successive masked write",
	    "Dual full write",
	    "Triple full write",
	    "Dual node write"
	);
	$operation_code = 0;
	$operation_code_supported = true;
	// Read operation code.
	if (isset($_POST['operation_code']) != 0) {
	    $operation_code=$_POST['operation_code'];
	}
	// Start form.
	echo "<form method='POST' action=''>";
	// EP-ID field.
	echo "<br><label for='id_sigfox_ep_id'>EP ID </label>";
	echo "<input id='id_sigfox_ep_id' type='text' name='sigfox_ep_id' pattern='[a-fA-F\d]{8,8}'";
	if ((isset($_POST['sigfox_ep_id']) != 0) && (isset($_POST['record_action']) == 0)) {
	    if (strlen($_POST['sigfox_ep_id']) > 0) {
	        echo " value=";
	        echo $_POST['sigfox_ep_id'];
	    }
	}
	echo " required size='10' />";
	echo "<br>";
	// Permanent attribute check box.
	echo "<br><label for='horns'>Permanent</label>";
	echo "<input type='checkbox' name='permanent_flag'";
	if ((isset($_POST['permanent_flag']) != 0) && (isset($_POST['record_action']) == 0)) {
	    echo " checked";
	}
	echo "/>";
	echo "<br>";
	// Operation codes select form.
	echo "<br><label for='OperationCode'>Operation code </label>";
	echo "<select name='operation_code' onchange='this.form.submit()'>";
	for ($idx=0 ; $idx<count($OPERATION_CODE_NAME) ; $idx++) {
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
        // Single full read.
        echo "<br><label for='id_node_addr'>Node address </label>";
        echo "<input id='id_node_addr' type='text' name='node_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_addr'>Register address </label>";
        echo "<input id='id_reg_addr' type='text' name='reg_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = intval($_POST['node_addr'], 16) & 0xFF;
        $dl_payload[2] = intval($_POST['reg_addr'],  16) & 0xFF;
        break;
    case 2:
    case 4:
        // Single or temporary full write.
        echo "<br><label for='id_node_addr'>Node address </label>";
        echo "<input id='id_node_addr' type='text' name='node_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_addr'>Register address </label>";
        echo "<input id='id_reg_addr' type='text' name='reg_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_value'>Register value </label>";
        echo "<input id='id_reg_value' type='text' name='reg_value' pattern='[a-fA-F\d]{2,8}' required size='10' />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = intval($_POST['node_addr'], 16) & 0xFF;
        $dl_payload[2] = intval($_POST['reg_addr'],  16) & 0xFF;
        $dl_payload[3] = (intval($_POST['reg_value'], 16) >> 24) & 0xFF;
        $dl_payload[4] = (intval($_POST['reg_value'], 16) >> 16) & 0xFF;
        $dl_payload[5] = (intval($_POST['reg_value'], 16) >> 8)  & 0xFF;
        $dl_payload[6] = (intval($_POST['reg_value'], 16) >> 0)  & 0xFF;
        // Additional duration field for temporary operation.
        if ($operation_code == 4) {
            echo "<br><label for='id_duration'>Duration </label>";
            echo "<input id='id_duration' type='text' name='duration' pattern='[a-fA-F\d]{2,2}' required size='10' />";
            echo "<br>";
            $dl_payload[7] = intval($_POST['duration'], 16) & 0xFF;
        }
        break;
    case 3:
    case 5:
        // Single or temporary masked write.
        echo "<br><label for='id_node_addr'>Node address </label>";
        echo "<input id='id_node_addr' type='text' name='node_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_addr'>Register address </label>";
        echo "<input id='id_reg_addr' type='text' name='reg_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_mask'>Register mask </label>";
        echo "<input id='id_reg_mask' type='text' name='reg_mask' pattern='[a-fA-F\d]{2,4}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_value'>Register value </label>";
        echo "<input id='id_reg_value' type='text' name='reg_value' pattern='[a-fA-F\d]{2,4}' required size='10' />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = intval($_POST['node_addr'], 16) & 0xFF;
        $dl_payload[2] = intval($_POST['reg_addr'],  16) & 0xFF;
        $dl_payload[3] = (intval($_POST['reg_mask'], 16)  >> 8) & 0xFF;
        $dl_payload[4] = (intval($_POST['reg_mask'], 16)  >> 0) & 0xFF;
        $dl_payload[5] = (intval($_POST['reg_value'], 16) >> 8) & 0xFF;
        $dl_payload[6] = (intval($_POST['reg_value'], 16) >> 0) & 0xFF;
        // Additional duration field for temporary operation.
        if ($operation_code == 5) {
            echo "<br><label for='id_duration'>Duration </label>";
            echo "<input id='id_duration' type='text' name='duration' pattern='[a-fA-F\d]{2,2}' required size='10' />";
            echo "<br>";
            $dl_payload[7] = intval($_POST['duration'], 16) & 0xFF;
        }
        break;
    case 6:
        // Successive full write.
        echo "<br><label for='id_node_addr'>Node address </label>";
        echo "<input id='id_node_addr' type='text' name='node_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_addr'>Register address </label>";
        echo "<input id='id_reg_addr' type='text' name='reg_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_value_1'>Register value 1 </label>";
        echo "<input id='id_reg_value_1' type='text' name='reg_value_1' pattern='[a-fA-F\d]{2,4}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_value_2'>Register value 2 </label>";
        echo "<input id='id_reg_value_2' type='text' name='reg_value_2' pattern='[a-fA-F\d]{2,4}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_duration'>Duration </label>";
        echo "<input id='id_duration' type='text' name='duration' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = intval($_POST['node_addr'], 16) & 0xFF;
        $dl_payload[2] = intval($_POST['reg_addr'],  16) & 0xFF;
        $dl_payload[3] = (intval($_POST['reg_value_1'], 16) >> 8) & 0xFF;
        $dl_payload[4] = (intval($_POST['reg_value_1'], 16) >> 0) & 0xFF;
        $dl_payload[5] = (intval($_POST['reg_value_2'], 16) >> 8) & 0xFF;
        $dl_payload[6] = (intval($_POST['reg_value_2'], 16) >> 0) & 0xFF;
        $dl_payload[7] = intval($_POST['duration'], 16) & 0xFF;
        break;
    case 7:
        // Successive masked write.
        echo "<br><label for='id_node_addr'>Node address </label>";
        echo "<input id='id_node_addr' type='text' name='node_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_addr'>Register address </label>";
        echo "<input id='id_reg_addr' type='text' name='reg_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_mask'>Register mask </label>";
        echo "<input id='id_reg_mask' type='text' name='reg_mask' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_value_1'>Register value 1 </label>";
        echo "<input id='id_reg_value_1' type='text' name='reg_value_1' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_value_2'>Register value 2 </label>";
        echo "<input id='id_reg_value_2' type='text' name='reg_value_2' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_duration'>Duration </label>";
        echo "<input id='id_duration' type='text' name='duration' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = intval($_POST['node_addr'],   16) & 0xFF;
        $dl_payload[2] = intval($_POST['reg_addr'],    16) & 0xFF;
        $dl_payload[3] = intval($_POST['reg_mask'],    16) & 0xFF;
        $dl_payload[4] = intval($_POST['reg_value_1'], 16) & 0xFF;
        $dl_payload[5] = intval($_POST['reg_value_2'], 16) & 0xFF;
        $dl_payload[6] = intval($_POST['duration'],    16) & 0xFF;
        break;
    case 8:
        // Dual full write.
        echo "<br><label for='id_node_addr'>Node address </label>";
        echo "<input id='id_node_addr' type='text' name='node_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_1_addr'>Register 1 address </label>";
        echo "<input id='id_reg_1_addr' type='text' name='reg_1_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_1_value'>Register 1 value </label>";
        echo "<input id='id_reg_1_value' type='text' name='reg_1_value' pattern='[a-fA-F\d]{2,4}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_2_addr'>Register 2 address </label>";
        echo "<input id='id_reg_2_addr' type='text' name='reg_2_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_2_value'>Register 2 value </label>";
        echo "<input id='id_reg_2_value' type='text' name='reg_2_value' pattern='[a-fA-F\d]{2,4}' required size='10' />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = intval($_POST['node_addr'],  16) & 0xFF;
        $dl_payload[2] = intval($_POST['reg_1_addr'], 16) & 0xFF;
        $dl_payload[3] = (intval($_POST['reg_1_value'], 16) >> 8) & 0xFF;
        $dl_payload[4] = (intval($_POST['reg_1_value'], 16) >> 0) & 0xFF;
        $dl_payload[5] = intval($_POST['reg_2_addr'], 16) & 0xFF;
        $dl_payload[6] = (intval($_POST['reg_2_value'], 16) >> 8) & 0xFF;
        $dl_payload[7] = (intval($_POST['reg_2_value'], 16) >> 0) & 0xFF;
        break;
    case 9:
        // Dual full write.
        echo "<br><label for='id_node_addr'>Node address </label>";
        echo "<input id='id_node_addr' type='text' name='node_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_1_addr'>Register 1 address </label>";
        echo "<input id='id_reg_1_addr' type='text' name='reg_1_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_1_value'>Register 1 value </label>";
        echo "<input id='id_reg_1_value' type='text' name='reg_1_value' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_2_addr'>Register 2 address </label>";
        echo "<input id='id_reg_2_addr' type='text' name='reg_2_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_2_value'>Register 2 value </label>";
        echo "<input id='id_reg_2_value' type='text' name='reg_2_value' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_3_addr'>Register 3 address </label>";
        echo "<input id='id_reg_3_addr' type='text' name='reg_3_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_3_value'>Register 3 value </label>";
        echo "<input id='id_reg_3_value' type='text' name='reg_3_value' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = intval($_POST['node_addr'],   16) & 0xFF;
        $dl_payload[2] = intval($_POST['reg_1_addr'],  16) & 0xFF;
        $dl_payload[3] = intval($_POST['reg_1_value'], 16) & 0xFF;
        $dl_payload[4] = intval($_POST['reg_2_addr'],  16) & 0xFF;
        $dl_payload[5] = intval($_POST['reg_2_value'], 16) & 0xFF;
        $dl_payload[6] = intval($_POST['reg_3_addr'],  16) & 0xFF;
        $dl_payload[7] = intval($_POST['reg_3_value'], 16) & 0xFF;
        break;
    case 10:
        // Dual node write.
        echo "<br><label for='id_node_1_addr'>Node 1 address </label>";
        echo "<input id='id_node_1_addr' type='text' name='node_1_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_1_addr'>Register 1 address </label>";
        echo "<input id='id_reg_1_addr' type='text' name='reg_1_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_1_value'>Register 1 value </label>";
        echo "<input id='id_reg_1_value' type='text' name='reg_1_value' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_node_2_addr'>Node 2 address </label>";
        echo "<input id='id_node_2_addr' type='text' name='node_2_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_2_addr'>Register 2 address </label>";
        echo "<input id='id_reg_2_addr' type='text' name='reg_2_addr' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        echo "<br><label for='id_reg_2_value'>Register 2 value </label>";
        echo "<input id='id_reg_2_value' type='text' name='reg_2_value' pattern='[a-fA-F\d]{2,2}' required size='10' />";
        echo "<br>";
        // Build DL payload.
        $dl_payload[1] = intval($_POST['node_1_addr'], 16) & 0xFF;
        $dl_payload[2] = intval($_POST['reg_1_addr'],  16) & 0xFF;
        $dl_payload[3] = intval($_POST['reg_1_value'], 16) & 0xFF;
        $dl_payload[4] = intval($_POST['node_2_addr'], 16) & 0xFF;
        $dl_payload[5] = intval($_POST['reg_2_addr'],  16) & 0xFF;
        $dl_payload[6] = intval($_POST['reg_2_value'], 16) & 0xFF;
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
    // End form.
    echo "</form>";
?>
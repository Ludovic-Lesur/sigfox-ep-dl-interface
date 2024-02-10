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
        $dl_payload[1] = intval($_POST['node_addr'], 16);
        $dl_payload[2] = intval($_POST['reg_addr'], 16);
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
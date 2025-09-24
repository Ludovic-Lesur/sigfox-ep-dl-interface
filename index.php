<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8'>
		<style>
            body {font-family: Ubuntu;}
            table {border-collapse: collapse;}
            td, th {border: 1px solid black;}
            .column {
                float: left;
                width: 50%;
            }
        </style>
		<title>Sigfox End-Point Downlink Interface</title>
	</head>
	<?php
        // Global variables.
        $DOWNLINK_MESSAGES_FILE = '../sigfox-ep-server/sigfox_downlink_messages.json';
        $dl_payload = array(0, 0, 0, 0, 0, 0, 0, 0);
        $DEVICE_TYPE_LIST = array(
            "MeteoFox",
            "ATXFox",
            "TrackFox",
            "DINFox",
            "HomeFox"
        );
        $device_type = 0;
	?>
	<body>
		<h1>Sigfox End Point Downlink Interface</h1>
		<hr>
		<div class="column left">
			<h2>DL payload builder</h2>
			<?php
			// Read device type.
			if (isset($_POST['device_type']) != 0) {
			    $device_type=$_POST['device_type'];
			}
			// Start form.
			echo "<form method='POST' action=''>";
			// Device type select form.
			echo "<br><label for='DeviceType'>Device type </label>";
			echo "<select name='device_type' onchange='this.form.submit()'>";
			for ($idx=0 ; $idx<count($DEVICE_TYPE_LIST) ; $idx++) {
			    // Generate form line.
			    $selected = ($idx == $device_type) ? 'selected' : '';
			    echo "<option value=$idx $selected> $DEVICE_TYPE_LIST[$idx]</option>";
			}
			echo "</select>";
			echo "<br>";
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
			// Display payload builder for the selected device type.
			switch ($device_type) {
		    case 3:
		        include 'ep/dinfox.php';
		        break;
		    default:
		        echo "<br>No downlink message defined for device type $DEVICE_TYPE_LIST[$device_type].<br>";
		        break;
			}
			// End form.
			echo "</form>";
			?>
		</div>
		<?php include 'record.php' ?>
		<?php include 'remove.php' ?>
		<h2>DL messages list</h2>
		<?php include 'list.php'; ?>
	</body>
</html>
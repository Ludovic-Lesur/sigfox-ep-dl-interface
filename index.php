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
	?>
	<body>
		<h1>Sigfox End Point Downlink Interface</h1>
		<hr>
		<div class="column left">
			<h2>DL payload builder</h2>
			<?php include 'ep/dinfox.php'; ?>
		</div>
		<?php include 'record.php' ?>
		<?php include 'remove.php' ?>
		<h2>DL messages list</h2>
		<?php include 'list.php'; ?>
	</body>
</html>
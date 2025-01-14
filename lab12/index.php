<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="pl" />
	<script src="js/kolorujtlo.js" type="text/javascript"></script>
	<script src="js/timedate.js" type="text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="css/style.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        h1 {
            margin: 0;
        }
		table, th, td {
			text-align: center;
            background-color: #444;
            padding: 10px;
		}
		th a{
            color: #fff;
            text-decoration: none;
            margin: 10px;
        }

        section {
            padding: 20px;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body onload="startclock()">
<?php
include("cfg.php");
include("showpage.php");
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
if ($_GET['idp'] == '') {
    echo PokazPodstrone(1);
} elseif ($_GET['idp'] == 'podstrona1') {
    echo PokazPodstrone(2);
} elseif ($_GET['idp'] == 'podstrona2') {
    echo PokazPodstrone(3);
} elseif ($_GET['idp'] == 'podstrona4') {
    echo PokazPodstrone(4);
} elseif ($_GET['idp'] == 'podstrona3') {
    echo PokazPodstrone(5);
}	elseif ($_GET['idp'] == 'podstrona5') {
    echo PokazPodstrone(6);
} else {
    echo PokazPodstrone(1);
}
?>
    <footer>
        &copy; Daniel Rudziński
    </footer>
	<?php
	$nr_indeksu = '169359';
	$nrGrupy = '3';
	echo 'Autor: Daniel Rudziński '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
	?>
</body>
</html>

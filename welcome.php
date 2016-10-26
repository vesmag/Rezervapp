<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<title> Rezervapp </title>

	<!-- bootstrap -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

  	<!-- dodatni stil -->
  	<link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

<?php
	if( !isset( $_SESSION["user"] ) ) header("Location: /~amkarlo/index.php");
	if ($_SESSION["user"]=="admin" ) header("Location: /~amkarlo/admin.php");
	else if( isset($_POST["logout"] ) ){
		session_unset();
		session_destroy();
		header("Location: /~amkarlo/index.php");
	}
	include('baze.php');

    //izvlačenje korisnikovih rezervacija
    $rezervacije = get_all_reservations_by_user ( $_SESSION["user"] );
    $broj_rezervacija = sizeof( $rezervacije );

    if (isset($_GET["r"])){
        $i = $_GET["r"];
        delete_from_reservations($rezervacije[$i][0], $rezervacije[$i][1], $rezervacije[$i][2]);
        $rezervacije = get_all_reservations_by_user ( $_SESSION["user"] );
        $broj_rezervacija = sizeof( $rezervacije );
    }

      function tablica() {
        global $rezervacije;
        global $broj_rezervacija;

        $table = "<div id='tablica' style='top: 40%;'>";
    		if( $broj_rezervacija == 0 )
				echo "<p>Nemate rezervacija</p>";
			else {
		 	// ako nisu prazne rezervacije

           $table.="<div><table class='table table-hover' style='position:absolute; left:10%; width: 80%;' ><thead>
     			<tr>
        			<th style='width:25%;'> Datum </th>
        			<th style='width:25%;'> Vrijeme </th>
        			<th style='width:25%;'> Predavaona </th>
        			<th style='width:25%;'>  </th>
      			</tr>
      			</thead><tbody>";

     		 for( $i=0; $i < $broj_rezervacija;  ++$i ){
     			 $table.= "<tr>";
     				for( $j=0; $j<3; $j++ ){
              		 $table.="<td>".$rezervacije[$i][$j]."</td>";
                 	 }
                 $table.="<td> <button type='button' onclick='obradi(".$i.")' class='btn btn-default btn-sm'>
          					<span class='glyphicon glyphicon-trash'></span> Izbriši
        				 </button>
					</td></tr>";
             }
                $table.="</tbody></table></div>";
	 }
        $table.="</div>";

        return $table;
    }

?>
    <script>

        function obradi(val) {
        var xhr1 = new XMLHttpRequest();

        xhr1.onreadystatechange = function() {
            if( xhr1.readyState == 4 && xhr1.status == 200 ) {
            document.body.innerHTML = xhr1.responseText;
                }
            }
        xhr1.open( "GET", "welcome.php?r=" + val, true );
        xhr1.send();
    }

    </script>

    <img src="dove.png" style="width: 40%; height: 50%; position: absolute; bottom: 3%; right: 2%;">

    <nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="welcome.php"> Rezervapp </a>
			</div>
			<div>
				<ul class="nav navbar-nav">
					<li class="active"><a href="welcome.php"> Početna </a></li>
        			<li><a href="calendar.php"> Kalendar </a></li>
      			</ul>
      			<ul class="nav navbar-nav navbar-right">
        			<li> <a href=""> <span class="glyphicon glyphicon-user"> </span> <?php echo $_SESSION["user"] ?> </a> </li>
        			<li> <a href="#">
        				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        					<span class="glyphicon glyphicon-log-out" style="color: #5685B5;"> </span>
							<input type="submit" class="btn-link" name="logout" value="Logout">
        				</form>
        				</a>
       				</li>
      			</ul>
    		</div>
  		</div>
	</nav>

<?php
// tu nacrtamo tablicu s rezervacijama, poslije headera
  echo tablica();
?>
</body>

</html>

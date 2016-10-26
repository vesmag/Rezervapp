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

	<script type="text/javascript">

		function prikaz_zahtjeva(){
			document.getElementById("prikazzahtjeva").style.visibility = "visible";
			document.getElementById("prikazkorisnika").style.visibility = "hidden";
			document.getElementById("prikazrezervacija").style.visibility = "hidden";
		}

		function prikaz_korisnika(){
			document.getElementById("prikazkorisnika").style.visibility = "visible";
			document.getElementById("prikazzahtjeva").style.visibility = "hidden";
			document.getElementById("prikazrezervacija").style.visibility = "hidden";
		}

        function prikaz_rezervacija(){
			document.getElementById("prikazkorisnika").style.visibility = "hidden";
			document.getElementById("prikazzahtjeva").style.visibility = "hidden";
			document.getElementById("prikazrezervacija").style.visibility = "visible";
		}

        function prihvati(val) {
            var xhr1 = new XMLHttpRequest();

            xhr1.onreadystatechange = function() {
        	if( xhr1.readyState == 4 && xhr1.status == 200 ) {
                document.body.innerHTML = "";
            	document.body.innerHTML = xhr1.responseText;
                }
            }
            xhr1.open( "GET", "admin.php?accept=1&id=" + val, true );
            xhr1.send();
            }

        function obrisi(koga, sto) {
            var xhr2 = new XMLHttpRequest();

            xhr2.onreadystatechange = function() {
        	if( xhr2.readyState == 4 && xhr2.status == 200 ) {
                document.body.innerHTML = "";
            	document.body.innerHTML = xhr2.responseText;
                }
            }

        xhr2.open( "GET", "admin.php?delete=" + sto + "&id=" + koga, true );
        xhr2.send();
        }

        function izbrisi(cija,koja) {
            var xhr3 = new XMLHttpRequest();

            xhr3.onreadystatechange = function() {
        	if( xhr3.readyState == 4 && xhr3.status == 200 ) {
                document.body.innerHTML = "";
            	document.body.innerHTML = xhr3.responseText;
                }
            }

        xhr3.open( "GET", "admin.php?brisi=" + cija + "&n=" + koja, true );
        xhr3.send();
        }

        function poredaj_datum() {
             var xhr4 = new XMLHttpRequest();

            xhr4.onreadystatechange = function() {
        	if( xhr4.readyState == 4 && xhr4.status == 200 ) {
                document.body.innerHTML = "";
            	document.body.innerHTML = xhr4.responseText;
                }
            }

            xhr4.open( "GET", "admin.php?sort=1", true );
            xhr4.send();
        }

	</script>

<?php
	if( $_SESSION["user"]!=="admin" ) header("Location: index.php");
	if( isset($_POST["logout"] ) ){
		session_unset();
		session_destroy();
		header("Location: index.php");
	}

    include('baze.php');


     function prikaz() {
        global $broj_zahtjeva;
        global $broj_korisnika;
        global $zahtjevi;
        global $korisnici;
        global $rez_date;
        global $rez_user;

            $show="<div id='prikazzahtjeva' style='visibility:hidden; margin-top: 5%;' >";
            if( $broj_zahtjeva != 0) { 	        // ako nisu prazni zahtjevi
                $show.="<div class='table-responsive' style='width: 90%; position: absolute; left: 5%; border-collapse: separate; border-spacing: 10px 50px;'>";
                  $show.="<table class='table table-hover'>";
                  $show.="<thead><tr><th style='width:25%;'> Username </th>
                                <th style='width:25%;'> Email </th>
                                <th style='width:25%;'> Name2$show </th>
                                <th style='width:25%;'>  </th>";
                  $show.="</tr></thead><tbody>";

                for( $i=0; $i< $broj_zahtjeva;  ++$i ){
                    $show.="<tr>";
     				for( $j=0; $j<3; $j++ ){
                        $show.="<td>".$zahtjevi[$i][$j]."</td>";
                 	   }
                 	$show.="<td><button type='button' class='btn btn-default btn-sm'  onclick='obrisi(".$i.",1)'>
          					<span class='glyphicon glyphicon-trash'></span> Izbriši </button></td>";
                    $show.="<td><button type='button' class='btn btn-default btn-sm'  onclick='prihvati(".$i.")'>
          					<span class='glyphicon glyphicon-ok'></span> Prihvati </button></td>";
                    $show.="</tr>";
                        }
               $show.="</tbody></table></div>";
                }
            $show.="</div>";

            $show.="<div id='prikazkorisnika' style='visibility:hidden; margin-top: 5%;' >";
            if( $broj_korisnika != 0 ) {	//ako nije prazno
                $show.="<div class='table-responsive' style='width: 90%; position: absolute; left: 5%; border-collapse: separate; border-spacing: 10px 50px;'>";
                $show.="<table class='table table-hover'>";
                $show.="<thead><tr><th style='width:25%;'> Username </th>
                                    <th style='width:25%;'> Email </th>
                                    <th style='width:25%;'> Name2$show </th>
                                    <th style='width:25%;'>  </th>";
                $show.="</tr></thead><tbody>";

                for( $i=0; $i<sizeof( $korisnici );  ++$i ){
                    if( $korisnici[$i][0] != "admin" ){
                        $show.="<tr>";
                        for( $j=0; $j<3; $j++ ){
                            $show.="<td>".$korisnici[$i][$j]."</td>";
                        }
                        $show.="<td> <button type='button' class='btn btn-default btn-sm'  onclick='obrisi(".$i.",2)'>
          					<span class='glyphicon glyphicon-trash'></span> Izbriši </button></td>";
                        $show.="</tr>";
                        }}
                $show.="</tbody></table></div>";
                }
                $show.="</div>";

            $show.="<div id='prikazrezervacija' style='visibility:visible; margin-top: 5%;' >";
                if( sizeof($rez_user)+1 > 0) { 	// ako nisu prazne rezervacije
                    $show.="<div class='table-responsive' style='width: 90%; position: absolute; left: 5%; border-collapse: separate; border-spacing: 10px 50px;'>";
                    $show.="<table class='table table-hover'>";
                    $show.="<thead><tr><th style='width:20%;'> Username </th>
                                    <th style='width:20%;' onclick='poredaj_datum()'> Datum </th>
                                    <th style='width:20%;'> Vrijeme </th>
                                    <th style='width:20%;'> Predavaona </th>
                                    <th style='width:20%;'>  </th>";
                    $show.="</tr></thead><tbody>";
                if (isset($_GET["sort"])) {
                    //izvlačenje rezervacije iz baze po datumu
                    $rez_date = get_all_reservations_sort();

                    for( $i=0; $i< sizeof($rez_date);  ++$i ){
                            $show.="<tr>";
                            for( $j=0; $j<4; $j++ ){
                                $show.="<td>".$rez_date[$i][$j]."</td>";
                 	 }
                 	$show.="<td> <button type='button' class='btn btn-default btn-sm' onclick='izbrisi(".$i.",-1)'>
          					<span class='glyphicon glyphicon-trash'></span> Izbriši </button></td>";
                    $show.="</tr>";
                    }  }
                else {
                    for( $i=0; $i<sizeof( $rez_user );  ++$i ){
                        for( $k=0; $k<sizeof( $rez_user[$i] ); ++$k ){
                            $show.="<tr>";
                            $show.="<td>".$korisnici[$i][0]."</td>";
                            for( $j=0; $j<3; $j++ ){
                                $show.="<td>".$rez_user[$i][$k][$j]."</td>";
                                }
                            $show.="<td> <button type='button' class='btn btn-default btn-sm' onclick='izbrisi(".$i.",".$k.")'>
          					<span class='glyphicon glyphicon-trash'></span> Izbriši </button></td>";
          					}
                        $show.="</tr>";
                    } }
                $show.="</tbody></table></div>";
                }
            $show.="</div>";
        return $show; }


    // izvlacenje korisnika iz baze
    $korisnici = get_users ();		// $korisnici[] = [ [us1, em1, nam1, id1], [us2, em2, nam2, id2], ...]
    $broj_korisnika = sizeof( $korisnici ) -1;		// broj_korisnika minus 1 zato što se admin nalazi u korisnicima, a sebe ne broji

    //izvlačenje zahtjeva iz baze
    $zahtjevi = get_requests();		// $zahtjevi[] = [ [us1, em1, nam1, id1], ]
    $broj_zahtjeva = sizeof($zahtjevi);

    //izvlačenje rezervacija iz baze po korisniku
    foreach( $korisnici as $x )
			$rez_user[] = get_all_reservations_by_user ( $x[0] );

    // brojanje rezervacija
    $broj_rezervacija = 0;
	foreach ($rez_user as $x) {
		foreach ($x as $y)
			++$broj_rezervacija;
	}

    if (isset($_GET["accept"])) {
     // prihvati novog usera
        if (move_to_users ($zahtjevi[$_GET["id"]][3])) {
                $to = $zahtjevi[$_GET["id"]][1];
                $subject = "REZERVAPP - Uspješna registracija";
                $message = "Vaša registracija je uspješno završena. \n Možete koristiti naše usluge. \n Rezervapp";
                $headers = "From: REZERVAPP" . "\r\n" .
                            "Reply-To: valentinadumbov@gmail.com" . "\r\n" .
                            "X-Mailer: PHP/" . phpversion();

                mail($to, $subject, $message, $headers);
               // refreshaj stanje
             $korisnici = get_users ();
             $broj_korisnika = sizeof($korisnici)-1;
         }
    }
    if (isset($_GET["delete"])) {
        if ($_GET["delete"] == 1) {
        // izbisi zahtjev
            if (delete_from_requests ($zahtjevi[$_GET["id"]][3])) {
                // refreshaj stanje
                $zahtjevi = get_requests();
                $broj_zahtjeva = sizeof($zahtjevi);
                $zahtjevi = get_requests();
                $broj_zahtjeva = sizeof($zahtjevi);
                }
            }
        else
        if (($_GET["delete"] == 2)) {
        //izbrisi is usera
            if (delete_from_users ($korisnici[$_GET["id"]][3]))
             //izbrisi sve njegove rezervacije
                    if (delete_from_reservations_user($korisnici[$_GET["id"]][0]) ) {
                         //obavijesti mailom
                        $to = $korisnici[$_GET["id"]][1];
                        $subject = "REZERVAPP - Obavijest";
                        $message = "Vaš korisnički račun je izbrisan. \n Za više informacija obratite se na valentinadumbov@gmail.com. \n Rezervapp";
                        $headers = "From: REZERVAPP" . "\r\n" .
                                    "Reply-To: valentinadumbov@gmail.com" . "\r\n" .
                                    "X-Mailer: PHP/" . phpversion();

                        mail($to, $subject, $message, $headers);
                           // refreshaj stanje
                        $korisnici = get_users ();
                        $broj_korisnika = sizeof( $korisnici ) -1;
                        $zahtjevi = get_requests();
                        $broj_zahtjeva = sizeof($zahtjevi);
                }
            }
    }
    if (isset($_GET["brisi"])) {
    //izbrisi rezervaciju
        // ako je bila sortirana lista
        if ($_GET["n"] == -1) {
        echo "bla";
        $rez_date = get_all_reservations_sort();
        $rez = $rez_date[$_GET["brisi"]];
        if (delete_from_reservations ($rez[1], $rez[2], $rez[3])) {
                           // refreshaj stanje
                        $zahtjevi = get_requests();
                        $broj_zahtjeva = sizeof($zahtjevi);
                }

        }
    // ako je bila obicna lista
     else {
        $rez = $rez_user[$_GET["brisi"]][$_GET["n"]];
        if (delete_from_reservations ($rez[0], $rez[1], $rez[2])) {
                           // refreshaj stanje
                        $zahtjevi = get_requests();
                        $broj_zahtjeva = sizeof($zahtjevi);
                }
        }
    }

?>
	<!--
	ADMIN:
	username= admin, password= admin, email=admin@gmail.com, name2show=Administrator
	-->
    <img src="dove.png" style="width: 40%; height: 50%; position: absolute; bottom: 3%; right: 2%;">
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#"> Rezervapp </a>
		</div>
		<div>
			<ul class="nav navbar-nav">
				<li class="active"><a href="admin.php">Home</a></li>
        		<li><a href="calendarAdmin.php"> Kalendar </a></li>
      		</ul>
      		<ul class="nav navbar-nav navbar-right">
        		<li><a href="#"><span class="glyphicon glyphicon-user"></span> Administrator </a></li>
        		<li> <a href="#">
        			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        				<span class="glyphicon glyphicon-log-out" style="color: #5685B5;"> </span>
						<input type="submit" class="btn-link" name="logout" value="Logout">
        			</form>
        			</a> </li>
      		</ul>
    	</div>
  		</div>
	</nav>

	<div id="wrapper" style="position: absolute; right:10%; width: 80%;">

	<div id="header2" style="margin-left: 50%;">
		<a href="#" onclick="prikaz_zahtjeva();" class="btn btn-info"> Zahtjevi <span class="badge"> <?php echo $broj_zahtjeva; ?> </span></a>
		<a href="#" onclick="prikaz_korisnika();" class="btn btn-info"> Korisnici <span class="badge"> <?php echo $broj_korisnika; ?> </span></a>
		<a href="#" onclick="prikaz_rezervacija();" class="btn btn-info"> Rezervacije <span class="badge"> <?php echo $broj_rezervacija; ?> </span></a>
	</div>
	</div>
   <?php
    echo prikaz();
   ?>
	</div>

</body>

</html>

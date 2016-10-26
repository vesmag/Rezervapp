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
    <img src="dove.png" style="width: 40%; height: 50%; position: absolute; bottom: 3%; right: 2%;">

	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="admin.php"> Rezervapp </a>
			</div>
			<div>
				<ul class="nav navbar-nav">
					<li><a href="admin.php"> Početna </a></li>
        			<li class="active"><a href="calendarAdmin.php"> Kalendar </a></li>
      			</ul>
      			<ul class="nav navbar-nav navbar-right">
        			<li> <a href="admin.php"> <span class="glyphicon glyphicon-user"> </span> <?php echo "Admin"; ?> </a> </li>
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
    include('baze.php');

    if( isset($_POST["logout"] ) ){
		session_unset();
		session_destroy();
		header("Location: /~amkarlo/index.php");
	}

	// funkcija koja crta kalendar
	function draw_calendar( $month, $year ){

		// zapocni tablicu
		$calendar = '<table cellpadding="0" cellspacing="0" class="calendar" style="width: 90%; position: absolute; left: 5%; top: 280px;">';

		// zaglavlje tablice
		$headings = array('Ponedjeljak','Utorak','Srijeda','Četvrtak','Petak','Subota','Nedjelja');
		$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

		// postavi varijable
		$running_day = date('N',mktime( 0,0,0,$month,1,$year) )-1;
		$todays_date = date('j');
		$todays_month = date ('n');
		$days_in_month = date('t', mktime( 0,0,0,$month,1,$year) );
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = array();

		// prvi redak
		$calendar.= '<tr class="calendar-row">';

		// ispisi sve prazne dane tjedna do pocetka mjeseca
		for( $x = 0; $x < $running_day; $x++ ) {
			$calendar.= '<td class="calendar-day-np"> </td>';
			$days_in_this_week++;
		}

		// nastavi s danima
		for( $list_day = 1; $list_day <= $days_in_month; $list_day++ ){
			$calendar.= '<td class="calendar-day">';
			// oznaci danasnji datum
            if ($list_day == $todays_date && $_SESSION['mj'] == $todays_month)
        	    $calendar.= '<div class="todays-date" onclick="predavaone('.$list_day.')">'.$list_day.'</div>';
            else {
                if (get_reservations(date('Y-m-d', mktime(0, 0, 0, $_SESSION['mj'], $list_day, $_SESSION['god']))))
                    $calendar.= '<div class="day-number-taken" onclick="predavaone('.$list_day.')">'.$list_day.'</div></td>';
                else
                    $calendar.= '<div class="day-number" onclick="predavaone('.$list_day.')">'.$list_day.'</div></td>';
                }
			if( $running_day == 6 ) {
				$calendar.= '</tr>';

				if( ( $day_counter+1 ) != $days_in_month ) {
					$calendar.= '<tr class="calendar-row">';
				}
				$running_day = -1;
				$days_in_this_week = 0;
			}

			$days_in_this_week++;
			$running_day++;
			$day_counter++;
		}

	 	// zavrsi preostale dane tjedna
		if( $days_in_this_week < 8 ) {
			for( $x = 1; $x <= (8 - $days_in_this_week); $x++ ){
				$calendar.= '<td class="calendar-day-np"> </td>';
			}
		}
		// zadnji red
		$calendar.= '</tr>';

		// kraj tablice
		$calendar.= '</table>';

		// vrati sve sto smo nacrtali
		return $calendar;
	}

// funkcija koja crta tablicu s predavaonama
function draw_schedule(){
    $raspored = '<table cellpadding="0" cellspacing="0" class="calendar" style="width: 90%; position: absolute; left: 5%;>';

    $ucione = get_classrooms();
    $raspored.= '<tr class="calendar-row"><tr class="calendar-row"><td class="calendar-day-head"></td>';
    // prvi redak tablice (ucione)
    for($x = 0; $x < sizeof($ucione); $x++)
        $raspored.='<td class="calendar-day-head">'.$ucione[$x].'</td>';

    $raspored.= '</tr></tr>';

    // napravi datum
    $datum = date('Y-m-d', mktime(0, 0, 0, $_SESSION['mj'], $_SESSION['dan'], $_SESSION['god']));
    //izvuci sve rezervacije za taj datum
    $line = get_reservations ($datum);
     for($x = 8; $x < 21; $x++) {
        // prvi stupac tablice - vrijeme
        $raspored.= '<tr class="calendar-row"><td class="calendar-day"><div class="time-number">'.$x.'</div></td>';
        // ispisi raspored za vrijeme x
        for($y = 0; $y < 6; $y++) {
           // ispisi raspored za predavaonu y
               $ispis = "";
               // pridruzi vrijednost name2show ispisu tamo gdje ima rezervacija
               for($i=0; $i < sizeof($line); ++$i) {
                    $rezervacija = $line[$i];
                    if ($rezervacija[0] == $x && $rezervacija[1] == $ucione[$y])
                            $ispis = $rezervacija[2];
                    }
                 // pri ispisu, slobodni termini se mogu rezervirati, a zauzeti izbrisati
                if ($ispis == "")
                    $raspored.= '<td class="calendar-day" onclick="obradi('.$y.','.$x.',1)"><div class="rezervacije">'.$ispis.'</div></td>';
                else
                    $raspored.= '<td class="calendar-day" ><div class="rezervacije" onclick="obradi('.$y.','.$x.',0)">'.$ispis.'</div></td>';
                }
         // zavrsi redak
        $raspored.= '</tr>';
        }
    // zavrsi tablicu
    $raspored.= '</table>';

    // vrati nacrtano
    return $raspored;
}

$ucione = get_classrooms ();

//ako je odabrao predavaonu i termin, napravi ili izbrisi rezervaciju
if (isset($_GET['r']) && isset($_GET['h']) && isset($_GET['a'])) {
    $pred = $_GET['r'];
    $sat = $_GET['h'];
    $akcija = $_GET['a'];
    $datum = date('Y-m-d', mktime(0, 0, 0, $_SESSION['mj'], $_SESSION['dan'], $_SESSION['god']));
    // ako je akcija=true, onda je kliknuo na rezervaciju
    if ($akcija) {
      $id = rand();
    // while (!(find_id($id))) { $id = rand(); }
      if (insert_into_reservations ($ucione[$pred], $datum, $sat, "admin", $id))
        echo "<div class='container'> Uspješno ste rezervirali termin u predavaoni $ucione[$pred] u $sat h! Želite li rezervirati još termina ovaj dan?";
       }
     // ako je akcija=false, kliknuo je na brisanje
    else
        if (delete_from_reservations ($datum, $sat, $ucione[$pred]))
            echo "<div class='container'> Uspješno ste izbrisali termin u predavaoni $ucione[$pred] u $sat h! Želite li dalje uređivati termine za ovaj dan?";

   // ispisi gumbe za povratak
    echo "<input type='submit' onclick='ispisi(this)' value='Da'>
        <input type='submit' onclick='ispisi(this)' value='Ne' ></div>";
}
else
// ako je odabran dan, nacrtaj tablicu sa predavaonama
    if (isset($_GET['d']) ) {
        // spremi dan iz GET-a u neku varijablu da imamo za kasnije
        $_SESSION['dan'] = $_GET['d'];
        // pozovi php fju koja ispisuje predavaone
        echo draw_schedule();
    }
    else {
        // ako je korisnik kliknuo na da promijeni mjesec
        if (isset($_GET['m']) &&  isset($_GET['g'])) {
        // spremi podatke iz GET-a u varijable
             $_SESSION['mj'] = $_GET['m'];
            $_SESSION['god'] = $_GET['g'];
        // ispisi naslov i kalendar
            echo '<h2 style="position: absolute; left: 47%;">'. $_SESSION["mj"].'. '.$_SESSION["god"].'.</h2>';
        // konkretan poziv fje koja ispisuje kalendar
            echo draw_calendar($_SESSION["mj"], $_SESSION["god"]);
            }
        else {
        // ako smo na pocetku, ispisi trenutni mjesec
            $_SESSION['god'] = date("Y");
            $_SESSION['mj'] = date("n");
            echo '<h2 style="position: absolute; left: 47%;">'.$_SESSION["mj"].'. '.$_SESSION["god"].'.</h2>';
            echo draw_calendar($_SESSION["mj"],$_SESSION["god"]);
            }
        // gumbi za mijenjanje mjeseca
		echo '<div style="position: absolute; left: 47%; top:230px;" > <input type="button" class="btn btn-default btn-sm" onclick="dalje(this,'.$_SESSION["mj"].','.$_SESSION["god"].')" value="Natrag" />'
			 .'<input type="button" class="btn btn-default btn-sm" onclick="dalje(this,'.$_SESSION["mj"].','.$_SESSION["god"].')" value="Naprijed" /> </div>';

	}

	?>

	<script>
	 var dan;
	 var mj;
	 var god;

	// funkcija za hendlanje mijenjanja mjeseca
	function dalje(val, m, g) {

    	var xhr1 = new XMLHttpRequest();

    	xhr1.onreadystatechange = function() {
        	if( xhr1.readyState == 4 && xhr1.status == 200 ) {
           document.body.innerHTML = "";
             document.body.innerHTML = xhr1.responseText;
            }
        }
        // usput postavi i globalne varijable tako da znamo koji je mjesec korisnik gledao nakon sto rezervira
    	if (val.value == 'Natrag') {
        	if (( ((m-1)>0) && (g==2016)) || ( (m>8) && (g==2015) )) {
            	xhr1.open( "GET", "calendarAdmin.php?" + "m=" + (m-1) + "&g=" + g, true );
            	mj = m-1; god = g; }
        	else if ((m-1)<1) {
                xhr1.open( "GET", "calendarAdmin.php?" + "m=" + 12 + "&g=" + (g-1), true );
                mj = 12; god = g-1; }
            else {
                xhr1.open( "GET", "calendarAdmin.php?" + "m=" + m + "&g=" + g, true );
                mj = m; god = g; }
        }
    	else if (val.value == 'Naprijed') {
        	if (( (m<12) && (g==2015) ) || ( (m<7) && (g==2016) )) {
            	xhr1.open( "GET", "calendarAdmin.php?" + "m=" + (m+1) + "&g=" + g, true );
            	mj = m+1;  god = g; }
        	else if ((m+1)>12) {
                xhr1.open( "GET", "calendarAdmin.php?" + "m=" + 1 + "&g=" + (g+1), true );
                mj = 1; god = g+1; }
            else {
                 xhr1.open( "GET", "calendarAdmin.php?" + "m=" + m + "&g=" + g, true );
                 mj = m; god = g; }
        }

    	xhr1.send();
	}

	// funkcija za hendlanje klika odabranog datuma - uzrokuje ispis tablice s predavaonama s GET-om
	function predavaone(val) {
        dan = val;
    	var xhr2 = new XMLHttpRequest();

    	xhr2.onreadystatechange = function() {
        	if( xhr2.readyState == 4 && xhr2.status == 200 ) {
            	document.body.innerHTML = "";
            	document.body.innerHTML = xhr2.responseText;
            }
        }

    	xhr2.open( "GET", "calendarAdmin.php?d=" + val, true );
    	xhr2.send();
	}

// funkcija za hendlanje klika odabrane predavaone - pokrece dio php-a koji sprema rezervaciju u bazu
function obradi(pred, sat, sto) {
    var xhr3 = new XMLHttpRequest();

    xhr3.onreadystatechange = function() {
        if( xhr3.readyState == 4 && xhr3.status == 200 ) {
            document.body.innerHTML = xhr3.responseText;
            }
        }
    if (sto == 1)
        xhr3.open( "GET", "calendarAdmin.php?r=" + pred + "&h=" + sat + "&a=1", true );
    else
        xhr3.open( "GET", "calendarAdmin.php?r=" + pred + "&h=" + sat + "&a=0", true );
    xhr3.send();
}

// funkcija za hendlanje klika na gumb poslije rezerv ili brisanja termina
function ispisi (sto) {

    var xhr4 = new XMLHttpRequest();

    xhr4.onreadystatechange = function() {
        if( xhr4.readyState == 4 && xhr4.status == 200 ) {
            document.body.innerHTML = xhr4.responseText;
            }
        }
     // vratit ce ga na trenutni raspored
    if (sto.value == 'Da')
        xhr4.open( "GET", "calendarAdmin.php?d=" + dan, true );
    else
    // vratit ce ga na trenutni mjesec
        xhr4.open( "GET", "calendarAdmin.php?m=" + mj + "&g=" + god, true );
    xhr4.send();
}


	</script>

</body>

</html>

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
    include('baze.php');
	if( isset( $_SESSION["user"] ) ) header("Location: /welcome.php");

?>

	<img src="dove.png" style="width: 40%; height: 50%; position: absolute; bottom: 3%; right: 2%;">
    <img src="line.png" style="width:60%; height: 20%; position: fixed; top:13%; ">
    <img src="line.png" style="width:60%; height: 20%; position: fixed; top:7%; left: 50%; ">

	<div class="container">
		<h1 style="font-family:Book Antiqua; position:absolute; top:6%;"> <a id="naslov" href="index.php"> REZERVAPP </a> </h1>
    </div>


  	<div class="container" style="position:absolute; top:30%;">

		<form class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="width:50%; position:absolute; left:25%;">
			<div class="form-group">
				<label class="col-sm-2 control-label"> Korisničko ime </label>
				<div class="col-sm-10">
					<input class="form-control" id="focusedInput" type="text" placeholder="username" name="username">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"> Lozinka </label>
				<div class="col-sm-10">
					<input class="form-control" id="focusedInput" type="password" placeholder="password" name="password">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"> Ponovljena ozinka </label>
				<div class="col-sm-10">
					<input class="form-control" id="focusedInput" type="password" placeholder="password" name="ponpassword" onkeyup="provjeralozinke();">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"> Email </label>
				<div class="col-sm-10">
					<input class="form-control" id="focusedInput" type="email" placeholder="e-mail" name="email">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"> Ime za prikaz </label>
				<div class="col-sm-10">
					<input class="form-control" id="focusedInput" type="text" placeholder="ime" name="name2show">
				</div>
			</div>
			<input type="submit" name="regsubmit" value="Pošalji zahtjev" class="btn btn-primary" style="position:absolute; left:50%;">
		</form>
	</div>


	<?php
        function ocisti($data){
            for ($i=0; $i<5; $i++) {
                $data[0] = trim($data[0]);		//makni nepotrebne karaktere kao previse space, tab, newline iz input data
                $data[0] = stripslashes($data[0]);	//makne backslashes \ iz user input data
                $data[0] = htmlspecialchars($data[0]);
                }
               echo "provjereno!";
             return $data;
            }

		if( isset($_POST["regsubmit"]) ) {

			if( $_POST["username"]=="" || $_POST["password"]=="" || $_POST["ponpassword"]=="" || $_POST["email"]=="" || $_POST["name2show"]=="" )
				echo  "<script type='text/javascript'> alert('Niste unijeli sve podatke!'); </script>";
			else {
			$log_data = array ( $_POST["username"], $_POST["password"], $_POST["ponpassword"], $_POST["email"], $_POST["name2show"]);
			$log_data = ocisti($log_data);

            if (find_username ($log_data[0]))
                echo "<script type='text/javascript'> alert('Korisničko ime je zauzeto!'); </script>";
            else
                if (find_email ($log_data[3]))
                    echo "<script type='text/javascript'> alert('Email je već u uporabi!'); </script>";
                else
                    if (find_name2show ($log_data[4]))
                        echo "<script type='text/javascript'> alert('Ime za prikaz je zauzeto!'); </script>";
                    else
                     if ($log_data[1] != $log_data[2])
                              echo "<script type='text/javascript'> alert('Lozinke se ne podudaraju!'); </script>";
                     else {
                        echo "<script> alert('Zahvaljujemo na interesu! O statusu zahtjeva bit ćete obaviješteni na email.'); </script>";

                        $id = rand();
                      //  while (!(find_id($id))) { $id = rand(); }
                        //zahtjev administratoru
                        if (insert_into_requests ($log_data[0], $log_data[1],$log_data[3], $log_data[4], $id)) {
                        //poslati na mail korisniku
                                $to = $log_data[3];
                                $subject = "REZERVAPP - Registracija";
                                $message = "Zahvaljujemo na interesu za aplikaciju Rezervapp! \n O statusu zahtjeva bit ćete obaviješteni na email. \n Rezervapp";
                                $headers = "From: REZERVAPP" . "\r\n" .
                                            "Reply-To: valentinadumbov@gmail.com" . "\r\n" .
                                        "X-Mailer: PHP/" . phpversion();

                                mail($to, $subject, $message, $headers);
                                }
                        else
                            echo "<script> alert('Greska'); </script>";

				echo "<script> window.location.href = 'index.php'; </script> ";
			}
        }
        }
	?>
</body>

</html>

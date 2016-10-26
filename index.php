<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<title> Rezervapp </title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

  	<!-- dodatni stil -->
  	<link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>


<?php
    if (isset( $_SESSION["user"]) && $_SESSION["user"]=="admin" ) header("Location: /~amkarlo/admin.php");
	if( isset( $_SESSION["user"] ) ) header("Location: /~amkarlo/welcome.php");
	include('baze.php');
?>

	<script type="text/javascript">


	</script>

	<img src="dove.png" style="width: 40%; height: 50%; position: absolute; bottom: 3%; right: 2%;">
    <img src="line.png" style="width:60%; height: 20%; position: fixed; top:13%; ">
    <img src="line.png" style="width:60%; height: 20%; position: fixed; top:7%; left: 50%; ">

    <div class="container">

    	<h1 style="font-family:Book Antiqua; position:absolute; top:6%;"> <a id="naslov" href="index.php"> REZERVAPP </a> </h1>

    <ul style="position:absolute; right:2%; top:3%;">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-inline" role="form">
  				<div class="form-group">
					<input type="text" class="form-control" name="username">
  				</div>
  				<div class="form-group">
					<input type="password" class="form-control" name="password">
  				</div>
  				<span class="glyphicon glyphicon-log-in" style="color: #5685B5;"> </span>
				<input type="submit" class="btn-link" name="login" value="Login">
			</form>
        </ul>
    </div>

	<div class="container" style="position:absolute; left:10%; top:45%;">
	    <blockquote style="color: #4A6B7E;">
	   	<p> Dobrodošli na aplikaciju za rezervacije prostorija! </p>
    	<p> Ovdje možete kao neregistrirani korisnik pregledavati rasporede i rezervacije prostorija, </p>
    	<p> te kao registrirani korisnik možete i sami upravljati rezervacijama! </p>
  		<ul>
  			<a href="registr.php"> <span class="glyphicon glyphicon-user"> </span> Registriraj se </a>
  		</ul>
  		<ul>
  			<a href="calendar.php"> <span class="glyphicon glyphicon-calendar"> </span> Kalendar </a>
  		</ul>
  		</blockquote>

	</div>

	<?php

		if( isset($_POST["login"]) ) {

			if( $_POST["username"]=="" || $_POST["password"]=="" )
				echo "<script type='text/javascript'> alert('Niste unijeli sve podatke!'); </script>";

			else {
				if( !find_username ($_POST["username"] ) )
					echo "<script type='text/javascript'> alert('Korisničko ime ne postoji!'); </script>";
				if (find_username ($_POST["username"])){
                    if (check_password ($_POST["username"], $_POST["password"])) {
                        $_SESSION["user"] = $_POST["username"];
                        $_SESSION["name"] = get_name2show($_POST["username"]);
                    }
					else echo "<script type='text/javascript'> alert('Lozinka nije u redu!'); </script>";
				}
				if( $_POST["username"] == "admin" && $_POST["password"] == "admin" ) {
                        $_SESSION["user"] = "admin";
                        $_SESSION["name"] = "admin";
                        header("Location: /~amkarlo/admin.php");  }
				else {
				echo "<script> window.location.href = 'welcome.php'; </script> ";
				}
			}
		}
	?>
</body>

</html>

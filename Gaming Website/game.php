<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom fonts for this template -->
<link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="vendor/devicons/css/devicons.min.css" rel="stylesheet">
<link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/resume.min.css" rel="stylesheet">
		<link rel="stylesheet" href="main.css">
				<link rel="stylesheet" href="node_modules/skeleton-css/css/skeleton.css">



<title>GameZone!</title>
<script>
	function loadpage(){
      var str= getUrlVars()["name"];

      if (!str || str ==""){
        $('.newroom').css('display', 'block');
      }
      else if(str=="guest")
        $('.joinroom').css('display','block');
    	else 
    		$('.gameBoard').css('display','block');
  }

   function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;

    function createLink(){
    	var msg =document.getELementbyID("userHello");
    	var str = msg.split(":");
    	var gameid = str[1].slice(0,5);
    	document.getElementbyID("gameID").innerHTML = gameID;
    }
}
	</script>
</head>
<body id="page-top" onload="loadpage();">


	<?php include 'navbar.php';?>

<div class="container-fluid">

	<div class="row">
		<div class="container-fluid">

			
				<section class="resume-section p-3 p-lg-5 d-flex flex-column" id="tictactoe">
				<h1>Tic - Tac - Toe</h1>
				<div class="newroom">
				<h4>Create a new Game</h4>
				<input type="text" name="name" id="nameNew" placeholder="Enter your name" required>
				<div >
				<h5>Choose</h5>
				<input type="radio" name="XO" id ="chooseX" value="X"> <label for="chooseX"><h5>X</h5></label>
				<input type="radio" name="XO" id ="chooseO" value="O"> <label for="chooseO"><h5>O</h5></label>
			</div>
				<button id="new">New Game</button>
			</div>
				<br><br>
				<div class="joinroom">
				<h4>Join an existing game</h4>
				<input type="text" name="name" id="nameJoin" placeholder="Enter your name" required>
				<!--<input type="text" name="room" id="room" placeholder="Enter Game ID" required>-->
				<button id="join">Join Game</button>
			</div>
			<div class="gameBoard">
				<h2 id="userHello"></h2>
				<h3 id="turn"></h3>()
				<table class="center">
					<tr>
						<td><button class="tile" id="button_00"></button></td>
						<td><button class="tile" id="button_01"></button></td>
						<td><button class="tile" id="button_02"></button></td>
					</tr>
					<tr>
						<td><button class="tile" id="button_10"></button></td>
						<td><button class="tile" id="button_11"></button></td>
						<td><button class="tile" id="button_12"></button></td>
					</tr>
					<tr>
						<td><button class="tile" id="button_20"></button></td>
						<td><button class="tile" id="button_21"></button></td>
						<td><button class="tile" id="button_22"></button></td>
					</tr>
				</table>
			</div>
		

			</section>

			

			</div>
		</div>	
	</div>	

	<div class="container-fluid">
	</br>
	
</br>

		<div class = "invite" style="padding-left: 25%">
		<form class="form-inline mt-2 mt-md-0" method="post" action="sendmail.php" id="inviteform" target="votar">
			<input class="form-control mr-sm-2" name="email" id="emailID" type="text" placeholder="Email-Id" aria-label="Email-Id">
			<textarea type="text" name="gameid" id="gameid" hidden></textarea>
			<h6 id="name"> </h6>
			<button id="invite" class="btn btn-outline-success my-2 my-sm-0" type="submit">Invite</button>
		</form>

		<iframe name="votar" style="display:none;"></iframe>
	</div>
	

<div class="row">
	<img class="img-fluid" src="images/imgadd.jpg">
</div>
</div>
<footer class="footer">
<?php include 'footer.php';?>

</footer>



<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Plugin JavaScript -->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for this template -->
<script src="js/resume.min.js"></script>

<script src="node_modules/jquery/dist/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
		<script src="main.js"></script>
</body>
</html>


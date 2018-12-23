<?php
/*
Benjamin Bowser 11/7 PHP Assignment
*/

session_start();		//create a session

//initialize number of visits session variable
$numVisits = 0;
if (isset($_SESSION['num'])) {
                    $_SESSION['num']++;
                    $numVisits = $_SESSION['num'];
} else {
				  $numVisits = 0;
                  $_SESSION['num'] = 0;
}

//see if the cmd get variable is passed into the program.
$cmd = "";
if (isset($_GET['cmd'])) {
           $cmd =htmlspecialchars($_GET['cmd']);
           if ($cmd != "page1" && $cmd != "page2" && $cmd != "page3") {
                 $cmd = "";
             }
}
//YOU WILL PUT YOUR FORM HANDLING CODE HERE
$user = "";
if (isset($_GET['user'])) {
	$user = htmlspecialchars($_GET['user']);
	$_SESSION['user'] = $user;
	}
?>


<!DOCTYPE html>
<html lang="en-us">
<head>
<script src=
  "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://getbootstrap.com/docs/3.3/examples/starter-template/starter-template.css">
  </head>
  
 <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">PHP Assignment - Bowserbl</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
    </div>
  </div>
</nav>
  
<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <ul class="list-unstyled components">
            <p>Menu</p>
            <li>
                <a href="index.php?cmd=page1">Random Numbers</a>
            </li>
            <li>
                <a href="index.php?cmd=page2">Images</a>
            </li>
            <li>
                <a href="index.php?cmd=page3">Input Form</a>
            </li>
			<li>
				<a href="index.php">Home</a>
			</li>
			<?php if (isset($_SESSION['user'])) {

				echo '<li>Name: ';
				echo $_SESSION['user'];
				echo '</li>';
				echo '<li>Number of visits: ';
				echo $numVisits;
				echo '</li>';
			}?>		
        </ul>
    </nav>
</div>
    
<div class="container">
<?php
	if ($cmd=="page1"){
	for ($x = 0; $x < 100; $x++) {
	$a = rand();
	$color = '#'.substr(md5(mt_rand()), 0, 6);
	echo '<font color="';
	echo $color;
	echo '">';
	echo $a;
	echo '<br>';
}
	?>
</div><!-- /.container -->

<div class="container">
<?php
	}
	else if ($cmd == "page2") {
	$array = [
    1 => "1.jpg",
    2 => "2.jpg",
	3 => "3.jpg",
];	
	$number = rand (1, 3);
	$file = $array[$number];
	echo '<img src="http://ceclnx01.cec.miamioh.edu/~bowserbl/CSE383/cse383-f18/383-1024-bowserbl-1989/php1/img/';
	echo $file;
	echo '">';
  	?> 
	</div><!-- /.container -->
	
<?php
	}
	else if ($cmd == "page3") {
  	?> 
<div class="container">
<form method="get" action="index.php">
<div class="form-group">
<label for="user">User:</label>
<input type="text" name="user" value="" class="form-control" id="user">
</div>
<div class="form-group">
<input type="submit" name="submit" value="Submit" class="form-control">
</div>
</div>

<?php
	} else {
?> 
<div class="container">
		  <p>
			<table class="table">
  <thead>
    <tr>
      <th scope="col">Variable</th>
      <th scope="col">Value</th>
    </tr>
  </thead>
  <tbody>
	  <tr>
	  <td>Remote Address</td>
	  <td><?php echo $_SERVER['REMOTE_ADDR'];?></td>
    </tr>
    <tr>
      <td>User Agent</td>
      <td><?php echo $_SERVER['HTTP_USER_AGENT'];?></td>
    </tr>
    <tr>
      <td>Remote Port</td>
      <td><?php echo $_SERVER['REMOTE_PORT'];?></td>
    </tr>
	<tr>
      <td>Server Software</td>
      <td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
  </tr>
  <tr>
      <td>Request Time</td>
      <td><?php echo $_SERVER['REQUEST_TIME'];?></td>
  </tr>
  </tbody>
</table>
</p>
</div><!-- /.container -->

<?php 

}?>

</body>
</html>

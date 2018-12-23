<!DOCTYPE html>
      <html lang="en">
      <head>
	  <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content=
      "width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <script src=
  "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src=
  "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">
  </script>
</head>
<body>
<div class="container-fluid">
<h1>School Info System</h1>
<div id="content">
<h2>Please authenticate</h2>

<?php
$dbUser = "[redacted]";
$dbpassword = "[redacted]";
$mysqli = mysqli_connect("[redacted]", $dbUser, $dbpassword,"[redacted]");
if (mysqli_connect_errno($mysqli)) {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
      	echo "Failed to connect to MySQL: " . mysqli_connect_error();
          	die;
}

function loginHandler($username, $password) {
	global $mysqli;
	$sqlState = "SELECT password from users where user =?";
	if (!$statement = $mysqli->prepare($sqlState)) {
		// If an error happens
	}
	if (!$statement->bind_param("s", $username)) {
		// Binding failure
	}
	if(!$statement->execute()) {
		// Executable statements
	}
	
	$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
	$statement->bind_result($hashedPassword);
	while ($statement->fetch()) {
		if (password_verify($password, $hashedPassword)) {
			$_SESSION['user'] = $username;
		}
		else {}
	}
	$statement->close();
}

function writeLogin() {
	echo '<div id="formArea">';
	echo '<form method=\'post\' class="form-inline">';
	echo '<label for="user">user</label><input type="text" name="user" class="form-control mx-sm-3">';
	echo '<label for="password">Password</password><input type="password" name="password">';
	echo '<input type="submit">';
	echo '</form>';
	echo '</div>';
}
?>
</div>

<div id="tableData">
<script>
function clear() {
    document.getElementById("content").innerHTML = "";
	document.getElementById("formArea").innerHTML = "";
	document.getElementById("formArea").innerHTML = "";
}
</script>
<?php
if (!isset($_SESSION['user'])) {
	writeLogin();
}
	if(isset($_POST["user"])) {
		   $textUser = $_POST["user"];
		   $textPass = $_POST["password"];
		   loginHandler($textUser, $textPass);
	}

if (isset($_SESSION['user'])) {
$textUser = $_SESSION['user'];
echo "<script> clear(); </script>";
echo '<a href=\'?cmd=logout\'>Logout</a>';
echo '<div>';
echo '<form method=\'post\' class="form-inline">';
echo '<label for="amount">Filter by min salary</label><input type="text" name="amount" value="0" class="form-control mx-sm-3">';
echo '<input type="submit" name="filterSubmit" value="Filter">';
echo '</form>';
echo '</div>';
echo '<table class=\'table\'>';
echo '<thead>';
echo '<tr>';
echo '<th>School Name</th><th>Num Undergrads</th><th>Median Salary</th></tr></thead>';

$res = mysqli_query($mysqli, "SELECT SchoolName, url, NumUndergrads, Median10YearEarnings from SchoolData order by SchoolName");
if (!$res) {
  echo "error on sql - $mysqli->error";
} else {
  while($row = mysqli_fetch_assoc($res)) {
	 echo '<tr><td><a href=\'http://';
	 echo $row['url'];
	 echo '\'>';
	 echo $row['SchoolName'];
	 echo '</a></td><td>';
	 echo $row['NumUndergrads'];
	 echo '</td><td>';
	 echo $row['Median10YearEarnings'];
	 echo '</td></tr>';
  }
  echo '</table>';
}
if(isset($_POST["filterSubmit"])) {
$filterAmount = $_POST["amount"];
$res = mysqli_query($mysqli, "SELECT SchoolName, url, NumUndergrads, Median10YearEarnings from SchoolData WHERE Median10YearEarnings > filterAmount");
if (!$res) {
  echo "error on sql - $mysqli->error";
} else {
  while($row = mysqli_fetch_assoc($res)) {
	 echo '<tr><td><a href=\'http://';
	 echo $row['url'];
	 echo '\'>';
	 echo $row['SchoolName'];
	 echo '</a></td><td>';
	 echo $row['NumUndergrads'];
	 echo '</td><td>';
	 echo $row['Median10YearEarnings'];
	 echo '</td></tr>';
  }
  echo '</table>';
}
} 
}
?>
</div>
</div>
</body>
</html>
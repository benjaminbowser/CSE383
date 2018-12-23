<?php
$dbUser = "[redacted]";
$dbpassword = "[redacted]";
$mysqli = mysqli_connect("[redacted]", $dbUser, $dbpassword,"[redacted]");
if (mysqli_connect_errno($mysqli)) {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
      	echo "Failed to connect to MySQL: " . mysqli_connect_error();
          	die;
}

function getData($a) {
  $ret = array("data"=>strlen($a));
  return $ret;
}

/*
   campbest
   restExample.php
   cse383
 */

require_once("model-example.php");

//returns data as json
function retJson($data) {
  header('content-type: application/json');
  print json_encode($data);
  exit;
}

//get request method into $path variable
$method = strtolower($_SERVER['REQUEST_METHOD']);
if (isset($_SERVER['PATH_INFO']))
$path  = $_SERVER['PATH_INFO'];
else $path = "";

//path comes in as /a/b/c - split it apart and make sure it passes basic checks

$pathParts = explode("/",$path);
if (count($pathParts) <2) {
  $ret = array('status'=>'FAIL','msg'=>'Invalid URL');
  retJson($ret);
}
if ($pathParts[1] !== "v1") {
  $ret = array('status'=>'FAIL','msg'=>'Invalid url or version');
  retJson($ret);
}


//get json data if any
$jsonData =array();
try {
  $rawData = file_get_contents("php://input");
  $jsonData = json_decode($rawData,true);
  if ($rawData !== "" && $jsonData==NULL) {
    $ret=array("status"=>"FAIL","msg"=>"invalid json");
    retJson($ret);
  }
} catch (Exception $e) {
};

//var_dump($pathParts);
//var_dump($method);


//look for url /v1/data
if ($method==="get" && count($pathParts) ==  3 && $pathParts[2] === "data") {
  $ret = array('status'=>'OK','msg'=>'','data' => "Hello World");
  retJson($ret);
}

//look for url /v1/keys
if ($method==="get" && count($pathParts) ==  3 && $pathParts[2] === "keys") {
	$res = mysqli_query($mysqli, "SELECT keyName from KeyValue");
  		$row = mysqli_fetch_assoc($res);
		$data = array();
		while($row = mysqli_fetch_assoc($res)) {
		$data[] = $row;
 }
  $ret = array('status'=>'OK','keys' => $data);
  retJson($ret);
}
//look for get with data as part of the path - /v1/keys/<DATA>
else if ($method=="get" && count($pathParts) ==  4 && $pathParts[2] === "keys") {
  $res = mysqli_query($mysqli, "SELECT value from KeyValue where keyName = '$pathParts[3]'");
  		$row = mysqli_fetch_assoc($res);
 
  $ret = array('status'=>'OK','data22' => $row);
  
  retJson($ret);
}

//look for post with json data - expects variable a to be present
if ($method==="post" && count($pathParts) ==  3 && $pathParts[2] === "keys") {
  if (!isset($jsonData['keyName'])) 
  {
    $ret = array('status'=>'FAIL','msg'=>'json in a not present');
    retJson($ret);
  }
  $key = $jsonData['keyName'];
  $value = $jsonData['value'];
  $del = mysqli_query($mysqli, "DELETE from KeyValue where keyName = '$key'");
  
  if ($res = mysqli_prepare($mysqli, "INSERT into KeyValue (keyName, value) VALUES (?, ?)")) {
	  mysqli_stmt_bind_param($res, "ss", $key, $value);
	  mysqli_stmt_execute($res);
	  $reply = array("status" => "OK", 'data' => $key);
	  retJSON($reply);
  }
  
  $ret = array('status'=>'OK','data' => $value);
  retJson($ret);
}

else {
  $ret = array('status'=>'FAIL','msg'=>'Invalid URL');
  retJson($ret);
}

?>


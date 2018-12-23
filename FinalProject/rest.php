<?php

$dbUser = "[redacted]";
$dbpassword = "[redacted]";
$mysqli = mysqli_connect("[redacted]", $dbUser, $dbpassword,"[redacted]");
if (mysqli_connect_errno($mysqli)) {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
      	echo "Failed to connect to MySQL: " . mysqli_connect_error();
          	die;
}

require_once("data.php");

// returns data as json
function retJson($data) {
  header('content-type: application/json');
  print json_encode($data);
  exit;
}

// get request method into $path variable
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

// Code to get it started is above ^^^^^^^^^^^^^^


// Look for url /v1/user. Post:
// Json in: User & Password
// Json out: Status: Ok/Fail, token is a string
if ($method==="post" && count($pathParts) ==  3 && $pathParts[2] === "user") {
	if (!isset($jsonData['user']) || !isset($jsonData['password'])) {
		$ret = array('status'=>'FAIL','msg'=>'');
		retJson($ret);
	}
	$user = $jsonData['user'];
	$password = $jsonData['password'];
	$pwd = checkPassword($mysqli, $user);
	if (!verifyUser($mysqli, $user)){
		$ret = array('status'=>'FAIL','msg'=>'');
		retJson($ret);
	}
	else if (password_verify($password, $pwd)){
		$token = getToken($mysqli, $user);
		$ret = array('status'=>'OK','msg'=>'','data' => $token);
		retJson($ret);
	}
	else {
		$ret = array('status'=>'FAIL','msg'=>'');
		retJson($ret);

	}
}

// Look for url /v1/items. Get:
// No Json In, Json Out: Status, message, items[] (pk and item)
if ($method==="get" && count($pathParts) ==  3 && $pathParts[2] === "items") {
	
  	$ret = array('status'=>'OK','msg'=>'','items' => getItems($mysqli));
  	retJson($ret);
}

//look for get with data as part of the path - /v1/items/<DATA> (token)
// JSON reply: OK, FAIL, AUTHFAIL, msg:text, items[] pk, item, timestamp
else if ($method=="get" && count($pathParts) ==  4 && $pathParts[2] === "items") {
	$token = $pathParts[3];
	$user = getUser($mysqli, $pathParts[3]);
	if (!verifyUser($mysqli, $user)){
		$ret = array('status'=>'FAIL','msg'=>'');
		retJson($ret);
	}
	$userPK = getUserPk($mysqli, $user);
	$items = getUserItems($mysqli, $userPK);
	$ret = array('status'=>'OK','msg'=> '', 'items' =>$items);
	retJson($ret);
}

// Look for url /v1/itemsSummary/<DATA> (token)
// Json in: None, Out: Status, Msg, Items[] item, count
else if ($method=="get" && count($pathParts) ==  4 && $pathParts[2] === "itemsSummary") {
	$token = $pathParts[3];
	$user = getUser($mysqli, $token);
	if (!verifyUser($mysqli, $user)){
		$ret = array('status'=>'FAIL','msg'=>'');
		retJson($ret);

	}
	$userPK = getUserPk($mysqli, $user);
  	$items = getSummaryOfItems($mysqli, $userPK, true);
 	$ret = array('status'=>'OK','data' => $items);
  	retJson($ret);
}

// Look for URL /v1/items for POST input
// JSON in: String token, itemFK <key>
// JSON out: Status OK, Fail, AuthFail, msg text
if ($method==="post" && count($pathParts) ==  3 && $pathParts[2] === "items") {
  if (!isset($jsonData['token']) && !isset($jsonData['itemFK'])) 
  {
    $ret = array('status'=>'FAIL','msg'=>'json in a not present');
    retJson($ret);
  }
  $token = $jsonData['token'];
  $itemFK = $jsonData['itemFK'];
  $user = getUser($mysqli, $token);
  // $userPK = getUserPk($mysqli, $user);
  if (verifyUser($mysqli, $user) == false){
	  $ret = array('status'=>'FAIL','msg'=>'');
	  retJson($ret);
  }
  $userPK = getUserPk($mysqli, $user);
  if (verifyItemFk($mysqli, $userPK, $itemFK) == false){
  	  $ret = array('status'=>'FAIL','msg'=>'');
 	  retJson($ret);
  }
  updateItemsConsumed($mysqli, $itemFK, $userPK);
  $ret = array('status'=>'OK','msg'=>'');
  retJson($ret);
}


else {
  $ret = array('status'=>'FAIL','msg'=>'Invalid URL');
  retJson($ret);
}

?>




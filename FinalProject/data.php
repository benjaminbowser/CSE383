<?php

// Gets the password for a specified user
function checkPassword($mysqli, $user){
	$stmt = $mysqli->prepare("select password from users where user =?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($pwd);
	$stmt->fetch();
	return $pwd;
}

// Checks to make sure the user is valid in the database
function verifyUser($mysqli, $user){
	$stmt = $mysqli->prepare("select user from users where user =?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows === 0){
		return false; 
	}
	return true;
}

// Get the token for the user
function getToken($mysqli, $user){
	$stmt = $mysqli->prepare("select token from tokens where user =?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($token);
	$stmt->fetch();
	return $token;
}

// Gets the pk and item from item list in the database
function getItems($mysqli){
	$sql = "select pk, item from diaryItems order by item asc";
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0){
		while ($row = $result->fetch_assoc()){
			$items = array("pk"=>$row['pk'], "item"=>$row['item']);
			$masterData[] = $items;
			$items = array();
		}
	}
	return $masterData;

}

function getUser($mysqli, $token){
	$stmt = $mysqli->prepare("select user from tokens where token =?");
	$stmt->bind_param("s", $token);
	$stmt->execute();
	$stmt->store_result();
	//if ($stmt->num_rows === 0) {
	//	$user = "blank";
	//} else {
	$stmt->bind_result($user);
	$stmt->fetch();
	
	return $user;
}

function getUserItems($mysqli, $userPK){
	// $user = "test";
	$stmt = $mysqli->prepare("select diaryItems.item, timestamp from diaryItems left join diary on diaryItems.pk = diary.itemFK where userFK =? order by timestamp desc");
	
	$stmt->bind_param("s", $userPK);
	$stmt->execute();
	$result = $stmt->get_result();
	$masterItems = array();
	while ($row = $result->fetch_assoc()){
		$userItems = array("item"=>$row['item'], "timestamp"=>$row['timestamp']);
		$masterItems[] = $userItems;
		$userItems = array();
	}
	return $masterItems;
}

function getSummaryOfItems($mysqli, $userPK, $out){
	$stmt = $mysqli->prepare("select diaryItems.item, count(timestamp) as count from diaryItems left join diary on diaryItems.pk=diary.itemFK where userFK=? group by diaryItems.item");
	$stmt->bind_param("s", $userPK);
	$stmt->execute();
	$result = $stmt->get_result();
	$masterItems = array();
	
	while ($row = $result->fetch_assoc()){
		$userItems = array("item"=>$row['item'], "count"=>$row['count']);
		// $userItems = array($row["token"]);
		$masterItems[] = $userItems;
		$userItems = array();
	}
	return $masterItems;

}

// Gets the userpk from a specified user
function getUserPk($mysqli, $user){
	$stmt = $mysqli->prepare("select pk from users where user =?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($userPK);
	$stmt->fetch();
	return $userPK;

}

// Checks to see if itemFK is valid for a user
function verifyItemFk($mysqli, $userFK, $itemFK){
	$stmt = $mysqli->prepare("select item from diaryItems where pk=?");
	$stmt->bind_param("s", $itemFK);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows === 0){
		return false;
	}
	return true;

}

// Updates the items in the database
function updateItemsConsumed($mysqli, $itemFK, $userPK){
	$stmt = $mysqli->prepare("insert into diary (userFK, itemFK) values (?, ?)");
	$stmt->bind_param("ss", $userPK, $itemFK);
	$stmt->execute();

}

?>

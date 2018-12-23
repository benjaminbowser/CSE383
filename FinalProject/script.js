var tokenAuth;
$(document).ready(function(){
	alert("Final Project for CSE383 - Web Application Programming for Benjamin Bowser & Alec Bird in Fall 2018. To test, login with user: test password: test");
	getButtons();
	// getData();   
	// getItemsData();
	
	$("#form-sub").submit(function(e){
		e.preventDefault();
		var obj = {};
		obj.user =  $("#user").val();
		obj.password = $("#password").val();
		getToken(obj);
	});

	
});

// Get token for specific user
function getToken(obj){
	$.ajax({
		url: "https://benjaminbowser.com/projects/CSE383FinalBirdBowser/rest.php/v1/user",
		type: "POST", 
		dataType: "JSON",
		contentType: "application/json",
		data: JSON.stringify(obj),
		success: function (text){
			var str = JSON.stringify(text);
			var val = JSON.parse(str);
			if (val.status == "OK") {
			$(".container-fluid").hide();
			$(".record").show();
			tokenAuth = val.data;
			getData();
			getItemsData();
			// alert(tokenAuth);

		}
		//else {
		//	alert( "Invalid username/password combination" );
		//}
		},
		
error: function( xhr ) {
	      alert( "Invalid username/password combination" );
}
	});
}

// Print button dynamically based on items
function getButtons(){
	$.ajax({
		url: "https://benjaminbowser.com/projects/CSE383FinalBirdBowser/rest.php/v1/items",
		type: "GET",
		dataType : "JSON",
		success: function (text){
			var stat = ""; 
			var str = JSON.stringify(text);
			var obj = JSON.parse(str);
			// $("#counts").html(obj.data[0].item);
			
			var buttons = "";
						
			for (var j = 0; j < obj.items.length; j++) {
				buttons += "<button class='itemButton' pk= " + obj.items[j].pk + " onclick='addRecord(this)'>" + obj.items[j].item + "</button>";
			}
			
			$("#buttonArea").html(buttons);
		
		},
		error: function( xhr ) {
	      		alert( "The server has thrown an error. Please check console log for details!" );
		}
	});
}

// Clicks button and increment item
function addRecord(thisButton){
	var itemPK = $(thisButton).attr('pk');
	// alert(itemPK);
	var itemObj = {};
	itemObj.token = tokenAuth;
	itemObj.itemFK = itemPK;
	updateItems(itemObj);
}

// Prints item summary table of item and its count
function getData(){
	$.ajax({
		url: "https://benjaminbowser.com/projects/CSE383FinalBirdBowser/rest.php/v1/itemsSummary/" + tokenAuth,
		type: "GET",
		dataType : "JSON",
		success: function (text){
			var stat = ""; 
			var str = JSON.stringify(text);
			var obj = JSON.parse(str);
			// $("#counts").html(obj.data[0].item);
			
			stat = "<table class='table'><tr><th>Item</th><th>Count</th></tr>";
			for (var i = 0; i < obj.data.length; i++){
				stat += "<tr><td>" + obj.data[i].item + "</td><td>" + obj.data[i].count + "</td></tr>";
			}
			stat += "</table>";
			$("#counts").html(stat);
			
		
		
		},
		error: function( xhr ) {
	      		alert( "The server has thrown an error. Please check console log for details!" );
		}
	});
}

// Prints item table of item and its timestamp
function getItemsData(){
	const tokenUrl = "https://benjaminbowser.com/projects/CSE383FinalBirdBowser/rest.php/v1/items/" + tokenAuth;
	// alert(tokenUrl)
	$.ajax({
		url: tokenUrl,
		type: "GET",
		dataType : "JSON",
		success: function (text){
			var stat = ""; 
			var str = JSON.stringify(text);
			var obj = JSON.parse(str);
			// $("#timestamp").html(str);
			
			// var buttonLabelPk = [];
			
			stat = "<table class='table'><tr><th>Item</th><th>Timestamp</th></tr>";
			for (var i = 0; i < 30; i++){
				stat += "<tr><td>" + obj.items[i].item + "</td><td>" + obj.items[i].timestamp + "</td></tr>";
			}
			stat += "</table>";
			$("#timestamp").html(stat);
			
		
		
		},
		error: function( xhr ) {
	      		alert( "The server has thrown an error. Please check console log for details!" );
		}
	});
}

// Updates the tables by incrementing items in tables
function updateItems(itemObj){

	$.ajax({
		url: "https://benjaminbowser.com/projects/CSE383FinalBirdBowser/rest.php/v1/items",
		type: "POST",
		data: JSON.stringify(itemObj),
		dataType: "JSON",
		success: function (text){
			getData();
			getItemsData();
			// alert("success");
			
		},
		error: function( xhr ) {
	      		alert( "The server has thrown an error. Please check console log for details!" );
		}
	});
}



<!--Begin script-->
$(document).ready(function(){
			<!-- Call each function -->
			$("div#table").hide();
			$("#newForm").hide();
			$("#reset").hide();
			$("#user").change(verifyUser);
			$("#affiliation").change(verifyAffiliation);
			$("#comment").change(verifyComment);
			$("#car").change(verifyCar);
		    $("#color").change(verifyColor);

			<!-- Verify all fields are valid before allowing a submission-->
			$("form").submit(function(e){
				$("div#table").hide();
				e.preventDefault();
				if (verifyUser() == false || verifyAffiliation() == false ||
					verifyComment() == false || verifyCar() == false || verifyColor() == false){
						$("#error").html("This submssion has errors.");
						return false;
				}
				else {
					submitAjax();
					return true;
				}
			});
		});
<!-- Changes the data fields all back to default -->
function resetFormData() {
  $("#table").hide();
  $("#reset").hide();
  $("#user").val("");
  $("#comment").val("");
  $("#affiliation").val("");
  $("#car").val("");
  $("#color").val("");
  $("#error").html("Please complete fields").show();
  $("form").show();
}

<!-- Content from instructions for getting data -->
function submitAjax() {
  var dataFromForm = {};
  dataFromForm.user = $("#user").val();
  dataFromForm.affiliation = $("#affiliation").val();
  dataFromForm.comment = $("#comment").val();
  dataFromForm.car = $("#car").val();
  dataFromForm.color = $("#color").val();
  submitCall(dataFromForm, 0);
}

<!-- Send AJAX request -->
function submitCall(dataFromForm, errors) {
  if (errors <= 3) {
    $.ajax({
      type: "POST",
      url: "/~campbest/cse383/forms1/form-ajax.php",
      contentType: 'application/json',
      data: JSON.stringify(dataFromForm),
      success: function(result) {
        $("#error").hide();
        writeTable(result);
      },
      error: function() {
        $("#error").html("Submission did not send, trying again.");
        errors++;
        submitCall(dataFromForm, errors);
      },
    });
  }
  else {
    $("#error").html("Too many attempts. Please refresh.");
    $("form").hide();
    $("#reset").show();
  }
}

<!-- Write output to the table -->
function writeTable(result) {
  if (result.status == "OK") {
    $("form").hide();
    $(result.data).each(function() {
      var d = "<tr>";
      d += "<td>" + this.user + "</td>";
      d += "<td>" + this.affiliation + "</td>";
      d += "<td>" + this.comment + "</td>";
      d += "<td>" + this.car + "</td>";
      d += "<td>" + this.color + "</td>";
      d += "<td>" + this.timestamp + "</td>";
      $("#tableData").append(d);
    })
    $("#table").show();
    $("#reset").show();
  }
}
		<!-- Verify the user field-->
		function verifyUser(){
			var user = document.getElementById("user");
			var userVal = user.value;
			if (userVal.length < 3 || userVal === ""){
				$("#userError").html("User must be longer than 3 characters"); 
				return false;
			}
			else {
				$("#userError").html("Entry Valid");
				return true;
			}
		}

		<!-- Verify the affiliation field-->
		function verifyAffiliation(){
			if ($("#affiliation").val() === ""){
				$("#affiliationError").html("Must select an affiliation");
				return false;
			}
			else {
				$("#affiliationError").html("Entry Valid");
				return true;
			}
		}
		
		<!-- Verify the comment field-->
		function verifyComment(){
			var delim = /[<>]/g;
			var commentId = document.getElementById("comment");
			var commentVal = commentId.value;
			var wordCount = commentVal.split(" ").length - 1;
			var check = delim.test(commentVal);
			if (check == true){
				$("#commentError").html("HTML tags are not permitted.");
				return false;
			}
			else if(commentVal == "" || wordCount < 2){
				$("#commentError").html("Comment must be longer than 3 words");
				return false;
			}
			else {
				$("#commentError").html("Entry Valid");
				return true;
			}
		}

		<!-- Verify the car field-->
		function verifyCar(){
			var carId = document.getElementById("car");
			var carVal = carId.value;
			if (carVal === ""){
				$("#carError").html("Must enter a car");
				return false;
			}
			else {
				$("#carError").html("Entry Valid");
				return true;
			}
		}
		
		<!-- Verify the color field-->
		function verifyColor(){
			if ($("#color").val() === ""){
				$("#colorError").html("Must select a color");
				return false;
			}
			else {
				$("#colorError").html("Entry Valid");
				return true;
			}
		}
		
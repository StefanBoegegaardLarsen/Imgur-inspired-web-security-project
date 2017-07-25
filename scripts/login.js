var url = window.location.href;
var urlSplit = url.split("post.php");
var urlIndex = urlSplit[0]+"index.php";
var currentId = url.split("id=");
var baseUrl = currentId[0];
var authentication;

$("#btnSubmitLogin").click(function(){
	$.ajax({
		cache: false,
		type: 'post',
		dataType: 'json',
		data: ({
			'name': $('#userLoginName').val(),
			'pass': $('#userLoginPass').val()
		}),
		url: 'loginApi.php',
		success: function (data) {
			if (data.status == "loggedIn") {
				$(".input, #btnSubmitLogin, #btnCreateUser").show();
				// $("#login-container").append("Hello "+data.username);
				canPost();
			} else if (data.status == "timeout"){
				$("#status-msg").html("Your account has been suspended. Please try again in 5 minutes.");
				$("#status-msg").show();
			} else if (data.status == "wrongCredentials") {
				$("#status-msg").html("Wrong username or password");
				$("#status-msg").show();
				setTimeout(function(){

					$.ajax({
						cache: false,
						type: 'post',
						dataType: 'json',
						data: ({
							'name': $('#userLoginName').val()
						}),
						url: 'unsuspend.php',
						success: function (data) {
							if (data.status == "endTimeout") {
							} else {
							}
						},
						error: function() {
						}
					});

				}, 3000)
			} else if (data.status == "error") {
			} else if (data.status == "suspended") {
				$("#status-msg").html("Your account has been suspended. Please try again in 5 minutes.");
				$("#status-msg").show();
			} else if (data.status == "unsuspended") {
			} else {
			}
		},
		error: function () {
		}
	});
});

// 1. user fills in username, email and phone number

// 2. user clicks btnCreateUser
// 	2.1. check if username exists
//	2.2. if username is available, send sms authentication
//	2.3. if username does exist, send message to user
// 	2.4. input and button for authentication appears

// 3. user inputs sms code
// 	3.1. if code matches, user is logged in
// 	3.2. if code doesn't match, send message to user and let them try again

$("#btnCreateUser").click(function(){
	$.ajax({
		cache: false,
		type: 'post',
		dataType: 'json',
		data: ({
			'name': $('#userLoginName').val(),
			'pass': $('#userLoginPass').val(),
			'phone': $('#phone').val()
		}),
		url: 'doesUserExist.php',
		success: function (data) {
			if (data.status == "userExists") {
				$(".input-username").append("<p class='username-taken'>Username is taken</p>");
			} else if (data.status == "availableUsername") {

				$("#login fieldset").append('<div class="input input-verification">\
					<label for="verification"></label>\
					<input name="verification" type="text" id="verification" placeholder="Authentication code" required />\
					</div>\
					<button id="btnVerify" type="button" class="submit"><i class="fa fa-long-arrow-right"></i>Verify</button>\
					');

				$(".input-username, .input-password, .input-phone, #btnCreateUser, #btnSubmitLogin, #btnLogOut").hide();

				$.ajax({
					cache: false,
					type: 'post',
					dataType: 'json',
					data: ({
						'name': $('#userLoginName').val(),
						'pass': $('#userLoginPass').val(),
						'phone': $('#phone').val()
					}),
					url: 'authentication.php',
					success: function (data) {
						if (data.status == "smsSent") {
							$("#status-msg").html("An sms with a verification code has been sent to your phone.");
							$("#status-msg").show();
							authentication = data.authentication;
						} else if (data.status == "smsNotSent") {
							$("#status-msg").html("There was a problem with sending the SMS, please refresh the page and try again.");
							$("#status-msg").show();
						}
					},
					error: function () {
					}
				});
			} else {
			}
		},
		error: function() {
		}
	});
});

// $("#login-container").on("click", "#btnVerify", function(){
// 	window.location.href = (uploadUrl);
// });

$("fieldset").on("click", "#btnVerify", function(){
	// 1. get random 4 digits from authentication.php
	// 2. compare with .val()
	// 3. if they are the same, go to createUser.php

	var sVerification = $("#verification").val();

	if(sVerification == authentication) {

		$.ajax({
			cache: false,
			type: 'post',
			dataType: 'json',
			data: ({
				'name': $('#userLoginName').val(),
				'pass': $('#userLoginPass').val()
			}),
			url: 'createUser.php',
			success: function (data) {
				if (data.status == "userExists") {
					$(".input-username").append("<p class='username-taken'>Username is taken</p>");
				} else if (data.status == "userCreated") {
					$("#btnLogOut").show();
					$("#btnVerify, #verification").hide();
					$("#login-container").append("<button id='btnCreatePost'>Create Post</button>");
					$("#status-msg").html("Your account has been created. Please log in to start using your account");
					$("#status-msg").show();
				} else {
				}
			},
			error: function() {
			}
		});
	} else {
	}
});

$("#btnLogOut").click(function(){
	$.ajax({
		cache: false,
		type: 'post',
		dataType: 'json',
		data: ({
		}),
		url: 'sessionCheck.php',
		success: function (data) {
			if (data.status == "sessionIsSet") {
				$.ajax({
					cache: false,
					type: 'post',
					dataType: 'json',
					data: ({
					}),
					url: 'logoutApi.php',
					success: function (data) {
						if (data.status == "sessionKilled") {
							// $(".input, #btnSubmitLogin, #btnCreateUser").hide();
							$(".comments-container").empty();
							window.location.replace("index.php");
						}
					},
					error: function () {
					}
				});
			} else {
			}
		},
		error: function () {
		}
	});
});



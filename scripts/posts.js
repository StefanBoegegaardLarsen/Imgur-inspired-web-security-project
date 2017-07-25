var url = window.location.href;
var currentId = url.split("id=");
var keyPressed;
var baseUrl = currentId[0];

var loggedIn = false;

function canPost(){
	$.ajax({
		cache: false,
		type: 'post',
		dataType: 'json',
		data: ({
		}),
		url: 'sessionCheck.php',
		success: function (data) {
			if (data.status == "sessionIsSet") {
				$("#login-container").append("<button id='btnCreatePost'>Create Post</button>");
				$(".input, #btnSubmitLogin, #btnCreateUser").hide();
			} else if (data.status == "notSet") {
			} else {
			}
		},
		error: function () {
		}
	});
}

$(window).load(function(){
	canPost();
});

$("#login-container").on("click", "#btnCreatePost", function(){
	window.location.href = ("upload.php");
});

$(".comments-container").on("click", ".btnDeleteComment", function(){
	var commentId = $(this).attr("id");
	var csrf_token_delete = $(".csrf_token_delete").val();
	// console.log(commentId+" "+csrf_token_delete);
	deleteComment(commentId, csrf_token_delete);
});

function leftArrowPressed() {
	keyPressed = "left";

	$.ajax({
		cache: false,
		type: 'post',
		dataType: 'json',
		data: ({
			'currentId': currentId[1],
			'keyPressed': keyPressed
		}),
		url: 'nextOrPrevious.php',
		success: function (data) {
			if (data.status == "ok") {
				previousId = data.id;
				var previousUrl = baseUrl+"id="+previousId;
				window.location.replace(baseUrl+"id="+previousId);
			} else {
			}
		},
		error: function () {
		}
	});
}
function deleteComment(commentId, csrf_token_delete) {
	$.ajax({
		cache: false,
		type: 'post',
		dataType: 'json',
		data: ({
			'commentId': commentId,
			'csrf_token_delete' : csrf_token_delete
		}),
		url: 'deleteComment.php',
		success: function (data) {
			if (data.status == "ok") {
				location.reload();
			} else if(data.status == "securityIssue"){

				console.log("something went wrong");
			}
		},
		error: function () {

		}
	});
}

function rightArrowPressed() {
	keyPressed = "right";

	$.ajax({
		cache: false,
		type: 'post',
		dataType: 'json',
		data: ({
			'currentId': currentId[1],
			'keyPressed': keyPressed
		}),
		url: 'nextOrPrevious.php',
		success: function (data) {
			if (data.status == "ok") {
				nextId = data.id;
				var nextUrl = baseUrl+"id="+nextId;
				window.location.replace(baseUrl+"id="+nextId);
			} else {
			}
		},
		error: function () {
		}
	});
}

document.onkeydown = function(event) {
	event = event || window.event;
	switch (event.keyCode) {
		case 37:
		leftArrowPressed();
		break;
		case 39:
		rightArrowPressed();
		break;
	}
}

$(".btn-left").click(function(){
	leftArrowPressed();
});

$(".btn-right").click(function(){
	rightArrowPressed();
});

$(".btn-add-comment").click(function(){
	var comment = $(".txt-post-comment").val();
	var csrf_token = $(".csrf_token").val();

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
						'comment': comment,
						'id': currentId[1],
						'csrf_token' : csrf_token
					}),
					url: 'addComment.php',
					success: function (data) {
						if (data.status == "commentAdded") {
							$(".comments-container").append("<div class='comment-text'>"+data.commenter+" wrote: "+comment+"</div>");
							location.reload();
						} else if (data.status == "commentNOTAdded") {
						} else if (data.status == "securityIssue") {
						}
					},
					error: function () {
					}
				});

			} else if (data.status == "notSet") {
			} else {
			}
		},
		error: function () {
		}
	});
});









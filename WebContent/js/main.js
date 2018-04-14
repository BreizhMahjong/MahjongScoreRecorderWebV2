var SERVER_QUERY_URL = "https://breizhmahjong.fr/bmjc/query.php";

function loginEvent() {
	var login = $("#loginInput").val();
	var password = $("#passwordInput").val();
	$.post(SERVER_QUERY_URL, {
	    "action" : "login",
	    "username" : login,
	    "password" : password
	}, function(result) {
		var result = $.parseJSON(result);
		if (result.result) {
			location.reload();
		} else {
			$("#loginError").text(result.message);
		}
	});
}

function logoutEvent() {
	$.post(SERVER_QUERY_URL, {
		"action" : "logout"
	}, function(result) {
		location.reload();
	});
}

function showLoading() {
	$(".table").hide();
	$(".chart").hide();
	$(".loadingImage").show();
}

function hideLoading() {
	$(".table").show();
	$(".chart").show();
	$(".loadingImage").hide();
}

$(document).ready(function() {
	$(".nav li.disabled a").click(function() {
		return false;
	});

	$("#loginButton").leanModal({
	    "top" : 20,
	    "overlay" : 0.4,
	    "closeButton" : ".modal_close"
	});

	$("#logoutButton").on("click", function() {
		logoutEvent();
	});

	$(".userLogo").error(function() {
		var userLogo = $(this);
		userLogo.attr("src", SERVER_URL + "/wp-content/uploads/2015/11/bambou-1.png");
		userLogo.load();
	});
});
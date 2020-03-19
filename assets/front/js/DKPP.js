$(document).ready(function() {
	"use-strict";
	$(function() {
		$('a[href*=#]:not([href=#])').click(function() {
			if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
				var target = $(this.hash);
				target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
				if (target.length) {
					$('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
					return false;
				}
			}
		});
	});
	
});

// URL
var urlWeb = "http://www.dkpp.go.id/";
var urlLogin = "http://localhost:88/sipepp/swap/login";

function goToWeb() {
	window.location = urlWeb;
}

function goToLogin() {
	window.location = urlLogin;
}

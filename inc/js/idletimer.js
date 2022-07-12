var idleTimeout;
function resetIdle() {
	if(idleTimeout) {
		clearTimeout(idleTimeout);
	}
	idleTimeout = setTimeout(function(){window.location='../bidding'}, 5*60*1000);
};
resetIdle();

document.addEventListener('click', resetIdle, false);
document.addEventListener('touchstart', resetIdle, false);
document.addEventListener('mousemove', resetIdle, false);
document.addEventListener('keypress', resetIdle, false);

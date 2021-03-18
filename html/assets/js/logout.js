let idleMax = (60 * idleMinutes),
    idleTime = 0,
    idleWarning = 10,
    idleTimer;

function intervalTimer() {
    idleTime = idleTime + 1;
    console.log(idleTime + '/' + idleMax);
    if (idleTime >= idleMax) {
        clearTimeout(idleTimer);
        $('body').removeClass('auto-logout');
        window.location = "/logout";
    }
    else {
        if (idleTime > (idleMax - idleWarning)) {
            $('body').addClass('loading auto-logout');
            $('#auto-logout span').text(idleMax - idleTime);
        }
        resetTimer();
    }
}

function resetTimer() {
    clearTimeout(idleTimer);
    startTimer();
}

function startTimer() {
    idleTimer = setTimeout(intervalTimer, 1000);
}

$(document).ready(function(){
    startTimer();
    $('*').bind('mousemove keydown scroll', function () {
        idleTime = 0;
        resetTimer();
    });
});
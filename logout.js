$(document).ready(function () {
    let logoutTimer;

    $(window).on('beforeunload', function () {

        clearTimeout(logoutTimer);
        logoutTimer = setTimeout(function () {

            $.ajax({
                type: 'POST',
                url: 'logout.php',
                data: { manual_logout: 0 }, 
                async: false,
            });
        }, 10000);
    });
});

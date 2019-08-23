 var targethref = '';

/************************
 * Cookie Functions
 ************************/

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name + '=; Max-Age=-99999999;';
}


/************************
 * Editing Functions
 ************************/

function applyEdit(target, label, url) {
    if (url.indexOf("http") < 1) {
        url = "http://" + url;
        // console.log(url);
    }
    $("span#" + target).text(label);
    $("a#" + target).attr('href', url);
}

$(".listing-apps > li").click(function(e) {
    if (event.ctrlKey || event.metaKey) {
        e.preventDefault();

        $('.user').css('filter', 'blur(2px)');
        $('.listing-apps').css('filter', 'blur(2px)');
        $('.footer').css('filter', 'blur(2px)');


        targethref = $(event.target).closest('a').attr('id');

        $("#modal").iziModal({
            onOpening: function() {
                $('.modalEdit').css('opacity', '1');
                $('.modalEdit').css('z-index', '100');
                $('.editLabel').val('');
                $('.editURL').val('');
            },
            onClosing: function() {
                $('.modalEdit').css('opacity', '0');
                $('.user').css('filter', 'blur(0px)');
                $('.listing-apps').css('filter', 'blur(0px)');
                $('.footer').css('filter', 'blur(0px)');
            },
            radius: 10,
            theme: 'light',
            title: 'Change Box',
            width: '250px',
            focusInput: true,
            headerColor: '#0b506f',
            transitionOut: 'bounceOutDown'
        });

        $('#modal').iziModal('open', {});

    } else {
        e.stopPropagation();
        e.stopImmediatePropagation();
        $(this).addClass('non_edit').removeClass('edit').unbind('click.editable');
        var id = $(this).attr('id');
        //  console.log('clickedid: ' + id);
    }


    $("#modal").on('click', '.btnSubmit', function(e) {
        e.preventDefault();
        editName = $('.editLabel').val();
        editValue = $('.editURL').val();
        if (editName.length > 8) {
            var notification = document.querySelector('.mdl-js-snackbar');
            notification.MaterialSnackbar.showSnackbar({
                message: 'Names must be shorter than 9 characters'
            });
            return;
        }
        if (editValue.indexOf('.') < 1) {
            var notification = document.querySelector('.mdl-js-snackbar');
            notification.MaterialSnackbar.showSnackbar({
                message: 'Are you sure that is a valid URL?'
            });
            return;
        }
        setCookie(targethref, editName + "," + editValue, 36000);
        applyEdit(targethref, editName, editValue);
        $('#modal').iziModal('close', {});
        delete targethref;
    });

    $("#modal").on('click', '.btnReset', function(e) {
        e.preventDefault();
        var originalLabel = $("span#" + targethref).defaultValue;
        var originalURL = $("a#" + targethref).defaultValue;
        eraseCookie(targethref);
        $("span#" + targethref).text(originalLabel);
        $("a#" + targethref).attr('href', originalURL);
        $('#modal').iziModal('close', {});
        window.location.href = "https://nt.ndo.dev";
    });

});

function checkSubmit(e) {
    if (e && e.keyCode == 13) {
        // document.forms[0].submit();
        $('.btnSubmit').click();
    }
}

function loadEdits() {

    var link = [];

    for (i = 1; i <= 12; i++) {
        if (typeof $.cookie('a' + i) !== 'undefined') {
            link[i] = getCookie("a" + i);
        }
    }

    for (i = 1; i <= 12; i++) {
        if (typeof $.cookie('a' + i) !== 'undefined') {
            var cookieVal = link[i];

            var cookieArray = cookieVal.split(",");
            var cookieLabel = cookieArray[0];
            var cookieURL = cookieArray[1];

            if (!cookieURL.includes("http")) {
                cookieURL = "https://" + cookieURL;
            }

            $("span#a" + i).text(cookieLabel);
            $("a#a" + i).attr('href', cookieURL);
        }
    }
}

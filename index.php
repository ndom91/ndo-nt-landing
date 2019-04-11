<!DOCTYPE HTML>
<HTML>

<head>
    <script>
        var lIP = '';
      // NOTE: window.RTCPeerConnection is "not a constructor" in FF22/23
    var RTCPeerConnection = /*window.RTCPeerConnection ||*/ window.webkitRTCPeerConnection || window.mozRTCPeerConnection;

    if (RTCPeerConnection) (function () {
        var rtc = new RTCPeerConnection({iceServers:[]});
        if (1 || window.mozRTCPeerConnection) {      // FF [and now Chrome!] needs a channel/stream to proceed
            rtc.createDataChannel('', {reliable:false});
        };

        rtc.onicecandidate = function (evt) {
            // convert the candidate to SDP so we can run it through our general parser
            // see https://twitter.com/lancestout/status/525796175425720320 for details
            if (evt.candidate) grepSDP("a="+evt.candidate.candidate);

        };
        rtc.createOffer(function (offerDesc) {
            grepSDP(offerDesc.sdp);
            rtc.setLocalDescription(offerDesc);
        }, function (e) { console.warn("offer failed", e); });


        var addrs = Object.create(null);
        addrs["0.0.0.0"] = false;

        function updateDisplay(newAddr) {
            if (newAddr in addrs) return;
            else addrs[newAddr] = true;

            var displayAddrs = Object.keys(addrs).filter(function (k) { return addrs[k]; });
            document.cookie = "IP=" + displayAddrs;
            //console.log('displayAddrs: ' + displayAddrs);
            var lIP = displayAddrs;
            //console.log('lIP: ' + lIP);
            //document.getElementById('list').textContent = displayAddrs.join(" or perhaps ") || "n/a";
        }


        function grepSDP(sdp) {
            var hosts = [];
            sdp.split('\r\n').forEach(function (line) { // c.f. http://tools.ietf.org/html/rfc4566#page-39
                if (~line.indexOf("a=candidate")) {     // http://tools.ietf.org/html/rfc4566#section-5.13
                    var parts = line.split(' '),        // http://tools.ietf.org/html/rfc5245#section-15.1
                        addr = parts[4],
                        type = parts[7];
                    if (type === 'host') updateDisplay(addr);
                } else if (~line.indexOf("c=")) {       // http://tools.ietf.org/html/rfc4566#section-5.7
                    var parts = line.split(' '),
                        addr = parts[2];
                    updateDisplay(addr);
                }
            });
        }
    })(); else {
        document.getElementById('list').innerHTML = "<code>ifconfig | grep inet | grep -v inet6 | cut -d\" \" -f2 | tail -n1</code>";
        document.getElementById('list').nextSibling.textContent = "In Chrome and Firefox your IP should display automatically, by the power of WebRTCskull.";
    }

    </script>


    <title>newtelco home</title>

    <link type="text/css" rel="stylesheet" href="assets/css/style.css" />

    <meta name="theme-color" content="#67B246" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="https://fonts.googleapis.com/css?family=Rajdhani:300,400,700|Ubuntu+Mono:300|Roboto+Mono:300,400,700|Major+Mono+Display" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    <script src="//twemoji.maxcdn.com/2/twemoji.min.js?11.2"></script>

    <!-- LOAD JQUERY -->
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
    <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script> -->
    <!-- <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel='stylesheet' /> -->
    <!-- LOAD JQUERY UI -->

    <!-- jQuery Modal -->
    <link rel="stylesheet" href="assets/css/iziModal.css">
    <script src="assets/js/iziModal.js" type="text/javascript"></script>

    <!-- twemoji -->
    <link type="text/css" rel="stylesheet" href="assets/css/twemoji.css" />

    <!-- mdl -->
    <link type="text/css" rel="stylesheet" href="assets/css/material_snackbar.css" />
    <script src="assets/js/material.js" type="text/javascript"></script>

    <!-- hover css -->
    <link type="text/css" rel="stylesheet" href="assets/css/hover.min.css" />

    <script>
        // webticker inline
        ! function(i) {
            function t(t) {
                var e = 0;
                return t.children("li").each(function() {
                    e += i(this).outerWidth(!0)
                }), e
            }

            function e(t) {
                return Math.max.apply(Math, t.children().map(function() {
                    return i(this).width()
                }).get())
            }

            function s(i) {
                var t = i.data("settings") || {
                        direction: "left",
                        speed: 15
                    },
                    e = i.children().first(),
                    s = Math.abs(-i.css(t.direction).replace("px", "").replace("auto", "0") - e.outerWidth(!0)),
                    n = 1e3 * s / t.speed,
                    r = {};
                return r[t.direction] = i.css(t.direction).replace("px", "").replace("auto", "0") - s, {
                    css: r,
                    time: n
                }
            }

            function n(i) {
                var t = i.data("settings") || {
                    direction: "left"
                };
                i.css("transition-duration", "0s").css(t.direction, "0");
                var e = i.children().first();
                e.hasClass("webticker-init") ? e.remove() : i.children().last().after(e)
            }

            function r(i, t) {
                var e = i.data("settings") || {
                    direction: "left"
                };
                "undefined" == typeof t && (t = !1), t && n(i);
                var a = s(i);
                i.animate(a.css, a.time, "linear", function() {
                    i.css(e.direction, "0"), r(i, !0)
                })
            }

            function a(i, t) {
                "undefined" == typeof t && (t = !1), t && n(i);
                var e = s(i),
                    r = e.time / 1e3;
                r += "s", i.css(e.css).css("transition-duration", r)
            }

            function c(t, e, s) {
                var n = [];
                i.get(t, function(t) {
                    var r = i(t);
                    r.find("item").each(function() {
                        var t = i(this),
                            e = {
                                title: t.find("title").text(),
                                link: t.find("link").text()
                            },
                            s = '<li><a href="' + e.link + '"">' + e.title + "</a></li>";
                        n += s
                    }), s.webTicker("update", n, e)
                })
            }

            function l(s, n) {
                if (s.children("li").length < 1) return window.console, !1;
                var r = s.data("settings");
                r.duplicateLoops = r.duplicateLoops || 0, s.width("auto");
                var a = 0;
                s.children("li").each(function() {
                    a += i(this).outerWidth(!0)
                });
                var c, l = s.find("li:first").height();
                if (r.duplicate) {
                    c = e(s);
                    for (var o = 0; a - c < s.parent().width() || 1 === s.children().length || o < r.duplicateLoops;) {
                        var d = s.children().clone();
                        s.append(d), a = 0, a = t(s), c = e(s), o++
                    }
                    r.duplicateLoops = o
                } else {
                    var h = s.parent().width() - a;
                    h += s.find("li:first").width(), s.find(".ticker-spacer").length > 0 ? s.find(".ticker-spacer").width(h) : s.append('<li class="ticker-spacer" style="float: ' + r.direction + ";width:" + h + "px;height:" + l + 'px;"></li>')
                }
                r.startEmpty && n && s.prepend('<li class="webticker-init" style="float: ' + r.direction + ";width:" + s.parent().width() + "px;height:" + l + 'px;"></li>'), a = 0, a = t(s), s.width(a + 200);
                var f = 0;
                for (f = t(s); f >= s.width();) s.width(s.width() + 200), f = 0, f = t(s);
                return !0
            }
            var o = function() {
                    var i = document.createElement("p").style,
                        t = ["ms", "O", "Moz", "Webkit"];
                    if ("" === i.transition) return !0;
                    for (; t.length;)
                        if (t.pop() + "Transition" in i) return !0;
                    return !1
                }(),
                d = {
                    init: function(t) {
                        return t = jQuery.extend({
                            speed: 15,
                            direction: "left",
                            moving: !0,
                            startEmpty: !0,
                            duplicate: !1,
                            rssurl: !1,
                            hoverpause: !0,
                            rssfrequency: 0,
                            updatetype: "reset",
                            transition: "linear",
                            height: "30px",
                            maskleft: "",
                            maskright: "",
                            maskwidth: 0
                        }, t), this.each(function() {
                            jQuery(this).data("settings", t);
                            var e = jQuery(this),
                                s = e.wrap('<div class="mask"></div>');
                            s.after('<span class="tickeroverlay-left">&nbsp;</span><span class="tickeroverlay-right">&nbsp;</span>');
                            var n, d = e.parent().wrap('<div class="tickercontainer"></div>');
                            if (i(window).resize(function() {
                                    clearTimeout(n), n = setTimeout(function() {
                                        console.log("window was resized"), l(e, !1)
                                    }, 500)
                                }), e.children("li").css("white-space", "nowrap"), e.children("li").css("float", t.direction), e.children("li").css("padding", "0 7px"), e.children("li").css("line-height", t.height), s.css("position", "relative"), s.css("overflow", "hidden"), e.closest(".tickercontainer").css("height", t.height), e.closest(".tickercontainer").css("overflow", "hidden"), e.css("float", t.direction), e.css("position", "relative"), e.css("font", "bold 10px Verdana"), e.css("list-style-type", "none"), e.css("margin", "0"), e.css("padding", "0"), "" !== t.maskleft && "" !== t.maskright) {
                                var h = 'url("' + t.maskleft + '")';
                                d.find(".tickeroverlay-left").css("background-image", h), d.find(".tickeroverlay-left").css("display", "block"), d.find(".tickeroverlay-left").css("pointer-events", "none"), d.find(".tickeroverlay-left").css("position", "absolute"), d.find(".tickeroverlay-left").css("z-index", "30"), d.find(".tickeroverlay-left").css("height", t.height), d.find(".tickeroverlay-left").css("width", t.maskwidth), d.find(".tickeroverlay-left").css("top", "0"), d.find(".tickeroverlay-left").css("left", "-2px"), h = 'url("' + t.maskright + '")', d.find(".tickeroverlay-right").css("background-image", h), d.find(".tickeroverlay-right").css("display", "block"), d.find(".tickeroverlay-right").css("pointer-events", "none"), d.find(".tickeroverlay-right").css("position", "absolute"), d.find(".tickeroverlay-right").css("z-index", "30"), d.find(".tickeroverlay-right").css("height", t.height), d.find(".tickeroverlay-right").css("width", t.maskwidth), d.find(".tickeroverlay-right").css("top", "0"), d.find(".tickeroverlay-right").css("right", "-2px")
                            } else d.find(".tickeroverlay-left").css("display", "none"), d.find(".tickeroverlay-right").css("display", "none");
                            e.children("li").last().addClass("last");
                            var f = l(e, !0);
                            t.rssurl && (c(t.rssurl, t.type, e), t.rssfrequency > 0 && window.setInterval(function() {
                                c(t.rssurl, t.type, e)
                            }, 1e3 * t.rssfrequency * 60)), o ? (e.css("transition-timing-function", t.transition), e.css("transition-duration", "0s").css(t.direction, "0"), f && a(e, !1), e.on("transitionend webkitTransitionEnd oTransitionEnd otransitionend", function(t) {
                                return !!e.is(t.target) && void a(i(this), !0)
                            })) : f && r(i(this)), t.hoverpause && e.hover(function() {
                                if (o) {
                                    var e = i(this).css(t.direction);
                                    i(this).css("transition-duration", "0s").css(t.direction, e)
                                } else jQuery(this).stop()
                            }, function() {
                                jQuery(this).data("settings").moving && (o ? a(i(this), !1) : r(e))
                            })
                        })
                    },
                    stop: function() {
                        var t = i(this).data("settings");
                        if (t.moving) return t.moving = !1, this.each(function() {
                            if (o) {
                                var e = i(this).css(t.direction);
                                i(this).css("transition-duration", "0s").css(t.direction, e)
                            } else i(this).stop()
                        })
                    },
                    cont: function() {
                        var t = i(this).data("settings");
                        if (!t.moving) return t.moving = !0, this.each(function() {
                            o ? a(i(this), !1) : r(i(this))
                        })
                    },
                    transition: function(t) {
                        var e = i(this);
                        o && e.css("transition-timing-function", t)
                    },
                    update: function(e, s, n, r) {
                        s = s || "reset", "undefined" == typeof n && (n = !0), "undefined" == typeof r && (r = !1), "string" == typeof e && (e = i(e));
                        var a = i(this);
                        a.webTicker("stop");
                        var c = i(this).data("settings");
                        if ("reset" === s) a.html(e), l(a, !0);
                        else if ("swap" === s) {
                            var o, d, h, f;
                            if (window.console, a.children("li").length < 1) a.html(e), a.css(c.direction, "0"), l(a, !0);
                            else if (c.duplicate === !0) {
                                a.children("li").addClass("old");
                                for (var p = e.length - 1; p >= 0; p--) o = i(e[p]).data("update"), d = a.find('[data-update="' + o + '"]'), d.length < 1 ? n && (0 === a.find(".ticker-spacer:first-child").length && a.find(".ticker-spacer").length > 0 ? a.children("li.ticker-spacer").before(e[p]) : (h = i(e[p]), p === e.length - 1 && h.addClass("last"), a.find("last").after(h), a.find("last").removeClass("last"))) : a.find('[data-update="' + o + '"]').replaceWith(e[p]);
                                a.children("li.webticker-init, li.ticker-spacer").removeClass("old"), r && a.children("li").remove(".old"), f = 0, f = t(a), a.width(f + 200), a.find("li.webticker-init").length < 1 && (c.startEmpty = !1), a.html(e), a.children("li").css("white-space", "nowrap"), a.children("li").css("float", c.direction), a.children("li").css("padding", "0 7px"), a.children("li").css("line-height", c.height), l(a, !0)
                            } else {
                                a.children("li").addClass("old");
                                for (var u = 0; u < e.length; u++) o = i(e[u]).data("update"), d = a.find('[data-update="' + o + '"]'), d.length < 1 ? n && (0 === a.find(".ticker-spacer:first-child").length && a.find(".ticker-spacer").length > 0 ? a.children("li.ticker-spacer").before(e[u]) : (h = i(e[u]), u === e.length - 1 && h.addClass("last"), a.find(".old.last").after(h), a.find(".old.last").removeClass("last"))) : a.find('[data-update="' + o + '"]').replaceWith(e[u]);
                                a.children("li.webticker-init, li.ticker-spacer").removeClass("old"), a.children("li").css("white-space", "nowrap"), a.children("li").css("float", c.direction), a.children("li").css("padding", "0 7px"), a.children("li").css("line-height", c.height), r && a.children("li").remove(".old"), f = 0, f = t(a), a.width(f + 200)
                            }
                        }
                        a.webTicker("cont")
                    }
                };
            i.fn.webTicker = function(t) {
                return d[t] ? d[t].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof t && t ? void i.error("Method " + t + " does not exist on jQuery.webTicker") : d.init.apply(this, arguments)
            }
        }(jQuery);
    </script>
    <script src="assets/sugar.min.js" type="text/javascript"></script>
    <!-- <script src="assets/js/jquery.jeditable.min.js" type="text/javascript"></script> -->

    <!-- PWA -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="manifest" href="manifest.json" />
    <script src="assets/js/ManUp.js"></script>
    <!-- /PWA -->

    <!-- overlay scrollbars -->
    <script type="text/javascript" src="assets/js/OverlayScrollbars.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/OverlayScrollbars.min.css">

    <!-- Trello client.js -->
    <script src="https://trello.com/1/client.js?key=65852c01ad6403e51950d16e14caf01c"></script>

    <!-- FAVICONS -->
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="assets/icons/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/icons/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/icons/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/icons/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="assets/icons/apple-touch-icon-60x60.png" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="assets/icons/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="assets/icons/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="assets/icons/apple-touch-icon-152x152.png" />
    <link rel="icon" type="image/png" href="assets/icons/favicon-196x196.png" sizes="196x196" />
    <link rel="icon" type="image/png" href="assets/icons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="assets/icons/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="assets/icons/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="assets/icons/favicon-128.png" sizes="128x128" />
    <meta name="application-name" content="ndom91 tools" />
    <meta name="msapplication-TileColor" content="#67B246" />
    <meta name="msapplication-TileImage" content="assets/icons/mstile-144x144.png" />
    <meta name="msapplication-square70x70logo" content="assets/icons/mstile-70x70.png" />
    <meta name="msapplication-square150x150logo" content="assets/icons/mstile-150x150.png" />
    <meta name="msapplication-wide310x150logo" content="assets/icons/mstile-310x150.png" />
    <meta name="msapplication-square310x310logo" content="assets/icons/mstile-310x310.png" />
    <!-- /FAVICONS -->
</head>

<script>
    // var iptechnik = [
    //   ["192.168.11.118","Kay"],
    //   ["192.168.11.87","Stelios"],
    //   ["192.168.11.98","Felix"],
    //   ["192.168.11.133","Andreas"],
    //   // ["192.168.11.69","Georg"],
    //   ["192.168.11.107","Nodar"],
    //   ["192.168.11.132","Jurij"], 
    //   ["192.168.11.111","Kai"],
    //   ["192.168.11.71","Nik"],
    //   ["192.168.11.139","Nico"],
    //   ["192.168.178.92","Nico"],
    //   ["192.168.11.43","Jens"]
    // ];

   var fname = '';

   $(document).ready(function() {

    var ipnames = [
      ["192.168.11.139","Nico"],
      ["192.168.11.43","Jens"],
      ["192.168.11.76","Nataliya"],
      ["192.168.11.99","Mario"],
      ["192.168.11.118","Kay"],
      ["192.168.11.87","Stelios"],
      ["192.168.11.71","Nik"],
      ["192.168.11.98","Felix"],
      ["192.168.11.133","Andreas"],
      ["192.168.11.69","Georg"],
      ["192.168.11.79","Alina"],
      ["192.168.11.78","Alina"],
      ["192.168.11.92","Tatjana"],
      ["192.168.11.83","Dmitri"],
      ["192.168.11.86","German"],
      ["192.168.11.84","Nadezda"],
      ["192.168.11.82","Svetlana"],
      ["192.168.11.61","Svetlana"],
      ["192.168.11.74","Elena"],
      ["192.168.11.72","Sascha"],
      ["192.168.11.14","Olga"],
      ["192.168.11.73","Tamila"],
      ["192.168.11.42","Jurij"],
      ["192.168.11.85","Dmitry"],
      ["192.168.11.107","Nodar"],
      ["192.168.11.145","Paul"],
      ["192.168.1.26","Maria"],
      ["192.168.11.164","Natascha"],
      ["192.168.11.111","Kai"],
      ["192.168.11.144","Nik"],
      ["192.168.11.71","Nik"],
      ["192.168.11.10","Nodo"],
      ["192.168.178.92","Nico"]
    ];


      
    var userIP = getCookie("IP");

    // if ipv4 and ipv6 are returned..
    if(userIP.indexOf(",") != -1) {
        var ipArray = userIP.split(",");
        userIP = ipArray[0];
    }

    var arr = ipnames.filter( function( el ) {
        return !!~el.indexOf( userIP );
    } );

    //console.log(JSON.stringify(arr));

    if (JSON.stringify(arr) == '[]') {
      var fname = '';
    } else {
      var fname = arr[0][1];
    }

    $('.fnameWelcome').prepend("Welcome " + fname + "  ");

    // var submitdata = {}

    // submitdata['slow'] = true;

  //  $("[class^=name]").editable("save.php", {
  //     loadurl  : 'load.php',
  //     loadtype : 'POST',
  //     loadtext : 'Loading…',
  //     data   : '<?php print $array; ?>',
  //     indicator : "<img src='assets/spinner.svg' />",
  //     type : "text",
  //     cssclass : 'editorClass',
  //     label : '',
  //     tooltip : "[CTRL/STRG] + Click to Edit",
  //     event: '',
  //     width : 120
  //   });
  });
  

</script>

<body id="body1" onload="loadEdits()">

    <div class="overlay">
        <div class="ynh-wrapper user">
            <ul class="user-menu">
                <!-- twemoji home:          http://ellekasai.github.io/twemoji-awesome/
           twemoji cheat sheet:   https://www.webfx.com/tools/emoji-cheat-sheet/ -->
                <li><a href=""><i class="fnameWelcome"></i><i class="twa twa-zap"></i></a></li>
            </ul>

            <a class="user-container user-container-info" href="https://home.newtelco.de">
                <h2 class="user-username">NewTelco GmbH</h2>
                <small class="user-fullname">Launcher</small>
                <span class="user-mail">home.newtelco.de</span>
            </a>
        </div>

    </div>
    <div class="bottomwrapper">
        <div id="apps" class="wrapper apps">
            <ul class="listing-apps">
                <li><a target="_blank" class="green2" id="a1" href="https://crm.newtelco.de"><span class="first-letter"></span><span id="a1" class="name1">CRM</span></a></li>
                <li><a target="_blank" class="green3" id="a2" href="http://gmail.newtelco.de"><span class="first-letter"></span><span id="a2" class="name2">gmail</span></a></li>
                <li><a target="_blank" class="green4" id="a3" href="http://drive.newtelco.de"><span class="first-letter"></span><span id="a3" class="name3">drive</span></a></li>
                <li><a target="_blank" class="green4" id="a4" href="https://cloud.newtelco.de"><span class="first-letter"></span><span id="a4" class="name4">ntcloud</span></a></li>
                <li><a target="_blank" class="green1" id="a5" href="https://lager.newtelco.de"><span class="first-letter"></span><span id="a5" class="name5">lager</span></a></li>
                <li><a target="_blank" class="green2" id="a6" href="https://docs.newtelco.dev/"><span class="first-letter"></span><span id="a6" class="name6">docs</span></a></li>
                <li><a target="_blank" class="green3" id="a7" href="http://help.newtelco.de"><span class="first-letter"></span><span id="a7" class="name7">help</span></a></li>
                <li><a target="_blank" class="green4" id="a8" href="http://password.newtelco.local"><span class="first-letter"></span><span id="a8" class="name8">pw</span></a></li>
                <li><a target="_blank" class="green1" id="a9" href="https://admin.newtelco.de"><span class="first-letter"></span><span id="a9" class="name9">admin</span></a></li>
                <li><a target="_blank" class="green1" id="a10" href="https://racks.newtelco.de"><span class="first-letter"></span><span id="a10" class="name10">racks</span></a></li>
                <li><a target="_blank" class="green2" id="a11" href="https://nms.newtelco.tech"><span class="first-letter"></span><span id="a11" class="name11">nms</span></a></li>
                <li><a target="_blank" class="green3" id="a12" href="https://trello.com/b/kPnaIXSu"><span class="first-letter"></span><span id="a12" class="name12">trello</span></a></li>
            </ul>

        </div>
        <div class="ynh-wrapper footer">
            <div class="trelloWrapper">
                <div class="mask">
                    <div class="trelloHeader">Technik: </div>
                    <ul id="trelloUL">
                    </ul>
                    <!-- <span class="tickeroverlay-left">&nbsp;</span>
        <span class="tickeroverlay-right">&nbsp;</span> -->
                </div>
            </div>
            <nav>
                <div class="footer1">
                    <a class="link-newtelco hvr-sweep-to-top" href="https://newtelco.com" target="_blank">newtelco.com</a>
                </div>
                <div class="footer2">
                    <div class="mask">
                        <ul id="ntnews-ticket">
                            <li class="ticker-spacer"></li>
                            <li> <i class="fas fa-server"></i> <b style="letter-spacing:2px; margin: 0 13px; color: #67B246;">NEWTELCO NEWS</b> <i class="fas fa-server"></i> </li>
                            <li><i class="twa twa-tada"></i> Welcome to the new office! <i class="twa twa-tada"></i></li>
                            <!-- <li> <font style="color: #67B246; font-weight: 700; font-size: 22px">|</font> </li> -->
                            <!-- <li><i class="twa twa-sparkler"></i><i class="twa twa-tada"></i><i class="twa twa-beers"></i>  Happy New Years!  <i class="twa twa-beers"></i><i class="twa twa-tada"></i><i class="twa twa-sparkler"></i></li> -->
                            <li>
                                <font style="color: #67B246; font-weight: 700; font-size: 22px">|</font>
                            </li>
                            <li>
                                <font style="font-weight: 700;">[STRG/CTRL + Click] to edit significantly updated!</font>
                            </li>
                            <li>
                                <font style="color: #67B246; font-weight: 700; font-size: 22px">|</font>
                            </li>
                            <li>This Site (home.newtelco.de) available on mobile!</li>
                            <li>
                                <font style="color: #67B246; font-weight: 700; font-size: 22px">|</font>
                            </li>
                            <li class="last">Domain password expiration changed to 90 days including email notifications</li>
                        </ul>
                        <span class="tickeroverlay-left">&nbsp;</span>
                        <span class="tickeroverlay-right">&nbsp;</span>
                    </div>
                </div>

                <div class="footer3">
                    <!-- <span class="love">&hearts;</span>-->
                    <div class="footerawrapper"><a target="_blank" class="footera hvr-sweep-to-top" href="https://github.com/ndom91">ndom91</a> &copy;</div>
                </div>


            </nav>
        <script>

        // TRELLO FUNCTION FOR TECHNIK!
        function authenticationSuccess() {
            $("#trelloUL").webTicker({
                height:'25px',
                duplicate:true,
                startEmpty:true,
                updatetype:'reset'
            });
            $("#stop-newsticker2").click(function(){
                $("#trelloUL").webTicker('stop');
            });
            $("#continue-newsticker2").click(function(){
                $("#trelloUL").webTicker('cont');
            });
        
        var trelloMembers = '';
        var trelloLbl = '';

        var membersSuccess = function(successMsg) {
            // console.log(JSON.stringify(successMsg));
            trelloMembers = successMsg;
        }

        var trelloMembers = Trello.get('/boards/5c671254bcea64060f2d0161/members', membersSuccess);

        var labelsSuccess = function(successMsg) {
            // console.log(JSON.stringify(successMsg));
            trelloLbl = successMsg;
        }

        var trelloLabel = Trello.get('/boards/5c671254bcea64060f2d0161/labels', labelsSuccess);

        var boardSuccess = function(successMsg) {
          var cards = [];
          var users = [];
          var labels = [];
        //   DEBUGGING STATEMENTS
        //   console.log("%c Debugging Trello API Response: ", "background: #67B246; color: #fff; font-weight: 700; font-size: large;");
        //   console.log(successMsg);
        //   console.log(trelloMembers);
        //   console.log(trelloLbl);
          $.each(successMsg, function(index, value) {
            if (value.idList != "5c67128447e0f057aba1e9ae") {
              cards.push([value.name,value.shortUrl]);
            }
          })
          var cardLi = '';
          for (i = 0; i < successMsg.length; i++) {
            if (successMsg[i].idList != "5c67128447e0f057aba1e9ae") {
              //   console.log(i);
              var labelLi = '';
              var cardImg = '';
              var cardName = cards[i][0];
              var shortUrl = cards[i][1];
            
              for(b = 0; b < successMsg[i].idMembers.length; b++) {
                let userObj = trelloMembers.find(obj => {
                    return obj.id == successMsg[i].idMembers[b]
                })
                var username = userObj.username;
                cardImg = cardImg + '<a target="_blank" href="https://trello.com/' + username + '"><img title="' + username + '" class="trelloIcons" src="assets/people/' + username + '.png"/></a>';
              }

              for(c = 0; c < successMsg[i].idLabels.length; c++) {
                let labelObj = trelloLbl.find(obj => {
                    return obj.id == successMsg[i].idLabels[c]
                })
                var labelName = labelObj.name;
                var labelColor = labelObj.color;
                labelLi = labelLi + '<span style="box-shadow: inset 0px 0px 20px -5px ' + labelColor + '" class="labelLabel">' + labelName + '</span>';
              }

              cardLi = cardLi + '<li data-update="item' + [i] + '">' + cardImg + '<a target="_blank" href="' + shortUrl + '">' + cardName + '</a>' + labelLi + '</li><li> <font style="color: #67B246; font-weight: 700; font-size: 22px">|</font> </li>';
              $('#trelloUL').append('<li data-update="item' + [i] + '"></li>');
            }
          }
          $('#trelloUL').webTicker('update', cardLi, 'swap', true, true);
        }
        var trelloJSON = Trello.get('/boards/5c671254bcea64060f2d0161/cards/open', boardSuccess);
      }
    </script>
        </div>
    </div>
</body>
<!-- Modal -->
<div id="modal">
    <div class="modalEdit">
        <form id="editForm">
            <h3 class="subTitle">Edit Link</h3>
            <div onKeyPress="return checkSubmit(event)" class="modalInputs">
                <input class="editLabel" placeholder="Name" />
                <input class="editURL" placeholder="URL" />
            </div>
            <div class="btns">
                <a type="submit" class="btnSubmit">Save</a>
                <a type="submit" class="btnReset">Reset Block</a>
            </div>
        </form>
    </div>
</div>
<div class="mdl-snackbar mdl-js-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button type="button" class="mdl-snackbar__action"></button>
</div>
<footer>
    <script>
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
                    headerColor: '#67B246',
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
                // console.log('originalLabel: ' + originalLabel);
                // console.log(originalLabel);
                // console.log('originalURL: ' + originalURL);
                // console.log(originalURL);
                eraseCookie(targethref);
                $("span#" + targethref).text(originalLabel);
                $("a#" + targethref).attr('href', originalURL);
                $('#modal').iziModal('close', {});
                window.location.href = "https://home.newtelco.de";
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

                    if (cookieURL.indexOf("http") < 1) {
                        cookieURL = "http://" + cookieURL;
                    }

                    $("span#a" + i).text(cookieLabel);
                    $("a#a" + i).attr('href', cookieURL);
                }
            }
        }


        /************************
         * Ticker Functions
         ************************/

        $(document).ready(function() {
            $("#ntnews-ticket").webTicker({
                height: '15px',
                duplicate: true,
                rssfrequency: 0,
                startEmpty: false,
                hoverpause: true
            });
            $("#stop-newsticker2").click(function() {
                $("#ntnews-ticket").webTicker('stop');
            });
            $("#continue-newsticker2").click(function() {
                $("#ntnews-ticket").webTicker('cont');
            });


        });

        /************************
         * Scrollbars
         ************************/

        document.addEventListener("DOMContentLoaded", function() {
            //The first argument are the elements to which the plugin shall be initialized
            //The second argument has to be at least a empty object or a object with your desired options
            OverlayScrollbars(document.querySelectorAll("body"), {
                className: "os-theme-light",
                resize: "vertical",
                sizeAutoCapable: false
            });
        });
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-127102919-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-127102919-1');

        var userIP = getCookie("IP");

        // if ipv4 and ipv6 are returned..
        if(userIP.indexOf(",") != -1) {
            var ipArray = userIP.split(",");
            userIP = ipArray[0];
        }

        // GET TECHNIK IPs FROM VARIABLE ABOVE
        // var arr2 = iptechnik.filter(function(el2) {
        //     return !!~el2.indexOf(userIP);
        // });
        
        // GET TECHNIK IPs FROM LDAP FROM PHP FILE
        $.ajax({
            url: 'ldap.php'
        }).done(function(response) {
            var ip_array = JSON.parse(response);
            ip_array.push("192.168.178.92");
            console.log(ip_array);
            console.log(userIP);
            var arr3 = ip_array.filter(function(el2) {
                return !!~el2.indexOf(userIP);
            });
            if (JSON.stringify(arr3) == '[]') {
                $('.trelloWrapper').html("");
                var fname = '';
            } else {
                // TODO Uncomment to activate trello on Header
                window.Trello.authorize({
                    type: 'popup',
                    name: 'Newtelco Trello App',
                    scope: {
                        read: 'true',
                        write: 'false'
                    },
                    expiration: 'never',
                    success: authenticationSuccess
                    // error: alert("Auth Failed!")
                });
            }
        })

    </script>
    <!-- Google Analytics End -->

</footer>
</div>

</html> 
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
            console.log('displayAddrs: ' + displayAddrs);
            var lIP = displayAddrs;
            console.log('lIP: ' + lIP);
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

    <link type="text/css" rel="stylesheet" href="style.css" />

    <meta name="theme-color" content="#67B246" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="https://fonts.googleapis.com/css?family=Rajdhani:300,400,700|Ubuntu+Mono:300|Roboto+Mono:300,400,700" rel="stylesheet">
    <!-- <script type="text/javascript" src="assets/js/panel.js"></script> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">

    <!-- LOAD JQUERY -->
    <script src="assets/js/jquery-3.3.1.min.js"></script>

    <!-- LOAD JQUERY UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel='stylesheet' />

    <script src="assets/sugar.min.js" type="text/javascript"></script>
    <script src="assets/js/jquery.jeditable.min.js" type="text/javascript"></script>
    <!--   <script src="assets/js/webrtcip.js" type="text/javascript"></script>   -->
    <script>
    console.log('lIPinline: ' + lIP);
    </script>
    <!-- PWA -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="manifest" href="manifest.json"></link>
    <script src="assets/js/ManUp.js"></script>
    <!-- /PWA -->

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
    <meta name="application-name" content="ndom91 tools"/>
    <meta name="msapplication-TileColor" content="#67B246" />
    <meta name="msapplication-TileImage" content="assets/icons/mstile-144x144.png" />
    <meta name="msapplication-square70x70logo" content="assets/icons/mstile-70x70.png" />
    <meta name="msapplication-square150x150logo" content="assets/icons/mstile-150x150.png" />
    <meta name="msapplication-wide310x150logo" content="assets/icons/mstile-310x150.png" />
    <meta name="msapplication-square310x310logo" content="assets/icons/mstile-310x310.png" />
    <!-- /FAVICONS -->


</head>

   <script>
     /*$(document).ready(function() {
      $('.name').editable(function(value, settings) {
          console.log(this);
          console.log(value);
          console.log(settings);
          return(value);
      }, {
          type    : 'textarea',
          submit : 'OK',
          cancel : 'Cancel',
          cssclass : 'custom-class',
          event     : 'dbclick',
          tooltip   : 'Double click to edit…'
          cancelcssclass : 'btn btn-danger',
          submitcssclass : 'btn btn-success',
      });

     $('.edit_area').editable('http://www.example.com/save.php', {
         type      : 'textarea',
         cancel    : 'Cancel',
         submit    : 'OK',
         indicator : '<img src="assets/spinner.svg">',
         tooltip   : 'Click to edit...'
     });
   }*/

  <?php

    $array = json_decode($_COOKIE['a3'], true);

    /*$array['E'] =  'Letter E';
    $array['F'] =  'Letter F';
    $array['G'] =  'Letter G';
    $array['selected'] =  'F';*/
  ?>

$(document).ready(function() {
   /* data that will be sent along */
    var submitdata = {}
    /* this will make the save.php script take a long time so you can see the spinner ;) */
    submitdata['slow'] = true;
    /*submitdata['pwet'] = 'youpla';*/
  

    /*var clickedClass = "";

    $("[class*=name]").click(function() {
       var clickedClass = this.className;
       console.log(clickedClass);
    });

          [class^=name]

    */

   $("[class^=name]").editable("save.php", {
      loadurl  : 'load.php',
      loadtype : 'POST',
      loadtext : 'Loading…',
      data   : '<?php print json_encode($array); ?>',
      indicator : "<img src='assets/spinner.svg' />",
      type : "text",
      //pattern: "[a-zA-Z0-9_]{8}",
      onedit : function() { console.log('If I return false editing will be canceled'); return true;},
      before : function() { console.log('Triggered before form appears')},
      /*callback : function(result, settings, submitdata) {
          console.log('Triggered after submit');
          console.log('Result: ' + result);
          console.log('Settings.width: ' + settings.width);
          console.log('Submitdata: ' + submitdata.pwet);
      },*/
      cancel : 'Cancel',
      onblur : "ignore",
      cssclass : 'editorClass',
      cancelcssclass : 'btn btn-danger',
      submitcssclass : 'btn btn-success',
      maxlength : 200,
      showfn : function(elem) { elem.fadeIn('slow') },
      // select all text
      select : true,
      label : '',
      tooltip : "Click to edit block",
      onreset : function() { console.log('Triggered before reset') },
      onsubmit : function() { console.log('Triggered before submit') },
      submit : 'Save',
      submitdata : submitdata,/*
      submitdata :  function( value, settings){
              var parent_id = $(this).attr('id');
              return { action : 'update', id: parent_id};  
            },*/
      width : 120
  });
});
   /* target as a function example
  submitdata: function( value, settings){
              var parent_id = $(this).attr('id');
              return { action : 'update', id : parent_id};  
            },
  */
   </script>
  <script>
    //This is the "Offline copy of pages" service worker

    //Add this below content to your HTML page, or add the js file to your page at the very top to register service worker
    /*  if (navigator.serviceWorker.controller) {
        console.log('[PWA Builder] active service worker found, no need to register')
    } else {
        //Register the ServiceWorker
        navigator.serviceWorker.register('pwabuilder-sw.js', {
            scope: './'
        }).then(function(reg) {
            console.log('Service worker has been registered for scope:'+ reg.scope);
        });
    } */
  </script>
<body>

	<div class="overlay">
	    <div class="ynh-wrapper user">
	  <ul class="user-menu">
	    <!-- <li><a href=""><i class="fab fa-angellist"></i></a></li> -->
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
       <li><a target="_blank" class="green2" href="https://crm.newtelco.de"><span class="first-letter"></span><span id="a1"  class="name1">CRM</span></a></li>
       <li><a target="_blank" class="green3" href="http://gmail.newtelco.de"><span class="first-letter"></span><span id="a2"  class="name2">Gmail</span></a></li>
       <li><a target="_blank" class="green4" href="http://drive.newtelco.de"><span class="first-letter"></span><span id="a3"  class="name3">Drive</span></a></li>
       <li><a target="_blank" class="green4" href="https://newtelcogmbh.slack.com"><span class="first-letter"></span><span id="a4"  class="name4">Slack</span></a></li>
       <li><a target="_blank" class="green1" href="http://links.newtelco.de"><span class="first-letter"></span><span id="a5"  class="name5">Links</span></a></li>
       <li><a target="_blank" class="green2" href="http://vpn.newtelco.de"><span class="first-letter"></span><span id="a6"  class="name6">VPN</span></a></li>
       <li><a target="_blank" class="green3" href="http://help.newtelco.de"><span class="first-letter"></span><span id="a7"  class="name7">Help</span></a></li>
       <li><a target="_blank" class="green4" href="http://password.newtelco.local"><span class="first-letter"></span><span id="a8"  class="name8">PW</span></a></li>
       <li><a target="_blank" class="green1" href="https://it.newtelco.de"><span class="first-letter"></span><span id="a9"  class="name9">IT</span></a></li>
       <li><a target="_blank" class="green1" href="http://netbox.newtelco.local"><span class="first-letter"></span><span id="a10" class="name10">Racks</span></a></li>
       <li><a target="_blank" class="green2" href="https://nms.newtelco.tech"><span class="first-letter"></span><span id="a11" class="name11">NMS</span></a></li>
       <li><a target="_blank" class="green3" id="wtf" href="https://git.newtelco.de"><span class="first-letter"></span><span id="a12" class="name12">Git</span></a></li>
     </ul>

   </div>

   <div class="ynh-wrapper footer"><nav>

      built with <span class="love">&hearts;</span> by <a target="_blank" class="footera" href="https://github.com/ndom91">ndom91</a> &copy;

      <!-- <a class="link-profile-edit" href="edit.html">Edit my profile</a>
      <a class="link-documentation" href="//yunohost.org/docs" target="_blank">Documentation</a>
      <a class="link-documentation" href="//yunohost.org/help" target="_blank">Support</a>-->
      <a class="link-newtelco" href="https://newtelco.com" target="_blank">newtelco.com

      </a> 
   </nav></div>
   </div>
   </div>
</body>
<footer>
<!-- <script src="https://cdn.jsdelivr.net/npm/@widgetbot/crate@3"  >

    const button = new Crate({
      server: '506121769141141513',
      channel: '506121769141141515',
      shard: 'https://vip1.widgetbot.io',
      //color: '#67B246',
      color: 'rgba(103, 178, 70, 0.5)',
      // The glyph to display on the button
      glyph: ['https://newtelco.tech/chat3.png', '100%'],  
      // Message notifications
      notifications: true,
      // Unread message indicator
      indicator: true,
      location: ['top', 'right'],
      css: '.button {margin-right: 50px; margin-top: 0px;} .notification {margin-right: 50px; margin-top: 10px;} div .icons { margin-right: 0; margin-top: 0; }',
    })

    setTimeout(function(){ 
      console.log('lIP2: ' + lIP)
      var btnNotify = 'Welcome! ' + lIP

      console.log('lIP3: ' + lIP)
      button.notify({
        content: btnNotify,
        timeout: 5000,
        color: '#67B246',
      })}, 6000);
    
</script> 
<script src="https://embed.small.chat/T85U0SU3YGDZE088P2.js" async></script>-->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-127102919-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-127102919-1');
</script>
<!-- Google Analytics End -->

</footer>
</div>
</html>

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
    url: 'ldap.php?technik_computers'
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

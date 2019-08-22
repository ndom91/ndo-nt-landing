/*=====================================================================================================*/
/* Giving credit where credit is due, The JS is all built off of my original mod of Twily's homepage. */
/* If there are any similarities left, it's probably because it's based on his code.                 */
/*==================================================================================================*/

/*var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
var dayNames = ["SUNDAY", "MONDAY", "TUESDAY", "WEDNESDAY", "THRUSDAY", "FRIDAY", "SATURDAY"];

/*=============*/
/*== Options ==*/
/*=============*/

// var CookiePrefix = "taco_stpg_"; //prefix for cookies.
var cmdPrefix = "!"; //prefix for commands.
var ssi = 0; //set default search provider. Use array index of the array below. (Starting with 0)
// Format: [Keyword, Search URL (Search query replaces "{Q}"), "Input placeholder text"]
var searchSources = [
  ["g",        "https://www.google.com/#q={Q}",                          "Google"],
  ["im",       "https://www.google.com/search?tbm=isch&q={Q}",           "Google Images"],
  ["imdb",     "http://www.imdb.com/find?q={Q}",                         "IMDB"],
  ["pb",       "https://thepiratebay3.org/index.php?q={Q}",              "The Pirate Bay"],
  ["ud",       "http://www.urbandictionary.com/define.php?term={Q}",     "Urban Dictionary"],
  ["wp",       "http://en.wikipedia.org/w/index.php?search={Q}",         "Wikipedia"],
  ["yt",       "https://www.youtube.com/results?search_query={Q}",       "YouTube"],
  ["c",        'https://wiki.syzygy.de/dosearchsite.action?cql=siteSearch+~+"{Q}"',       "Confluence"],
  ["j",        "https://task.syzygy.de/secure/QuickSearch.jspa?searchString={Q}",       "Jira"],
  ["dh",        "https://devhints.io/?q={Q}",       "Devhints"],
  ["so",        "https://stackoverflow.com/search?q={Q}",       "StackOverflow"],
  ["dd",        "https://devdocs.io/#q={Q}",       "Devdocs"]
  
];
 
var svgGoogle = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path d=\"M7 11v2.4h3.97c-.16 1.029-1.2 3.02-3.97 3.02-2.39 0-4.34-1.979-4.34-4.42 0-2.44 1.95-4.42 4.34-4.42 1.36 0 2.27.58 2.79 1.08l1.9-1.83c-1.22-1.14-2.8-1.83-4.69-1.83-3.87 0-7 3.13-7 7s3.13 7 7 7c4.04 0 6.721-2.84 6.721-6.84 0-.46-.051-.81-.111-1.16h-6.61zm0 0 17 2h-3v3h-2v-3h-3v-2h3v-3h2v3h3v2z\" fill-rule=\"evenodd\" clip-rule=\"evenodd\"/></svg>";


/*==================*/
/*== Main Script ==*/
/*================*/

function initSearch() {
  initSearchBar();
  buildHelp();
}

function initSearchBar() {
  if (searchSources[ssi] !== undefined)
    $('#searchBar').attr("placeholder", "Search...");
  else {
    ssi = 0;
    $('#searchBar').attr("placeholder", "Do you know what you're doing?");
    alert("Error: default search engine setting is invalid!");
  }

  document.addEventListener('keydown', function(event) { handleKeydown(event); });

  $('#searchBar').val = "";
}

function buildHelp() {
  var newHelp = "";

  for (var i = 1; i < searchSources.length; i++) {
    newHelp+= "<li><span>" + searchSources[i][0] + "</span> "+ searchSources[i][2] + "</li>";
  }
  $('#searchHelpMenu').append(newHelp);
}

$( document ).ready(function() {
$( "#searchBar" ).blur(function() {
  $( "#mainContainer" ).removeClass( "input-active" );
});

$( "#searchBar" ).focus(function() {
  $( "#mainContainer" ).addClass( "input-active" );
});
});

function handleQuery(event, query) {
  var key = event.keyCode || event.which;
  if(query !== "") {
    var qlist;
    if (key === 32) {
      // if pressing space AFTER searchSources keyword
      qList = query.split(" ");
      if (qList[0].charAt(0) === cmdPrefix) {
        var keyword = "";
        for (var i = 0; i < searchSources.length; i++) {
          keyword = cmdPrefix + searchSources[i][0];
          if (keyword === qList[0]) {
            ssi = i;
            $('#searchBar').attr("placeholder", searchSources[ssi][2]);
            $('#searchBar').val(query.replace(keyword, "").trim());
            event.preventDefault();
            break;
          }
        }
      }
    } else if (key === 13) {
      // if press ENTER
      qList = query.split(" ");
      if (qList[0].charAt(0) === cmdPrefix) {
        var keyword = "";
        for (var i = 0; i < searchSources.length; i++) {
          keyword = cmdPrefix + searchSources[i][0];
          if (keyword === qList[0]) {
            ssi = i;
            break;
          }
        }
        if (qList.length > 1) {
          var urlToOpen = searchSources[ssi][1].replace("{Q}", encodeURIComponent(query.replace(keyword, ""))).trim();
		      window.open(urlToOpen, '_blank');
        } else {
          $('#searchBar').attr("placeholder", searchSources[ssi][2]);
          $('#searchBar').val("");
        }
      } else {
        var urlToOpen2 = searchSources[ssi][1].replace("{Q}", encodeURIComponent(query));
        window.open(urlToOpen2, '_blank');
      }
    }
  }
  if (key === 27) {
    $('#searchBar').blur();
  }
}

var ignoredKeys = [9,13,16,17,18,19,20,27,33,34,35,36,37,38,39,40,45,46,91,92,93,112,113,114,115,116,117,118,119,120,121,122,123,144,145];
function handleKeydown(event) {
  // used to include.. notesInput === document.activeElement ||
  if ($('#searchBar').hasClass('active') || ignoredKeys.includes(event.keyCode)) {
    return;
  }

  $('#searchBar').focus();
}

// Jira Ticker
$(document).ready(function() {

initSearch();
loadEdits();

OverlayScrollbars(document.querySelectorAll("body"), {
    className: "os-theme-light",
    resize: "vertical",
    sizeAutoCapable: false
});

fetch('/jira', {
    method: 'POST',
    credentials: 'include',
    headers: new Headers({
        'Content-Type': 'application/json'
    })
})
.then(response => response.json())
.then(data => {
    let issues = data.issues

    // append li for each issue
    Object.keys(issues).forEach(issue => {
        $('#jiraTicker').append(`<li><a href="https://task.syzygy.de/browse/${issue}">${issue}</a> - ${issues[issue]}</li>`)
    })

    // initialize Ticker
    $('#jiraTicker').webTicker({
        height: '30px',
        duplicate: true,
        rssfrequency: 0,
        startEmpty: false,
        hoverpause: false
    });
})
.catch(error => console.error(error))

});

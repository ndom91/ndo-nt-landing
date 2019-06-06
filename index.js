// With ES5
var JiraApi = require('jira-client');
 
// Initialize
var jira = new JiraApi({
  protocol: 'https',
  host: 'task.syzygy.de',
  username: 'nico.domino',
  password: 'Miney91*',
  apiVersion: '2',
  strictSSL: true
});

const issueNumber = 'FRDBWT-707'
const userName = 'nico.domino'

// jira.findIssue(issueNumber)
jira.getUsersIssues(userName)
  .then(issue => {
    console.log(`Status: ${issue.fields.status.name}`);
  })
  .catch(err => {
    console.error(err);
  });




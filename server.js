const express = require('express')
const helmet = require('helmet')
const bodyParser = require('body-parser')
const moment = require('moment')
const { DateTime } = require('luxon')

const app = express()

app.use(express.static(__dirname + '/'))
app.use(helmet())
app.use(bodyParser.json())
app.use(bodyParser.urlencoded({extended: false}))

app.get('/', (req, res) => {
    // res.redirect('/index.html')
    res.sendFile(__dirname + '/index.html')
})

app.post('/jira', (req, res) => {
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
    })

    const issueNumber = 'FRDBWT-707'

    jira.findIssue(issueNumber)
    .then(issue => {
        console.log(`Status: ${issue.fields.status.name}`)
        return res.status(202).send(issue)
    })
    .catch(err => {
        console.error(err)
        return res.status(403).send(err)
    })
})

port = 7557
app.listen(port, () => {
    console.log(`Server running on http://localhost:${port}`)
})

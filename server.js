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
    res.sendFile(__dirname + '/index.html')
})

app.post('/jira', (req, res) => {
    var JiraApi = require('jira-client');
    
    var jira = new JiraApi({
    protocol: 'https',
    host: 'task.syzygy.de',
    username: 'nico.domino',
    password: 'Miney91*',
    apiVersion: '2',
    strictSSL: true
    })

    const userName = 'nico.domino'
    // let issueDesc = []
    let issuePromises = []
    let issueKeyResp = []

    jiraGetIssue = (issueKeys) => {

        issueKeyResp = issueKeys.forEach(key => {
            let issueDetail = jira.findIssue(key)

            issueDetail.then((resp) => {
                return new Promise((resolve) => {
                    console.log(resp.fields)
                    issDesc = issue + ' - ' + resp.fields.summary
                    // return issDesc
                    // return res.fields.description
                    console.log(JSON.stringify(issDesc))
                    // const issResponse = JSON.stringify(issDesc)
                    // return res.status(202).send(issResponse)

                })
            })
            // .then(function(data) {
            //     return Promise.all(issues2.map(data))
            // })
        })

    }

    // issueKeyResp.all (?)
    let results2 = ''

    jira.getUsersIssues(userName)
    .then(issue => {
        // atlassian API response
        // obj containing ALL issues info
        const issueObj = issue.issues
        let issueArr = []

        // pushing all issue keys from the obj to an array of keys
        issueObj.forEach(iss => {
            issueArr.push(iss.key)
        })

        // looping over issue key array to get
        // the issue details based on issue id (key)
        console.dir(issueArr)
        jiraGetIssue(issueArr)
        // for(i = 0; i < issueArr.length; i++) {
        //     issuePromises.forEach(jiraGetIssue(issueArr[i]))
        // }
    })
    .then(function(data){
        return Promise.all(results2.map(data))
    })
    .catch(err => {
        console.error(err)
        return res.status(403).send(err)
    })

    let results = Promise.all(issuePromises) 
    results.then(data => 
        console.log(data)
    )

    console.log('r2: ' + results2)

    console.log('iP', issuePromises)
    Promise.all(issuePromises)
        .then((result) => {
            // console.log('result', result)
            // console.log('issueDesc', issueDesc)
            // const issueResp = JSON.stringify(issueDesc)
            // console.log('issueResp', issueResp)
            // return res.status(202).send(issueResp)
        })

})

port = 7557
app.listen(port, () => {
    console.log(`Server running on http://localhost:${port}`)
})

const express = require('express')
const helmet = require('helmet')
const bodyParser = require('body-parser')
const dotenv = require('dotenv')

const app = express()
dotenv.config()

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
    host: process.env.JIRA_URL,
    username: process.env.JIRA_USER,
    password: process.env.JIRA_PW,
    apiVersion: '2',
    strictSSL: true
    })

    issueDeetFn = (key) => {
        let issueDetail = jira.findIssue(key)
        return issueDetail
    }

    jira.getUsersIssues(process.env.JIRA_USER)
    .then(issue => {
        const issueObj = issue.issues
        let outputObj = {}
        let outputObj2 = {}
        let issueArr = []

        issueObj.forEach(iss => {
            issueArr.push(iss.key)
        })

        let omgzPromises = issueArr.map(issueDeetFn)
        let omgzResults = Promise.all(omgzPromises)

        omgzResults.then(data => {
            for(i = 0; i < data.length; i++) {
                const issueKey = data[i].key
                const issueSummary = data[i].fields.summary

                outputObj[issueKey] = issueSummary
            }
            outputObj2['issues'] = outputObj

            const outputJSON = JSON.stringify(outputObj2)
            return res.status(202).send(outputJSON)
        })
    })
    .catch(err => {
        console.error(err)
        return res.status(403).send(err)
    })

})

port = process.env.PORT || 7557
app.listen(port, () => {
    console.log(`Server running on http://localhost:${port}`)
})

const express = require('express')
const helmet = require('helmet')
const bodyParser = require('body-parser')
const dotenv = require('dotenv')

const app = express()
const config = dotenv.config({ path: __dirname + '/.env' })

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

    getIssueDetails = (key) => {
        let issueDetails = jira.findIssue(key)
        return issueDetails
    }

    jira.getUsersIssues(process.env.JIRA_USER)
    .then(issue => {
        const issueObj = issue.issues
        let rawOutput = {}
        let filteredOutput = {}
        let issueArr = []

        issueObj.forEach(issue => {
            issueArr.push(issue.key)
        })

        let issuePromise = issueArr.map(getIssueDetails)
        let resolvedIssuePromise = Promise.all(issuePromise)

        resolvedIssuePromise.then(data => {
            for (const key of Object.keys(data)) {
                const status = data[key].fields.status.id
                const name = data[key].fields.status.name
                const description = data[key].fields.issuetype.description
                if(status !== '6' && status !== '10001'){
                    const issueKey = data[key].key
                    const issueSummary = data[key].fields.summary
                    rawOutput[issueKey] = issueSummary
                }
            }
            filteredOutput['issues'] = rawOutput

            const outputJSON = JSON.stringify(filteredOutput)
            return res.status(202).send(outputJSON)
        })
    })
    .catch(err => {
        console.error('Error: ', err)
        return res.status(403).send(err)
    })

})

port = process.env.PORT || 7557
app.listen(port, () => {
    console.log(`Server running on http://localhost:${port}`)
})

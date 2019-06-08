# Landing - Syzygy

:pushpin: [**syzygy.ndo.dev**](https://syzygy.ndo.dev)  

Default page for **ndomino** @ **Syzygy**

## Features

- [x] Grid based favorites launcher  
- [x] Editable URL block  
- [ ] Integrated Jira Issues Ticker   

## Installation

1. Clone this Repo `git clone https://git.newtelco.dev/ndomino/landing-syzygy` 
2. Install the dependencies `npm install` 
3. Run the server 
4. **Development**  
4a. Run: `npm run start:dev`  
4b. Visit: `localhost:7557`  
5. **Production**  
5a. Run: `if [ ! -f /usr/local/bin/pm2 ] ; then npm i -g pm2 ; fi && pm2 start server.js --name "Syzygy Landing"`  
5b. Use [**nginx**](https://nginx.org/en/docs/) to reverse proxy out `localhost:7557`  

## Contributing

1. Clone this Repo `git clone https://git.newtelco.dev/ndomino/landing-syzygy`  
2. Install Dependencies `npm install`  
3. Create a branch `git checkout -b [BRANCH_NAME]`  
4. Run dev server `npm run start:dev` available @ [localhost:7557](http://localhost:7557)  
    **Do work!** :computer: :briefcase: :office: :money_with_wings: :tada:   
6. Push your changes back up `git commit -am "[COMMIT_MESSAGE]"` & `git push origin [BRANCH_NAME]`  
7. Make a merge/pull request here on [**Gitlab**](https://git.newtelco.dev/ndomino/landing-syzygy/merge_requests/new)!  

---  

<sub>License [AGLPv3](https://www.gnu.org/licenses/agpl-3.0.en.html)</sup>
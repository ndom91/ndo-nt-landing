# Landing - Syzygy

[**syzygy.ndo.dev**](https://syzygy.ndo.dev) | Default page for ndomino use @ Syzygy 

### Features

- Grid based favorites launcher  
- Editable URL block  
- Integrated Jira Issues Ticker   

### Installation

1. Clone this Repo `git clone https://git.newtelco.dev/ndomino/landing-syzygy`  
2. Install the dependencies `npm install`  
3. Run the server  

4. **Development Env**  
4a. Run: `npm run start:dev`  
4b. Visit: `localhost:7557`  

5. **Production**  
5a. Run: `if [ ! -f /usr/local/bin/pm2 ] ; then npm i -g pm2 ; fi && pm2 start server.js --name "Syzygy Landing"`  
5b. Use **nginx** to reverse proxy out `localhost:7557`  

### Contributing

1. Clone this Repo `git clone https://git.newtelco.dev/ndomino/landing-syzygy`  
2. Install Dependencies `npm install`  
3. Run dev server `npm run start:dev`  
4. Create a branch `git checkout -b [BRANCH_NAME]`  
5. `Do d3v30pm3n7!`  
6. Push it back up `git commit -am "[COMMIT_MESSAGE]" & `git push origin [BRANCH_NAME]`  
7. Make a pull request on this gitlab server!  

**License [AGLPv3](https://www.gnu.org/licenses/agpl-3.0.en.html)**
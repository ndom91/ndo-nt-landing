# Landing - Syzygy

<img align="right" src="https://drone.ndo.dev/api/badges/ndom91/syzygy-landing/status.svg">
<img align="right" src="https://badges.greenkeeper.io/ndom91/syzygy-landing.svg?token=ac1a3669b7e5935a460f6dad9c9ed8f7ce0c76127d5502447f71a4c38789d3ea&ts=1566405890216">

:pushpin: [**syzygy.ndo.dev**](https://syzygy.ndo.dev)  

![homepage screenshot](https://imgur.com/dVdi4C0.png)

## Features

- [x] Grid based favorites launcher  
- [x] Editable URL block  
- [x] Integrated Jira Issues Ticker   
- [x] Portable config file  
- [x] Hashbang search for multiple providers

Search Providers Hashbangs include:

- **!g** - google
- **!im** - google images
- **!imdb** - imdb
- **!wp** - wikipedia
- **!yt** - youtube
- **!c** - syzygy confluence
- **!j** - syzygy jira

## Requirements  

- Jira Account for open ticket ticker  

## Installation

1. Clone this Repo `git clone https://git.newtelco.dev/ndomino/landing-syzygy` 
2. Install the dependencies `npm install` 
3. Make a copy of `.env.template` like so `cp .env.template .env`
4. Add your Jira details, etc. to your new `.env`
5. Run the server 
6. **Development**  
6a. Run: `npm run start:dev`  
6b. Visit: `localhost:7557`  
7. **Production**  
7a. Run: `if [ ! -f /usr/local/bin/pm2 ] ; then npm i -g pm2 ; fi && pm2 start server.js --name "Syzygy Landing"`  
7b. Use [**nginx**](https://nginx.org/en/docs/) to reverse proxy out `localhost:7557`  

## Contributing

1. Clone this Repo `git clone https://git.newtelco.dev/ndomino/landing-syzygy`  
2. Install Dependencies `npm install`  
3. Create a branch `git checkout -b [BRANCH_NAME]`  
4. Run dev server `npm run start:dev` available @ [localhost:7557](http://localhost:7557)  

---  

<sub>License [MIT](https://opensource.org/licenses/MIT)</sup>

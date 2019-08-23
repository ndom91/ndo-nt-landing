# Landing - Newtelco

<img align="right" src="https://drone.ndo.dev/api/badges/ndom91/ndo-nt-landing/status.svg">

🌍 [**nt.ndo.dev**](https://nt.ndo.dev)  

![homepage screenshot](https://imgur.com/dVdi4C0.png)

## 🎁 Features

- [x] Grid based favorites launcher  
- [x] Editable URL block  
- [x] Integrated Trello Card Info
- [x] Portable config file  
- [x] Hashbang search for multiple providers

Search Providers Hashbangs include:

- `!g` - **google**
- `!im` - **google images**
- `!imdb` - **imdb**
- `!wp` - **wikipedia**
- `!yt` - **youtube**
- `!t` - **trello**
- `!dh` - **devhints.io**
- `!dd` - **devdocs**
- `!so` - **stack overflow**

## 🔨 Requirements  

- [x] Trello account for open card ticker  

## 🏗️ Installation

1. Clone this Repo `git clone https://github.com/ndom91/private-landing-newtelco` 
2. Install the dependencies `npm install` 
3. Make a copy of `.env.template` like so `cp .env.template .env`
4. Add your Jira details, etc. to your new `.env`
5. Run the server 
6. **Development**  
6a. Run: `npm run start:dev`  
6b. Visit: `localhost:7557`  
7. **Production**  
7a. Run: `if [ ! -f /usr/local/bin/pm2 ] ; then npm i -g pm2 ; fi && pm2 start server.js --name "Company Landing"`  
7b. Use [**nginx**](https://nginx.org/en/docs/) to reverse proxy out `localhost:7557`  

## 🙏 Contributing

1. Clone this Repo `git clone https://github.com/ndom91/private-landing-newtelco`  
2. Install Dependencies `npm install`  
3. Create a branch `git checkout -b [BRANCH_NAME]`  
4. Run dev server `npm run start:dev` available @ [localhost:7557](http://localhost:7557)  

---  
📝 License [`MIT`](https://opensource.org/licenses/MIT)

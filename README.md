# Joe's Hangman API

This project has been completely done by Josep Jesus Bigorra Algaba. It consists of an API made with Laravel and a frontend made with Vue.js.
The initial goals have been achieved and surpassed. The backend and frontend are loosely coupled for ease of development and maximum uptime if this were to go to production.
In order to run the API in a production environment please place it in a server like Apache or Nginx. Since this is a development showcase I have been using it with `php artisan serve --host 192.168.178.68`.
The API by default runs in the `:8000` port.
The frontend includes a file named `src/main.js` where you can easily change the variables according to the current development environment.
The database used for the project was SQLite for its ease of use and portability, although this could easily be scaled to MySQL or other relational systems that require a dedicated server.
For this purpose you can find the SQL language file that describes the DB structure, and a `database.sqlite` file is already present in the `database` folder of the backend.
 

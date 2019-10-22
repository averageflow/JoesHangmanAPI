# Joe's Hangman API

This project has been completely done by Josep Jesus Bigorra Algaba. It consists of an API made with Laravel and a frontend made with Vue.js.

![Hangman Showcase 1](https://raw.githubusercontent.com/averageflow/Joe-s-Hangman-API/master/screenshots/1.png)

The initial goals have been achieved and surpassed. The backend and frontend are loosely coupled for ease of development and maximum uptime if this were to go to production.

In order to run the API in a production environment please place it in a server like Apache or Nginx. Since this is a development showcase I have been using it with `php artisan serve --host 192.168.178.68`.

The API by default runs in the `:8000` port.

The frontend includes a file named `src/main.js` where you can easily change the variables according to the current development environment.

The frontend can be run by executing `npm run serve` in the `frontend` folder, after setting the correct variables for the IP and port in `src/main.js`. It can also be built into a production package with webpack by using `npm run build`.

The database used for the project was SQLite for its ease of use and portability, although this could easily be scaled to MySQL or other relational systems that require a dedicated server.

For this purpose you can find the SQL language file that describes the DB structure, and a `database.sqlite` file is already present in the `database` folder of the backend. There is also a test database in that same folder called `test.sqlite`.

In order to run the Feature and Unit tests on the API you should first run the backend with `php artisan serve --host 192.168.178.68 --env=testing`, obviously replacing by your ip address and then `cd` into the folder of the backend and run the command `./vendor/bin/phpunit`.

Please login to the application by registering a new account or by using the provided details in the login page. Also make sure to allow cookies in your browser for the best functionality.

![Hangman Showcase 2](https://raw.githubusercontent.com/averageflow/Joe-s-Hangman-API/master/screenshots/2.png)

It is possible to expand the game by adding new words into the different languages. Keep in mind by changing the display language you also change the words available.

![Hangman Showcase 3](https://raw.githubusercontent.com/averageflow/Joe-s-Hangman-API/master/screenshots/3.png)

Any contribution, suggestion or security flaw should be communicated to me via email to `jjba@protonmail.ch`.

Enjoy and learn some Laravel and Vue.js.

 

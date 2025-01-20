### Setup project

1. install all library 

    ```bash
    $ composer install
    ```

2. edit .env file

3. create table with migration

    ```bash
    $ php artisan migrate
    ```

4. insert data with seeder

    ```bash
    $ php artisan db:seed
    ```

5. run the app

    ```bash
    $ php artisan serve
    ```
    *open http://localhost:8000
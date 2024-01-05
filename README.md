_Так как я долгое время работаю с фреймворком **Laravel**, то я решил отказаться от процедурного PHP в решении данного тестового
задания и написал свой микрофреймворк

После запуска проекта обязательно необходимо выполнить команду:

````
composer dump-autoload
````

 -- чтобы сделать автозагрузку классов

### Команды:
Данный проект работает с SQLite
БД хранится в директории `database`

БД пустая, необходимо после установки проекта запустить миграцию

````
php make/migrate.php
````
Если нужна дополнительная миграция, используйте команду:

````
php make/migration.php create_<table_name>_table
````

В директории `database/migrations` будет создана миграция.

В нее нужно добавить код для создания таблицы в БД

Для запуска проекта используется **Docker** и стандартная команда:

````
docker-compose up -d
````
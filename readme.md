RotorCMS 5.0
=========

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/visavi/rotorcms?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Latest Stable Version](https://poser.pugx.org/visavi/rotorcms/v/stable)](https://packagist.org/packages/visavi/rotorcms)
[![Total Downloads](https://poser.pugx.org/visavi/rotorcms/downloads)](https://packagist.org/packages/visavi/rotorcms)
[![Latest Unstable Version](https://poser.pugx.org/visavi/rotorcms/v/unstable)](https://packagist.org/packages/visavi/rotorcms)
[![License](https://poser.pugx.org/visavi/rotorcms/license)](https://packagist.org/packages/visavi/rotorcms)
[![Build Status](https://travis-ci.org/visavi/rotorcms.svg)](https://travis-ci.org/visavi/rotorcms)
[![Dependency Status](https://www.versioneye.com/user/projects/573cc369ce8d0e00360bcf3e/badge.svg)](https://www.versioneye.com/user/projects/573cc369ce8d0e00360bcf3e)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6e564370-eb46-45ee-923d-4be20c5df1be/mini.png)](https://insight.sensiolabs.com/projects/6e564370-eb46-45ee-923d-4be20c5df1be)

**RotorCMS** является гибкой, мощной и интуитивно понятной системой с минимальными требованиями к хостингу, высоким уровнем защиты и является превосходным выбором для построения сайта любой степени сложности

Главной особенностью RotorCMS является низкая нагрузка на системные ресурсы и высокая скорость работы, даже при очень большой аудитории сайта нагрузка на сервер будет минимальной, и вы не будете испытывать каких-либо проблем с отображением информации.

###Действия при первой установке движка RotorCMS

1. Настройте сайт так чтобы `public` был корневой директорией

2. Установите и настройте менеджер зависимостей [Composer](https://getcomposer.org).

3. Перейдите в директорию с сайтом и установите rotorcms выполнив команду в консоли `composer create-project visavi/rotorcms .`

4. Настройте конфигурационный файл .env, окружение, данные для доступа к БД, логин и email администратора и данные для отправки писем, sendmail или smtp. Если устанавливаете CMS вручную, то переименуйте конфигурационный файл .env.example в .env (Файл не отслеживается git'ом, поэтому на сервере и на локальном сайте могут находиться 2 разных файла с разными окружениями указанными в APP_ENV)

5. Создайте базу данных и пользователя для нее из панели управления на вашем сервере, во время установки скрипта необходимо будет вписать эти данные для соединения в файл .env

6. Установите права на запись всем директория внутри `public/uploads` и `app/storage`

7. Выполните миграции с помощью консольной команды `php rotor migrate`

8. Выполните заполнение БД с помощью команды `php rotor seed:run`

После завершения установки вы сможете посмотреть работу скрипта на главной странице вашего сайта

### Требования

Минимальная версия PHP необходимая для работы движка PHP 5.5.9 и MySQL 5.5

Если MySQL версия ниже 5.6, индексы необходимые для полнотектового поиска в БД не будут добавлены в некотрые таблицы. Позже их можно добавить вручную

### Миграции и заполнение БД

Текущий статус миграции `php rotor status`

Создание миграций `php rotor create CreateTestTable`

Выполнение миграций `php rotor migrate` или `php rotor migrate -t 20110103081132` для отдельной миграции

Откат последней миграции `php rotor rollback` или `php rotor rollback -t 20120103083322` для отдельной миграции

Создание сида `php rotor seed:create UserSeeder`

Выполнение сида `php rotor seed:run` или `php rotor seed:run -s UsersSeeder` для отдельного сида

```html
Пока нет приоритета сидов, запустите сперва отдельный сид для пользователей написанный выше
```

###Настройки nginx

Чтобы пути обрабатывались правильно необходимо настроить сайт

В секцию server добавить следующую запись: `rewrite ^/(.*)/$ /$1 permanent;` необходимую для удаление слешей в конце пути

В секции server -> location необходимо заменить строку

```
try_files $uri $uri/ =404

на

try_files $uri $uri/ /index.php?$query_string;
```
###Настройки apache

Создайте файл .htaccess и пропишите в него следующий код

```
<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(.*)/$ /$1 [L,R=301]
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^ index.php [L]
</IfModule>
```

### License

The RotorCMS is open-sourced software licensed under the [GPL-3.0 license](http://opensource.org/licenses/GPL-3.0)

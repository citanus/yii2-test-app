Simple messenger application
================================


SETTING UP
-------------------

Clone this repo and configure db access:

~~~
vim ./config/db.php
~~~

update repo by composer

~~~
composer update
~~~

and run migrations in repo direcotry root.

~~~
./yii migrate/up
~~~

thats all..


PURPOSE
-------------------

Task: The result must be a web-application that allows registered users to exchange messages.

Aim of the project: To define the candidate's approach to the fulfilment of the set task and dimension of its realization


KNOWN problems/bugs:
 * UX/UI missing at all.
 * pjax has some serious issues when working with form validation based on ajax. Probably some bug in framework,
   resolving of this issue is out of scope.
 * situation when contact recieve message from unknown user is ignored.


MISSING:
 * read/unread messages counter
 * DB optimalization(indexes)
 * class diagram
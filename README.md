alchemy
=======

Fast and clean PHP micro framework to build websites and not only. Alchemy focus to be simple and yet
gives you the way to build your application faster than writing from scratch.
Alchemy mainly focuses to be usefull on handling and processing the requests. The main application
flow is:
- register rewrites in router
- build the http request
- pass request to the router
- gently handle request and find out the matching controller
- load it and fire right method
- return Response object

Server requirements
-------------------

- PHP 5.4.x or newer.
- Curl extension on
- PDO with MySQL (to make DB working)

Installation
------------

to whatever you want, but please keep in mind to use right namespace than.
In the repository there are two folders one of them is named "app" here goes your application code.
The other one is named "alchemy" and this is the framework package

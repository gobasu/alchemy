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

In the repository there are two folders one of them is named "app" here goes your application code.
Dir structure should look like this:
- cache
- controller
- model
- view
- *plugins (not required)
- public

Of course you can use totally different structure but you should follow some conventions:
- dirnames must be lower case
- every filename in given dir which contains class and should be loaded dynamically by framework
must have the same name as class and namespace which corresponds to the dirname, eg. Assume we
need to create HelloWorld class which will be one of controllers for our application, we should
end with path similar to this one: /myapp/something/mycontroller/HelloWorld.php, and file body
have to be:

Example Controller
    <?php
    namespace myapp/something/mycontroller;
    class HelloWorld extends /alchemy/app/Controller
    {
    }

The other one is named "alchemy" and this is a framework package.

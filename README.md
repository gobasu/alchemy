Alchemy framework
=================

Fast and clean PHP micro framework to build not only websites. Alchemy focus to be simple and yet
gives you the way to build your application faster than writing from scratch. 

What differs this framework from others:
- It does not trying force on you to use predefined dir structure you may not like or don't want to have.
- It does not mixing framework files with your application files
- Say no to intricate configurations and setups, alchemy requires no configuration

Performance notes
-----------------
Tests were run at machine:
- Core 2 Duo 2.4 GHz
- 8GB RAM
- 120 GB SSD Intel
- PHP 5.4.4 + APC 
- XHProf

All values are average after 10 runs. More results will appear- stay tuned.

**Simple hello world page**

<pre>
+==============+==========+============+
|   Framework  | time[ms] | mem[bytes] |
+==============+==========+============+
|    alchemy   |  11,370  |  286,304   |
+--------------+----------+------------+
|     slim     |  23,125  |  449,280   |
+--------------+----------+------------+
| code igniter |  24,966  |  486,760   |
+--------------+----------+------------+
|     cake     |  818,488 | 2,943,936  |
+--------------+----------+------------+
|    laravel   |  99,838  | 1,385,688  |
+--------------+----------+------------+
</pre>

**Hello world with database handling**

**Page with acl usage**

**Plugin handling**

**Custom errorpage**



List of contents
----------------

**[Setup](#setup)**
- [Server Requirements](#server-requirements)
- [Basics](#basics)
- [Creating bootstrap file](#creating-bootstrap-file)

**[Routing](#routing)**
- [Resource](#resource)
- [Route types](#route-types)
- [Advanced routing](#advanced-routing)

**[Controllers](#controllers)**
- [Tying route to a controller](#tying-route-to-a-controller)
- [Getting route parameters](#getting-route-parameters)

**[Models](#models)**
- [Annotation system](#annotation-system)
- [Example model](#example-model)
- [Setting up database connection](#setting-up-database-connection)
- [Getting item by pk](#getting-item-by-pk)
- [Updating and creating model](#updating-and-creating-model)
- [Simple search API](#simple-search-api)
- [Custom queries](#custom-queries)

**[Views](#views)**

**[Event system](#event-system)**
- [Dispatching a custom event](#dispatching-a-custom-event)
- [Attaching listeners](#attaching-listeners)
- [About listeners](#about-listeners)
- [Framework events](#framework-events)

**[Session](#session)**
- [Using namespace](#using-namespace)
- [Custom session handler](#custom-session-handler)

**[Acl](#acl)**
- [Defining roles](#defining-roles)
- [Assigning roles](#assigning-roles)
- [Removing roles](#removing-roles)
- [Checking user's roles](#checking-users-roles)

**[I18n](#i18n)**
- [Accepting language from client headers](#accepting-language-from-client-headers)
- [Creating language's aliases](#creating-languages-aliases)

**[Image manipulation](#image-manipulation)**

**[Other](#other)**
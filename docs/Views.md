Views
=====

Alchemy for views uses [mustashe](#https://github.com/bobthecow/mustache.php) templating system. With small changes
instead `{{` & `}}` default tags are set to `<%` `%>`. And template dir is default set to `$PATH_TO_APPLICATION_ROOT/view`.
You can simply change it to anything you want by passing argument to `alchemy\ui\View` class' constructor.
More detailed info about mustashe can be found [here](https://github.com/bobthecow/mustache.php/wiki)

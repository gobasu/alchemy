Templating Engine
=====
**Note this is still experimental package**

Alchemy has its own fast templating system inspired by jinja and mustashe. 
It is as fast as smarty 3 but eats less more memory.

Basic usage
------
```php
use alchemy\future\template\renderer\Mixture;
$tpl = new Mixture($dirToTemplates = '$appdir/templates', $dirToCache = sys_get_tmp_dir());
$tpl->render('filename.html', $exampleData = array(
  'hello' => 'World!', 
  'fruits' => array('mango','bannana','orange','plum', 'apple', 'cherry'), 
  'today' => 1361698703, 
  'html' => '<h1>Helo World!</h1>'),
  'float' => 12.12,
  'tree' => array(
    'child_1' => array (
      'child_1_1' => 'This is child 1.1',
      'child_1_2' => 'This is child 1.2'
    ),
    'child_2' => 'This is child 2'
  )
);
```

Setting global date format
-----
```php
use alchemy\future\template\renderer\Mixture;
Mixture::setDateFormat($format = 'Y.m.d');
```

Setting global datetime format
-----
```php
use alchemy\future\template\renderer\Mixture;
Mixture::setDatetimeFormat($format = 'Y.m.d H:i:s');
```

Setting global number format
-----
```php
use alchemy\future\template\renderer\Mixture;
Mixture::setNumberFormat($decimals = 0, $decimalsSeparator = '.', $thousandsSeparator = ',');
```

Setting global currency suffix
----
```php
use alchemy\future\template\renderer\Mixture;
Mixture::setCurrencySuffix($suffix = null);
```

Displaying variables
--------
```html
<!DOCTYPE html>
<html>
  <title>${hello}</title>
</html>
<body>
  ${hello} <!-- outputs variable with escaping -->
  ${today date}<!-- outputs and formats date using global config -->
  ${float number}<!-- formats the number using global config -->
  ${html unescape}<!-- outputs variable without escaping -->
</body>
</html>
```

Looping
-------
```html
<!DOCTYPE html>
<html>
  <title>${hello}</title>
</html>
<body>
  {% each $fruit in $fruits %}
    ${fruit} , <!-- displays fruit name: 'mango','bannana','orange','plum', 'apple', 'cherry', -->
  {% endeach %}
</body>
</html>
```

Ranges
-------
```html
<!DOCTYPE html>
<html>
  <title>${hello}</title>
</html>
<body>
  {% each $item in 1..10}
    ${item} <!-- displays 1 2 3 4 5 6 7 8 9 10 -->
  {% endeach %}
</body>
</html>
```

Conditions
-------
```html
<!DOCTYPE html>
<html>
  <title>${hello}</title>
</html>
<body>
  {% if $hello %}<!-- check whatever $hello exists and evaluates to true -->
    ${hello}
  {% endif %}
  
  {% if $hello is 'World' %} <!-- checks whatever $hello == 'World' -->
    ${hello}
  {% endif %}
  
  {% if $hello is 3 %} <!-- checks whatever $hello == 3 -->
    ${hello}
  {% endif %}
  
  {% if $hello is number} <!-- checks whatever $hello is a number -->
    ${hello}
  {% endif %}
  
  {% if $hello not number} <!-- checks whatever $hello is not a number -->
    ${hello}
  {% endif %}
  <!-- more if expression like: date, credit card, email, url, ip will appear in futuer -->
  
</body>
</html>
```

Loops + conditions
-------
```html
<!DOCTYPE html>
<html>
  <title>${hello}</title>
</html>
<body>
  {% each $fruit in $fruits %}
    ${@index} <!-- displays loop's index starting from 1-->
    ${@key} <!-- displays array's key -->
    ${@value} <!-- displays array's current element -->
    ${@last} <!-- displays true if current element of array is last one otherwise false -->
    ${@first} <!-- displays true if current element of array is first one otherwise false -->
    ${@odd} <!-- displays true if current element is odd otherwise false -->
    ${@even} <!-- displays true if current element is even otherwise false -->
    ${@length} <!-- displays current array's length -->
    
    <!-- checking if element is odd, you can use here: odd, even, last, first -->
    {% if $fruit is odd %}
      This element is odd
    {% endif %}
    <!-- otherway to do the same -->
    {% if @odd %}
      This element is odd
    {% endif %}
  {% endeach %}
</body>
</html>
```
Nested data
-------

```html
<!DOCTYPE html>
<html>
  <title>${hello}</title>
</html>
<body>
  {% use $tree %}<!-- changes variable scope to $tree -->
    ${child_2} <!-- displays "This is child 2" -->
    {% use $child_1 %}
      ${child_1_1} ${child_1_2}
    {% enduse %}
    
    {% use $undefined %}
      <!-- this will never be displayed -->
    {% enduse %}
  {% enduse %}
</body>
</html>
```

Importing template
-------

```html
<!DOCTYPE html>
<html>
  <title>${hello}</title>
</html>
<body>
  {% import 'my_awesome.html' %} <!-- will import and evalueate as a template my_awesome.html file -->
</body>
</html>
```

Parse ignore
-------
```html
<!DOCTYPE html>
<html>
  <title>${hello}</title>
</html>
<body>
  <script type="text/javascript">
  //{! this will not be parsed as template but outputed instead
  
  //!}
  </script>
</body>
</html>
```

Extending
-------
`parent.html`
```html
<!DOCTYPE html>
<html>
  <title>${hello}</title>
</html>
<body>
  {% block body %}
  {% endblock %}
</body>
</html>
```

`child.html`
```html
{% extend "parent.html" %}
{% block body %}
  This is sample extension
{% endblock %}
```

Will output:

```html
<!DOCTYPE html>
<html>
  <title>World!</title>
</html>
<body>
  This is sample extension
</body>
</html>
```

Defining Helpers
-----
To create custom helpers simply use `Mixture::addHelper($helper, $callable)`, eg:
```php
$helperName = 'pre';
$callable = function(){
    echo '<pre>';
    print_r(func_get_args());
    echo '</pre>';
}
alchemy\future\template\renderer\Mixture::addHelper($helperName, $callable);
```

Using helpers in your template
----
To use your custom helper just put the name of defined helper between `{%` and `%}` tags. You can also
pass parameters to your helper function, example below:
```html
{% pre $param1 $param2 'String' 12 %}
```
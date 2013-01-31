Models
======

Alchemy has its own approach to models. Generally speaking alchemy's model is a mixin of entity and collection,
therefore collection methods like aggregating data should be defined as class methods but entity's methods should
be defined as a object's methods in the same class.

For every model's class framework dynamically creates schema class based on annotations used in model.

Annotation system
-----------------
Annotations provide data about a program that is not part of the program itself.
They have no direct effect on the operation of the code they annotate.

Model annotations are split into two types:
- class's annotations
  - `@Connection` (not required, defaul value is 'default')
  - `@Collection` (required)
  - `@Pk` (required)
- property's annotations
  - `@Param` (required)
  - `@Validator` (not required)

**`@Connection`**

Tells which connection should be used in given model class, e.g.

    @Connection my_connection

Default value is: `default`

**`@Collection`**

If you are using MySQL or other PDO based connection class this means which table should be used
for persisting your data, e.g.

    @Collection SQLTableName

This field is required and has no default value

**`@Pk`**

This tells which field should be used in get, save, delete operations, e.g.

    @Pk Id

This field is required and has no default value

**`@Param`**

Used to inform schema that this field is integral part of data used by connection class.
You need to setup this annotation on object's propery.
This annotation has two attributes:
- `type` tells what data type property holds (avaible: number, string, data, boolean, blob, enum)
- `required` tells whatever the value must be set or not

Example:

    @Param(type=string, required=false)

Example model
-------------
Lets assume we have got sql table `product`:
<pre>
+-------------+-------------+-------------+-------+
| productCode | productName | productLine | price |
+-------------+-------------+-------------+-------+
|             table data goes here                |
+-------------------------------------------------+
</pre>

Example database you can grab from [here](http://www.mysqltutorial.org/_sites/mysqltutorial.org/DownloadHandler.ashx?pg=b98d7eb2-72ba-4a90-9d91-d80c81c2e6dc&section=66d3abc9-1984-4ae0-96e1-ab685cce002c&file=sampledatabase.zip)

Our model will look similar to:
```php
<?php
namespace app\model;
use alchemy\storage\db\Model;
/**
 * Simple product model
 * Below we will set required annotations
 * Our sql table is 'product' so @Collection must
 * be set to 'produt' and @Pk to 'productCode'
 *
 * We will not set any @Connection here we just use
 * default one
 *
 * @Collection products
 * @Pk productCode
 */

class Product extends Model
{
    /**
     * @Param(type=string)
     */
    protected $productCode;

    /**
     * @Param(type=string)
     */
    protected $productName;

    /**
     * @Param(type=string)
     */
    protected $productLine;

    /**
     * @Param(type=number)
     */
    protected $buyPrice = 0.00;
}
```

Setting up database connection
------------------------------

To setup connection to MySQL database you should use `alchemy\storage\DB` class and proper connection
class in your bootstrap file (or configuration you you've created one). Example below:

```php
<?php
require_once $VALID_PATH_TO . '/alchemy/app/Application.php';
use alchemy\app\Application;
use alchemy\storage\DB;
use alchemy\storage\db\connection\MySQL;

//setup connection here
DB::add(new MySQL('{host}','{username}','{password}','{dbname}'), $connectionName = DB::DEFAULT_NAME);

$app = new Application($PATH_TO_APPLICATION_ROOT);
//add routes here...
$app->run();
```

`$connectionName` allows you to use in your application multiple connections in one application.

Supported Databases
------------------

  - MySQL ($host, $username, $password, $dbname)
  - SQLite ($dbpath)


Getting item by pk
------------------

To simply get item from storage by pk just use `Model::get()` function, e.g.

```php
$item = app\model\Product::get('{id here}');
// now if item exists you will get instance of a app\model\Product

```

Updating and creating model
---------------------------

To update or create item in database use `Model->save` method,
Updating record example below:
```php
$item = app\model\Product::get('{id here}');
try {
    $item->productLine = 'Motorcycles';
    $item->save();
} catch (\Exception $e) {
    //handle error when object is not saved
}
```

When creating new item in database make sure you have set in mysql
autoincrement and primary key on field marked as @Pk, otherwise you have
to provide a pk value, e.g.

```php
$item = new app\model\Product('AP-123');
$item->productLine = 'Motorcycles';
$item->productName = 'HB-123';
$item->buyPrice = 1120.12;

try {
    $item->save();
} catch (\Exception $e) {
  //handle error when object is not saved
}
```

Simple SQL search API
---------------------

Alchemy provides simple search api through `Model::find()` and `Model::findOne()`
class' methods.
All you need to do is to put the query array, where array's key is the fieldName and
value is the searched value in DB. *Framework supports only simple search queries which
means all query terms must be met*

Let's assume we want to find all products in `product` table where `productLine` = `Motorcycles`

```php
$collection = \app\model\Product::find(array('productLine' => 'Motorcycles'));
echo $collection[0]->productName;//will display the first item product name
```

Of course you can also use `>` `<` `>=` `<=` operators in your query as well as array value to match
one of the predefined values, e.g.

```php
$collection = Product::find(array(
    'productLine' => array('Trucks and Buses', 'Planes'),
    'buyPrice <=' => 31
));
```

**Sorting example**
If you need to sort your simple search query you have to pass the second argument
to the `Model::findOne` or `Model::find` function, e.g

```php
$collection = \app\model\Product::find(array('productLine' => 'Motorcycles'), array('buyPrice' => 1);
echo $collection[0]->productName;//will display the first item product name
```

The query tells find all records in table `products` where `productLine => 'Motorcycles'` and sort
results in ascending order by column `buyPrice`

Modifying a group of models
---------------------------

`Model::findAndModify(array $query = null, array $update, $returnData = false)`

  - `$query` simple search query term (may stay empty to update all set)
  - `$update` array with data what should be updated key=>value pairs
  - `$returnData` tells whatever modified models should be returned as a result

Increasing/descreasing is also avaible by prepending field name in `$update` array with +/i e.g.
`Model::findAndModify(null, array('+a', 1));`

Will increase field `a` in all models by one

**Removing group of records**

`Model::findAndRemove(array $query, $returnData = false)`

  - `$query` simple search query term (may stay empty to update all set)
  - `$returnData` tells whatever removed data should be returned as model list

Will remove all records in database matching `$query` and

Custom queries
--------------
MySQL connection custom query example
```php
<?php
namespace app\model;
use alchemy\storage\db\Model;
/**
 * Product
 *
 * @Connection default
 * @Collection products
 * @Pk productCode
 */

class Product extends Model
{
    /**
     * Our custom query which will search for all products from
     * motorcycle's line
     */
    public static function getMotorcycles()
    {
        $schema = self::getSchema();
        $fieldList = '`' . implode('`,`', $schema->getPropertyList()) . '`';
        $sql = 'SELECT ' . $fieldList . '
            FROM ' . $schema->getCollectionName() . '
            WHERE productLine = "Motorcycles"';

        return self::query($sql, $schema);
    }

    public static function removeMotorcycles()
    {
        $schema = self::getSchema();
        self::query('DELETE FROM ' . $schema->getCollectionName() . ' WHERE productLine = "Motorcycles"');
    }

    /**
     * @Param(type=number, required=true)
     */
    protected $productCode;

    /**
     * @Param(type=string, required=false)
     */
    protected $productName;

    /**
     * @Param(type=date)
     */
    protected $productLine;

    /**
     * @Param(type=number)
     */
    protected $buyPrice = 0.00;
}
```

As you may noticed we've used here method `self::getSchema()`. This function returns generated schema object of database's table
for more please see the [`alchemy\storage\db\ISchema`](https://github.com/dkraczkowski/alchemy/blob/master/alchemy/storage/db/ISchema.php).

<?php
namespace alchemy\storage\db\connection;

class SQLiteException extends SQLException {}

/**
 * SQLite Connection class
 */

class SQLite extends SQL
{

    public function __construct($fileName = self::USE_MEMORY)
    {
        $dsn = 'sqlite:' . $fileName;
        parent::__construct($dsn);
    }

    CONST USE_MEMORY = ':memory:';
}
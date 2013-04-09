<?php
namespace app\model;

use alchemy\storage\db\Model;

class SetupException extends \Exception {}

/**
 * Class Setup
 * @package app\model

 * @collection virtual
 */
class Setup extends Model
{
    /**
     * Builds the structure of recipes book database
     */
    public static function buildDatabase()
    {
        if (!self::query('CREATE TABLE IF NOT EXISTS recipe(
            recipe_id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255),
            description TEXT,
            created_on INTEGER
        )')) {
            throw new SetupException('COULD NOT CREATE TABLE: recipe');
        }

        if (!self::query('CREATE TABLE IF NOT EXISTS ingredient(
            ingredient_id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255)
        )')) {
            throw new SetupException('COULD NOT CREATE TABLE: ingredient');
        }


        if (!self::query('CREATE TABLE IF NOT EXISTS recipe_has_ingredient(
            recipe_has_igrendient_id INTEGER PRIMARY KEY AUTOINCREMENT,
            recipe_id INTEGER REFERENCES recipe(recipe_id) ON DELETE CASCADE,
            ingredient_id INTEGER REFERENCES ingredient(ingredient_id) ON DELETE CASCADE
        )')) {
            throw new SetupException('COULD NOT CREATE TABLE: recipe_has_ingredient');
        }
    }
}
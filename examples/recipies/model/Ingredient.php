<?php
namespace app\model;

use alchemy\storage\db\Model;

/**
 * Class Recipe
 * @package app\model
 * @pk ingredient_id
 * @collection ingredient
 */
class Ingredient extends Model
{
    /**
     * @param(type=number)
     */
    protected $ingredient_id;

    /**
     * @param(type=string)
     */
    protected $title;
}
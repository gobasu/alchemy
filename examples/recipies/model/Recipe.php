<?php
namespace app\model;

use alchemy\storage\db\Model;


/**
 * Class Recipe
 * @package app\model
 * @pk recipe_id
 * @collection recipe
 */
class Recipe extends Model
{

    public static function onLoad()
    {
        $data = self::query('SELECT recipe_id, ingredient_id FROM recipe_has_ingredient');
        if ($data) {
            foreach ($data as $i) {
                if (!isset(self::$recipeHasIngredient[$i['recipe_id']])) {
                    self::$recipeHasIngredient[$i['recipe_id']] = array();
                }
                self::$recipeHasIngredient[$i['recipe_id']][] = $i['ingredient_id'];
            }
        }

        //get all ingredients
        self::$avaibleIngredients = Ingredient::find();
    }

    /**
     * We will attach here ingredients models to recipe
     */
    public function onGet()
    {
        if (isset(self::$recipeHasIngredient[$this->getPK()])) {
            $ingredients = self::$recipeHasIngredient[$this->getPK()];
            foreach ($ingredients as $i) {
                $this->addExistingIngredient(self::$avaibleIngredients[$i]);
            }
        }
    }

    private function addExistingIngredient(Ingredient $ingredient)
    {
        $this->ingredients[$ingredient->getPK()] = $ingredient;
    }

    public function getIngredients()
    {
        return $this->ingredients;
    }

    public function ingredientNamesList()
    {
        $names = array();
        foreach ($this->ingredients as $i)
        {
            $names[] = $i->title;
        }
        return join(',', $names);
    }

    public function saveIngredients($ingredients)
    {
        //find out which are new ingredients
        foreach ($ingredients as &$i) {
            if (!is_numeric($i)) {
                $ingredient = new Ingredient();
                $ingredient->title = $i;
                $ingredient->save();
                $i = $ingredient->getPK();
            }
        }

        //save recipe's ingredients
        foreach ($ingredients as $ingredient) {
            self::query('INSERT INTO recipe_has_ingredient
                VALUES(NULL,:recipe_id,:ingredient_id)',
                array(
                    'recipe_id' => $this->getPK(),
                    'ingredient_id' => $ingredient
                )
            );
        }

    }

    /**
     * @param(type=number)
     */
    protected $recipe_id;

    /**
     * @param(type=string)
     */
    protected $title;

    /**
     * @param(type=string)
     */
    protected $description;

    /**
     * @param(type=number)
     */
    protected $created_on;

    /**
     * @param(type=number)
     */
    protected $updated_on;

    protected $ingredients = array();

    private static $recipeHasIngredient = array();
    private static $avaibleIngredients = array();
}
<?php
App::uses('AppModel', 'Model');

class Category extends AppModel 
{
    public $displayField = 'name';
    
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive et navn.',
            ),
        ),
    );

    public $hasAndBelongsToMany = array(
        'Recipe'
    );

    public function beforeDelete($cascade = true)
    {   

        $category_recipes = $this->CategoriesRecipe->find('all', array('conditions' => array('CategoriesRecipe.category_id' => $this->id)));

        foreach ($category_recipes as $key => $category_recipe) 
        {
            $related_recipe = $this->Recipe->find('first', array('conditions' => array('Recipe.id' => $category_recipe['CategoriesRecipe'], 'Recipe.is_active' => true)));
            if(!empty($related_recipe))
            {
                // Inform the user that this item has relations
                SessionComponent::setFlash(' Denne kategori har stadig relationer til nogle opskrifter. Tryk på info knappen for at se hvad. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
            }
        }
        


    }
}

?>
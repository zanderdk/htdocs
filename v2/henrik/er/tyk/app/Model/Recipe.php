<?php
App::uses('AppModel', 'Model');

class Recipe extends AppModel 
{
	public $displayField = 'name';
    
	public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive et navn.',
            ),
        ),
        'price' => array(
            'decimal' => array(
                'rule' => 'decimal',
                'allowEmpty' => false,
                'message' => 'Du skal angive en pris.',
            ),
            'notNegative' => array(
                'rule'    => array('comparison', '>=', 0),
                'message' => 'En pris kan ikke være negativ.'
            )
        ),
        'intern_product_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive en intern varekode.',
            ),
        ),
        'category' => array(
            'inList' => array(
                'rule'    => array('inList', array('females', 'babies', 'children', 'none')),
                'allowEmpty' => false,
                'message' => 'Du skal vælge kategori til opskriften.'
            ),
        ),
    );

    public $hasAndBelongsToMany = array(
        'Yarn',
    );

    public $hasOne = array(
        'Previous' => array(
            'className' => 'Recipe',
            'foreignKey' => 'previous_id'
        ),
        'Original' => array(
            'className' => 'Recipe',
            'foreignKey' => 'original_id'
        )
    );

    public $belongsTo = array(
        'Parrent' => array(
            'className' => 'Recipe',
            'foreignKey' => 'parrent_id'
        ),
        
    );

    public function afterSave($created, $options = array())
    {   
        // Sets the file to be null if no image was given
        if(empty($this->data['Recipe']['image']))
        {
            $this->data['Recipe']['image'] = null;
        }

        // Sets the file to be null if no pdf was given
        if(empty($this->data['Recipe']['pdf']))
        {
            $this->data['Recipe']['pdf'] = null;
        }

        // Upload the image of the variant
        if(FileComponent::fileGiven($this->data['Recipe']['image']))
        {   
             // Check if the upload went well (The image is limited to 500 x 500 pixels)
            if(!FileComponent::uploadAndResizeImage($this->data['Recipe']['image'], $this->data['Recipe']['id'], 'png', 'recipes', 1000, 1000))
            {   
                // Was this a creation of an item?
                if($created)
                {   
                    // Delete the entry in the database
                    $this->delete($this->data['Recipe']['id']);
                }
                // It did not go well delete the image and inform the user
                SessionComponent::setFlash('Billedet til denne opskrift blev ikke uploadet korrekt. Opskriften blev ikke gemt.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }
        }

        // Upload the image of the variant
        if(FileComponent::fileGiven($this->data['Recipe']['pdf']))
        {
            if(!FileComponent::uploadFile($this->data['Recipe']['pdf'], $this->data['Recipe']['id'], 'pdf', false, 'recipes'))
            {
                if($created)
                {   
                    // Delete the entry in the database
                    $this->delete($this->data['Recipe']['id']);
                }
                // It did not go well delete the image and inform the user
                SessionComponent::setFlash('Pdf-filen til denne opskrift blev ikke uploadet korrekt. Opskriften blev ikke gemt.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }
        }

        // If the item have no original_id then it is the original_id
        if(empty($this->data['Recipe']['original_id']))
        {
            $this->saveField('original_id',$this->data['Recipe']['id']);
        }
    }

    public function edit($data = null)
    {
        // Find the old model
        $old_recipe = $this->find('first', array('conditions' => array('Recipe.id' => $data['Recipe']['id'])));

        // Update data from the old model
        $new_recipe['Recipe']['previous_id'] = $old_recipe['Recipe']['id'];
        $new_recipe['Recipe']['original_id'] = $old_recipe['Recipe']['original_id'];
        $new_recipe['Recipe']['created'] = $old_recipe['Recipe']['created'];

        // Update data from the view
        $new_recipe['Recipe']['name'] = $data['Recipe']['name'];
        $new_recipe['Recipe']['price'] = $data['Recipe']['price'];

        $new_recipe['Recipe']['intern_product_code'] = $data['Recipe']['intern_product_code'];


        // Check if any care_labels was given
        if(!empty($data['Yarn']))
        {
            // TODO fix Recipes such if the yarn is changed, change the relation or delete the relation
            $new_recipe['Yarn'] = $data['Yarn'];    
        }

        if(!empty($data['Category']))
        {
            // TODO fix Recipes such if the yarn is changed, change the relation or delete the relation
            $new_recipe['Category'] = $data['Category'];    
        }

        // If the save went well
        if(!$this->saveAll($new_recipe))
        {
            return false;
        }
        $new_id = $this->id;

        // Upload a new image of the recipe if there is one given
        if(!empty($data['Recipe']['image']) && FileComponent::fileGiven($data['Recipe']['image']))
        {
             // Check if the upload went well (The image is limited to 500 x 500 pixels)
            if(!FileComponent::uploadAndResizeImage($data['Recipe']['image'], $new_id, 'png', 'recipes', 500, 500))
            {   
                // Delete the entry in the database
                $this->delete($new_id);

                // It did not go well delete the image and inform the user
                SessionComponent::setFlash('Billedet til denne opskrift blev ikke uploadet korrekt. Opskriften blev ikke gemt.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }
        }
        else
        {
            FileComponent::copyFile($old_recipe['Recipe']['id'],'png', true, 'recipes', $new_id, 'png', true, 'recipes');
        }

        // Upload a new pdf of the variant if there is one given
        if(!empty($data['Recipe']['image']) && FileComponent::fileGiven($data['Recipe']['image']))
        {
             // Check if the upload went well
            if(!FileComponent::uploadFile($this->data['Recipe']['pdf'], $new_id, 'pdf', false, 'recipes'))
            {   
                // Delete the entry in the database
                $this->delete($new_id);

                // It did not go well delete the image and inform the user
                SessionComponent::setFlash('PDF-filen til denne opskrift blev ikke uploadet korrekt. Opskriften blev ikke gemt.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }
        }
        else
        {
            FileComponent::copyFile($old_recipe['Recipe']['id'],'pdf', false, 'recipes', $new_id, 'pdf', false, 'recipes');
        }

        // Update the parrent of the old model
        $old_recipe['Recipe']['parrent_id'] = $new_id;
        $old_recipe = $this->save($old_recipe);

        // Update the relation before deleting the old version
        $this->updateYarnRelation($old_recipe['Recipe']['id'], $new_id);

        // Set the old version to inactive
        $this->deactivate($old_recipe['Recipe']['id'], true);

        // If the save went well
        if(empty($old_recipe))
        {
            return false;
        }

        // It all went well
        return true;
    }

    // Sets the is_active to 0
    public function deactivate($id = null, $delete_superfluous_associations = false)
    {   
        // Find the requested item
        $recipe = $this->find('first', array('conditions' => array('Recipe.id' => $id)));

        // Does the item exist?
        if(empty($recipe))
        {
            // Tell the user that the requested item did not exist 
            SessionComponent::setFlash(' Denne opskrift findes ikke. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        // Is the item active?
        else if(!$recipe['Recipe']['is_active'])
        {
            // Tell the user that the requested item already is inactive
            SessionComponent::setFlash(' Denne opskrift er allerede inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        // Was the save a failure?
        $this->id = $recipe['Recipe']['id'];

        if(!$this->saveField('is_active', 0))
        {   
            // Tell the user that the is_active property was not changed.
            SessionComponent::setFlash(' Opskriften blev ikke sat som inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        // Delete all associations that are superfluous
        if($delete_superfluous_associations)
        {
            if(!$this->RecipesYarn->deleteAll(array('RecipesYarn.recipe_id' => $id), false))
            {
                // Tell the user that the relations to care_labels was not delted
                SessionComponent::setFlash(' Relationen fra den inaktive opskrift til garnkvaliteterne blev ikke slettet. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            }
        }

        // If the image of this recipe exists_delete it
        if(FileComponent::fileExists($recipe['Recipe']['id'], 'png', true , 'recipes'))
        {
            if(!FileComponent::deleteFile($recipe['Recipe']['id'], 'png', true , 'recipes'))
            {
                // Inform the user that the file is still on the server
                SessionComponent::setFlash('Billedet til dette opskrift blev ikke slettet og ligger stadig på serveren.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }  
        }

        // If the file of this recipe exists_delete it
        if(FileComponent::fileExists($recipe['Recipe']['id'], 'pdf', false , 'recipes'))
        {
            if(!FileComponent::deleteFile($recipe['Recipe']['id'], 'pdf', false , 'recipes'))
            {
                // Inform the user that the file is still on the server
                SessionComponent::setFlash('PDF-filen til dette opskrift blev ikke slettet og ligger stadig på serveren.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }  
        }

        // It went well
        return true;
    }

    public function updateYarnRelation($old_recipe_id, $new_recipe_id)
    {
        // Find the old relations
        $old_relations = $this->RecipesYarn->find('all', array('conditions' => array('RecipesYarn.recipe_id' => $old_recipe_id)));

        foreach ($old_relations as $key => $relation) {
            $this->RecipesYarn->set($relation);
            $this->RecipesYarn->saveField('recipe_id', $new_recipe_id);
        } 
    }
}

?>
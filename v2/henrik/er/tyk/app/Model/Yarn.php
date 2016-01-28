<?php
App::uses('AppModel', 'Model');

class Yarn extends AppModel 
{
	public $displayField = 'name';
    
	public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive et navn.',
            ),
        ),
        'product_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive en varekode.',
            ),
        ),
        'gauge_masks' => array(
            'naturalNumber' => array(
                'rule'    => array('naturalNumber', true),
                'allowEmpty' => false,
                'message' => 'Du skal angive et ikke negativt antal masker.',
            )
        ),
        'gauge_rows' => array(
            'naturalNumber' => array(
                'rule'    => array('naturalNumber', true),
                'allowEmpty' => true,
                'message' => 'Du skal angive et ikke negativt antal rækker.',
            )
        ),
        'needle_min' => array(
            'decimal' => array(
                'rule' => 'decimal',
                'allowEmpty' => false,
                'message' => 'Du skal angive en minimums størrelse for en strikkepind.',
            ),
            'notNegative' => array(
                'rule'    => array('comparison', '>=', 0),
                'message' => 'En minimums størrelse for en strikkepind kan ikke være negativ.'
            )
        ),
        'needle_max' => array(
            'decimal' => array(
                'rule' => 'decimal',
                'allowEmpty' => false,
                'message' => 'Du skal angive en maximums størrelse for en strikkepind.',
            ),
            'notNegative' => array(
                'rule'    => array('comparison', '>=', 0),
                'message' => 'En maximums størrelse for en strikkepind kan ikke være negativ.'
            )
        ),
        'length' => array(
            'decimal' => array(
                'rule' => 'decimal',
                'allowEmpty' => false,
                'message' => 'Du skal angive en løbelængde.',
            ),
            'notNegative' => array(
                'rule'    => array('comparison', '>=', 0),
                'message' => 'En løbelængde kan ikke være negativ.'
            )
        ),
        'weight' => array(
            'decimal' => array(
                'rule' => 'decimal',
                'allowEmpty' => false,
                'message' => 'Du skal angive en vægt.',
            ),
            'notNegative' => array(
                'rule'    => array('comparison', '>=', 0),
                'message' => 'En vægt kan ikke være negativ.'
            )
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
	);

    public $hasOne = array(
        'Previous' => array(
            'className' => 'Yarn',
            'foreignKey' => 'previous_id'
        )
    );

    public $hasMany = array(
        // TODO 
        'YarnPart',
        'YarnVariant' => array(
            'order' => 'YarnVariant.color_code ASC')
    );

    public $belongsTo = array(
        // TODO
        'Menu',
        'Brand',
        'Parrent' => array(
            'className' => 'Yarn',
            'foreignKey' => 'parrent_id'
        ),
        
    );

    public $hasAndBelongsToMany = array(
        'CareLabel',
        'Recipe'
    );

    public function beforeValidate($options = array())
    {    
        // Was yarn_parts given?
        // And check that all parts sums up to 100%
        $total_percentage = 0;
        if(!empty($this->data['YarnPart']))
        {
            
            // Remove yarn_part relations that have no percentage given
            foreach ($this->data['YarnPart'] as $i => $yarn_part) 
            {   
                // Sum all percentages
                $total_percentage += $yarn_part['percentage'];

                if(empty($yarn_part['percentage']))
                {
                    unset($this->data['YarnPart'][$i]);
                }
            }
        }
        // Remove all yarn_batches that have id 0 because that means it was not selected        
        if(!empty($this->data['CareLabel']))
        {
            foreach ($this->data['CareLabel'] as $i => $care_label)
            {   

                if($care_label == 0)
                {          
                    unset($this->data['CareLabel'][$i]);
                }
 
            }
        }

        if($this->data['Yarn']['needle_size_min'] > $this->data['Yarn']['needle_size_max'])
        {
            // Tell the user that the needle_min was larger than needle_max
            SessionComponent::setFlash(' Minimumsværdien for nålestørrelse er større end maximumsværdien for nålestørrelse. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        if($total_percentage != 100)
        {
            // Tell the user the parts chosen for this yarn did not equal 100%
            SessionComponent::setFlash(' Den sammenlagte procentsats for materialer giver ikke 100%. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
    }


    public function edit($data = null)
    {
        // Find the old model
        $old_yarn = $this->find('first', array('conditions' => array('Yarn.id' => $data['Yarn']['id'])));

        // Update data from the old model
        $new_yarn['Yarn']['previous_id'] = $old_yarn['Yarn']['id'];
        $new_yarn['Yarn']['created'] = $old_yarn['Yarn']['created'];
        $new_yarn['Yarn']['product_count'] = $old_yarn['Yarn']['product_count'];

        // Update data from the view
        $new_yarn['Yarn']['name'] = $data['Yarn']['name'];
        $new_yarn['Yarn']['weight'] = $data['Yarn']['weight'];
        $new_yarn['Yarn']['length'] = $data['Yarn']['length'];
        $new_yarn['Yarn']['gauge_masks'] = $data['Yarn']['gauge_masks'];
        $new_yarn['Yarn']['gauge_rows'] = $data['Yarn']['gauge_rows'];
        $new_yarn['Yarn']['needle_size_min'] = $data['Yarn']['needle_size_min'];
        $new_yarn['Yarn']['needle_size_max'] = $data['Yarn']['needle_size_max'];
        $new_yarn['Yarn']['price'] = $data['Yarn']['price'];

        // Udate the belongsTo relations from the view
        $new_yarn['Yarn']['menu_id'] = $data['Yarn']['menu_id'];
        $new_yarn['Yarn']['brand_id'] = $data['Yarn']['brand_id'];
        $new_yarn['YarnPart'] = $data['YarnPart'];

         // Check if any care_labels was given
        if(!empty($data['CareLabel']))
        {
            // TODO fix Recipes such if the yarn is changed, change the relation or delete the relation
            $new_yarn['CareLabel'] = $data['CareLabel'];
        }

        // If the save went well
        if(!$this->saveAll($new_yarn))
        {
            return false;
        }
        $new_id = $this->id;

        // Update relations from the old model
        // Make sure it has relations
        if(!empty($old_yarn['YarnVariant']))
        {   
            // Run through every relation
            foreach ($old_yarn['YarnVariant'] as $i => $yarn_variant)
            {   
                // Check if the yarn_variant is active
                if($yarn_variant['is_active'] == 1)
                {   
                    $this->YarnVariant->set($yarn_variant);
                    $this->YarnVariant->saveField('yarn_id', $new_id);   
                }
            }    
        }

        // Update the parrent of the old model
        $old_yarn['Yarn']['parrent_id'] = $new_id;
        $old_yarn = $this->save($old_yarn);

        // Update the relation before deleting the old version
        $this->updateYarnRelation($old_yarn['Yarn']['id'], $new_id);

        // Set the old version to inactive
        $this->deactivate($old_yarn['Yarn']['id']);

        // If the save went well
        if(empty($old_yarn))
        {
            return false;
        }

        // It all went well
        return true;
    }

    // Sets the is_active to 0
    public function deactivate($id = null)
    {   
        // Find the requested item
        $yarn = $this->find('first', array('conditions' => array('Yarn.id' => $id)));

        // Does the item exist?
        if(empty($yarn))
        {
            // Tell the user that the requested item did not exist 
            SessionComponent::setFlash(' Denne garnkvalitet findes ikke. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        // Is the item active?
        else if(!$yarn['Yarn']['is_active'])
        {
            // Tell the user that the requested item already is inactive
            SessionComponent::setFlash(' Denne garnkvalitet er allerede inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        // Was the save a failure?
        $this->id = $yarn['Yarn']['id'];

        if(!$this->saveField('is_active', 0))
        {   
            // Tell the user that the is_active property was not changed.
            SessionComponent::setFlash(' Garnkvaliteten blev ikke sat som inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        // Deactivate its children
        if(!empty($yarn['YarnVariant']))
        {   
            // Run through every relation
            foreach ($yarn['YarnVariant'] as $i => $yarn_variant)
            {      
                // Check if the yarn_variant is active
                if($yarn_variant['is_active'] == 1)
                {   
                    $this->YarnVariant->deactivate($yarn_variant['id']);    
                }
            }    
        }


        if(!$this->CareLabelsYarn->deleteAll(array('CareLabelsYarn.yarn_id' => $id), false))
        {
            // Tell the user that the relations to care_labels was not delted
            SessionComponent::setFlash(' Relationen fra den inaktive garnkvalitet til vaskemærker blev ikke slettet. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        if(!$this->YarnPart->deleteAll(array('YarnPart.yarn_id' => $id)))
        {
            // Tell the user that the relations to yarn_parts was not delted
            SessionComponent::setFlash(' Relationen fra den inaktive garnkvalitet til dets bestanddele blev ikke slettet. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        if(!$this->RecipesYarn->deleteAll(array('RecipesYarn.yarn_id' => $id), false))
        {
            // Tell the user that the relations to care_labels was not delted
            SessionComponent::setFlash(' Relationen fra den inaktive opskrift til garnkvaliteterne blev ikke slettet. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        

        // It went well
        return true;
    }


    public function updateProductCount($id = null)
    {
        // Find the requested item
        $yarn = $this->find('first', array('conditions' => array('Yarn.id' => $id)));

        // Does the item exist?
        if(empty($yarn))
        {
            // Tell the user that the requested item did not exist 
            SessionComponent::setFlash(' Denne garnkvalitet findes ikke. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        $product_count = 0;
        foreach ($yarn['YarnVariant'] as $key => $yarn_variant) {
            if($yarn_variant['is_active'])
            {
                $product_count += $yarn_variant['product_count'];
            }
        }

        $this->set($yarn);
        $this->saveField('product_count', $product_count);
    }

    public function updateYarnRelation($old_yarn_id, $new_yarn_id)
    {
        
        // Find the old relations
        $old_relations = $this->RecipesYarn->find('all', array('conditions' => array('RecipesYarn.yarn_id' => $old_yarn_id)));

        foreach ($old_relations as $key => $relation) {
            $this->RecipesYarn->id = $relation['RecipesYarn']['id'];
            $this->RecipesYarn->saveField('yarn_id', $new_yarn_id);
        } 
    }
}

?>
<?php
App::uses('AppModel', 'Model');

class Needle extends AppModel 
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

    public $hasOne = array(
        'Previous' => array(
            'className' => 'Needle',
            'foreignKey' => 'previous_id'
        ),
        'Original' => array(
            'className' => 'Needle',
            'foreignKey' => 'original_id'
        )
    );

    public $hasMany = array(
        // TODO 
        'NeedleVariant' => array(
            'order' => 'NeedleVariant.girth ASC')
    );

    public $belongsTo = array(
        // TODO
        'Menu',
        'Brand',
        'Material',
        'Parrent' => array(
            'className' => 'Needle',
            'foreignKey' => 'parrent_id'
        ),
    );

    // TODO 
    // Create a function that "deletes" or "updates" 
        // Set is_active/parrent/previous

    public function afterSave($created, $options = array())
    {   
        // If the item have no original_id then it is the original_id
        if(empty($this->data['Needle']['original_id']))
        {
            $this->saveField('original_id',$this->data['Needle']['id']);
        }
    }

    public function edit($data = null)
    {
        // Find the old model
        $old_needle = $this->find('first', array('conditions' => array('Needle.id' => $data['Needle']['id'])));

        // Update data from the old model
        $new_needle['Needle']['previous_id'] = $old_needle['Needle']['id'];
        $new_needle['Needle']['original_id'] = $old_needle['Needle']['original_id'];
        $new_needle['Needle']['created'] = $old_needle['Needle']['created'];
        $old_needle['Needle']['product_count'] = $old_needle['Needle']['product_count'];

        // Update data from the view
        $new_needle['Needle']['name'] = $data['Needle']['name'];

        // Udate the belongsTo relations from the view
        $new_needle['Needle']['menu_id'] = $data['Needle']['menu_id'];
        $new_needle['Needle']['brand_id'] = $data['Needle']['brand_id'];
        $new_needle['Needle']['material_id'] = $data['Needle']['material_id'];


        // If the save went well
        if(!$this->saveAll($new_needle))
        {
            return false;
        }
        $new_id = $this->id;

        // Update relations from the old model
        // Make sure it has relations
        if(!empty($old_needle['NeedleVariant']))
        {   
            // Run through every relation
            foreach ($old_needle['NeedleVariant'] as $i => $needle_variant)
            {      
                // Check if the needle_variant is active
                if($needle_variant['is_active'] == 1)
                {   
                    $this->NeedleVariant->set($needle_variant);
                    $this->NeedleVariant->saveField('needle_id', $new_id); 
                }
            }    
        }

        // Update the old model
        $this->set($old_needle);
        $this->saveField('parrent_id', $new_id);

        // Set the old version to inactive
        $this->deactivate($old_needle['Needle']['id'], true);

        // If the save went well
        if(empty($old_needle))
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
        $needle = $this->find('first', array('conditions' => array('Needle.id' => $id)));

        // Does the item exist?
        if(empty($needle))
        {
            // Tell the user that the requested item did not exist 
            SessionComponent::setFlash(' Denne strikkepind findes ikke. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        // Is the item active?
        else if(!$needle['Needle']['is_active'])
        {
            // Tell the user that the requested item already is inactive
            SessionComponent::setFlash(' Denne strikkepind er allerede inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        // Was the save a failure?
        $this->id = $needle['Needle']['id'];

        // Was the save a failure?
        if(!$this->saveField('is_active', 0))
        {   
            // Tell the user that the is_active property was not changed.
            SessionComponent::setFlash(' Strikkepinden blev ikke sat som inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        else
        {
            // Deactivate its children
            if(!empty($needle['NeedleVariant']))
            {   
                // Run through every relation
                foreach ($needle['NeedleVariant'] as $i => $needle_variant)
                {      
                    // Check if the yarn_variant is active
                    if($needle_variant['is_active'] == 1)
                    {   
                        $this->NeedleVariant->deactivate($needle_variant['id']);    
                    }
                }    
            }
        }

        // It went well
        return true;
    }

    // TODO if on_sale is true then the previous version of that yarn that is not on sale should be mentioned.

    public function updateProductCount($id = null)
    {
        // Find the requested item
        $needle = $this->find('first', array('conditions' => array('Needle.id' => $id)));

        // Does the item exist?
        if(empty($needle))
        {
            // Tell the user that the requested item did not exist 
            SessionComponent::setFlash(' Denne strikkepind/hæklenål findes ikke. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        // Is the item active?
        else if(!$needle['Needle']['is_active'])
        {
            // Tell the user that the requested item already is inactive
            SessionComponent::setFlash(' Denne strikkepind/hæklenål er inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        $product_count = 0;
        foreach ($needle['NeedleVariant'] as $key => $needle_variant) {
            if($needle_variant['is_active'])
            {
                $product_count ++;
            }
        }

        $this->set($needle);
        $this->saveField('product_count', $product_count);
    }
}

?>
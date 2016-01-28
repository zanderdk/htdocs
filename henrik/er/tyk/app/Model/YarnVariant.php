<?php
App::uses('AppModel', 'Model');
App::import('Component', 'File');

class YarnVariant extends AppModel 
{

	public $displayField = 'id';
    
	public $validate = array(
        'color_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive et navn.',
            ),
        ),
	);

    public $hasOne = array(
        'Previous' => array(
            'className' => 'YarnVariant',
            'foreignKey' => 'previous_id'
        )
    );

    public $hasMany = array(
        // TODO 
        'YarnBatch' => array(
            'order' => 'YarnBatch.stock_quantity DESC')
    );

    public $belongsTo = array(
        // TODO
        'Yarn',
        'Color',
        'Parrent' => array(
            'className' => 'YarnVariant',
            'foreignKey' => 'parrent_id'
        )
    );

    public function beforeSave($options = array())
    {
        // TODO
        // Set the display_priority
    }

    public function afterSave($created, $options = array())
    {
        // Sets the file to be null if no file was given
        if(empty($this->data['YarnVariant']['file']))
        {
            $this->data['YarnVariant']['file'] = null;
        }

        // Upload the image of the variant
        if(FileComponent::fileGiven($this->data['YarnVariant']['file']))
        {   
             // Check if the upload went well (The image is limited to 500 x 500 pixels)
            if(!FileComponent::uploadAndResizeImage($this->data['YarnVariant']['file'], $this->data['YarnVariant']['id'], 'png', 'yarn_variants', 1000, 1000))
            {   
                // Was this a creation of an item?
                if($created)
                {   
                    // Delete the entry in the database
                    $this->delete($this->data['YarnVariant']['id']);
                }
                // It did not go well delete the image and inform the user
                SessionComponent::setFlash('Billedet til denne garnvariant blev ikke uploadet korrekt. Garnvarianten blev ikke gemt.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }
        }

        if($created)
        {
            $this->ensureThumbnail($this->data['YarnVariant']['id']);
        }

    }

    public function edit($data = null)
    {
        // Find the old model
        $old_yarn_variant = $this->find('first', array('conditions' => array('YarnVariant.id' => $data['YarnVariant']['id'])));

        // Update data from the old model
        $new_yarn_variant['YarnVariant']['previous_id'] = $old_yarn_variant['YarnVariant']['id'];
        $new_yarn_variant['YarnVariant']['created'] = $old_yarn_variant['YarnVariant']['created'];
        $new_yarn_variant['YarnVariant']['product_count'] = $old_yarn_variant['YarnVariant']['product_count'];

        // Update data from the view
        $new_yarn_variant['YarnVariant']['color_code'] = $data['YarnVariant']['color_code'];

        // Update the belongsTo relations from the view
        $new_yarn_variant['YarnVariant']['color_id'] = $data['YarnVariant']['color_id'];
        $new_yarn_variant['YarnVariant']['yarn_id'] = $data['YarnVariant']['yarn_id'];        

        // If the save went well
        if(!$this->save($new_yarn_variant))
        {
            return false;
        }
        $new_id = $this->id;

        // Upload a new image of the variant if there is one given
        if(!empty($data['YarnVariant']['file']) && FileComponent::fileGiven($data['YarnVariant']['file']))
        {
             // Check if the upload went well (The image is limited to 500 x 500 pixels)
            if(!FileComponent::uploadAndResizeImage($data['YarnVariant']['file'], $new_id, 'png', 'yarn_variants', 500, 500))
            {   
                // Delete the entry in the database
                $this->delete($new_id);

                // It did not go well delete the image and inform the user
                SessionComponent::setFlash('Billedet til denne garnvariant blev ikke uploadet korrekt. Garnvarianten blev ikke gemt.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }
        }
        else
        {
            FileComponent::copyFile($old_yarn_variant['YarnVariant']['id'],'png', true, 'yarn_variants', $new_id, 'png', true, 'yarn_variants');
        }

        

        if(!empty($old_yarn_variant['YarnBatch']))
        {
            // Update relations from the old model
            foreach ($old_yarn_variant['YarnBatch'] as $i => $yarn_batch)
            {   
                // Check if the yarn_batch is active
                if($yarn_batch['is_active'] == 1)
                {
                    $this->YarnBatch->set($yarn_batch);
                    $this->YarnBatch->saveField('yarn_variant_id', $new_id);    
                }
            }
        }

        // Update the old model
        $this->set($old_yarn_variant);
        $this->saveField('parrent_id', $new_id);

        // Set the old version to inactive
        $this->deactivate($old_yarn_variant['YarnVariant']['id']);

        // It all went well
        return true;
    }

    // Sets the is_active to 0
    public function deactivate($id = null)
    {   
        // Find the requested item
        $yarn_variant = $this->find('first', array('conditions' => array('YarnVariant.id' => $id)));

        // Does the item exist?
        if(empty($yarn_variant))
        {
            // Tell the user that the requested item did not exist 
            SessionComponent::setFlash(' Denne garnvariant findes ikke. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        // Is the item active?
        else if(!$yarn_variant['YarnVariant']['is_active'])
        {
            // Tell the user that the requested item already is inactive
            SessionComponent::setFlash(' Denne garnvariant er allerede inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        // Was the save a failure?
        $this->id = $yarn_variant['YarnVariant']['id'];

        if(!$this->saveField('is_active', 0))
        {   
            // Tell the user that the is_active property was not changed.
            SessionComponent::setFlash(' Garnvarianten blev ikke sat som inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        else
        {
            // Deactivate its children
            if(!empty($yarn_variant['YarnBatch']))
            {   
                // Run through every relation
                foreach ($yarn_variant['YarnBatch'] as $i => $yarn_batch)
                {      
                    // Check if the yarn_variant is active
                    if($yarn_batch['is_active'] == 1)
                    {   
                        $this->YarnBatch->deactivate($yarn_batch['id']);    
                    }
                }    
            }
        }

        // If the file of this variant exists_delete it
        if(FileComponent::fileExists($yarn_variant['YarnVariant']['id'], 'png', true , 'yarn_variants'))
        {
            if(!FileComponent::deleteFile($yarn_variant['YarnVariant']['id'], 'png', true , 'yarn_variants'))
            {
                // Inform the user that the file is still on the server
                SessionComponent::setFlash('Billedet til dette garnvariant blev ikke slettet og ligger stadig på serveren.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }  
        }
        
        $this->Yarn->updateProductCount($yarn_variant['Yarn']['id']);

        // It went well
        return true;
    }

    public function updateProductCount($id = null)
    {
        // Find the requested item
        $yarn_variant = $this->find('first', array('conditions' => array('YarnVariant.id' => $id)));

        // Does the item exist?
        if(empty($yarn_variant))
        {
            // Tell the user that the requested item did not exist 
            SessionComponent::setFlash(' Denne garnvariant findes ikke. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        // Is the item active?
        else if(!$yarn_variant['YarnVariant']['is_active'])
        {
            // Tell the user that the requested item already is inactive
            SessionComponent::setFlash(' Denne garnvariant er inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        $product_count = 0;
        foreach ($yarn_variant['YarnBatch'] as $key => $yarn_batch) {
            if($yarn_batch['is_active'])
            {
                $product_count++;
            }
        }

        $this->ensureThumbnail($yarn_variant['YarnVariant']['id']);
        $this->set($yarn_variant);
        $this->saveField('product_count', $product_count);
        $this->Yarn->updateProductCount($yarn_variant['Yarn']['id']);
    }

    public function makeThumbnail($id = null, $random = false)
    {
        // Find the requested item
        $yarn_variant = $this->find('first', array('conditions' => array('YarnVariant.id' => $id)));


        // Does the item exist?
        if(empty($yarn_variant))
        {
            // Tell the user that the requested item did not exist 
            SessionComponent::setFlash(' Denne garnvariant findes ikke. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        // Is the item active?
        else if($yarn_variant['YarnVariant']['is_thumbnail'])
        {
            // Tell the user that the requested item already is inactive
            SessionComponent::setFlash(' Denne garnvariant er allerede thumbnail. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        // Find the yarn that the variant is related to
        $yarn = $this->Yarn->find('first', array('conditions' => array('Yarn.id' => $yarn_variant['YarnVariant']['yarn_id'])));

        // Run through all the variants of that yarn
        foreach ($yarn['YarnVariant'] as $key => $yarn_variant_of_yarn) 
        {
            // If it is not active it should not be the thumbnail
            if(!$yarn_variant_of_yarn['is_active']) { continue;}

            // If the id matches, update it to be thumbnail
            if($yarn_variant_of_yarn['id'] == $id)
            {
                $this->set($yarn_variant_of_yarn);
                $this->saveField('is_thumbnail', 1);    
            }
            // If it does not it should NOT be thumbnail
            else
            {
                $this->set($yarn_variant_of_yarn);
                $this->saveField('is_thumbnail', 0);
            }  
        }

        // Everything went well
        return true;

    }

    private function ensureThumbnail($id = null)
    {
        // Find the requested item
        $yarn_variant = $this->find('first', array('conditions' => array('YarnVariant.id' => $id)));

        // Does the item exist?
        if(empty($yarn_variant))
        {
            // Tell the user that the requested item did not exist 
            SessionComponent::setFlash(' Denne garnvariant findes ikke. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        $yarn = $this->Yarn->find('first', array('conditions' => array('Yarn.id' => $yarn_variant['YarnVariant']['yarn_id'])));
        $has_thumbnail = false;
        $first_yarn_variant_id = null;
        foreach ($yarn['YarnVariant'] as $key => $yarn_variant_of_yarn) {
            if(!$yarn_variant_of_yarn['is_active']) { continue;}
            $first_yarn_variant_id = $yarn_variant_of_yarn['id'];
            if($yarn_variant_of_yarn['is_thumbnail'])
            {
                $has_thumbnail = true;
            }
        }

        if(!$has_thumbnail)
        {
            if($first_yarn_variant_id != null)
            {
                $this->makeThumbnail($first_yarn_variant_id);   
            }
        }
    }
}

?>
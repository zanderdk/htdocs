<?php
App::uses('AppModel', 'Model');
App::import('Component', 'File');

class NeedleVariant extends AppModel 
{
	public $displayField = 'intern_product_code';
    
	public $validate = array(
        'product_code' => array(
          'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive et navn.',
            ),
        ),
        'stock_quantity' => array(
            'naturalNumber' => array(
                'rule'    => array('naturalNumber', true),
                'allowEmpty' => false,
                'message' => 'Du skal hvor mange af denne strikkepinds variant du har.',
            )
        ),
        'item_quantity' => array(
            'naturalNumber' => array(
                'rule'    => array('naturalNumber', true),
                'allowEmpty' => false,
                'message' => 'Du skal angive hvor mange produkter der med i en odre af denne varer. Det må ikke være negativt.',
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
        'girth' => array(
            'decimal' => array(
                'rule' => 'decimal',
                'allowEmpty' => false,
                'message' => 'Du skal angive en tykkelse.',
            ),
            'notNegative' => array(
                'rule'    => array('comparison', '>=', 0),
                'message' => 'Du skal angive en ikke negativ tykkelse.'
            )
        ),
        'length' => array(
            'decimal' => array(
                'rule' => 'decimal',
                'allowEmpty' => false,
                'message' => 'Du skal angive en længde.',
            ),
            'notNegative' => array(
                'rule'    => array('comparison', '>=', 0),
                'message' => 'Du skal angive en ikke negativ længde.'
            )
        ),
        'intern_product_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive en intern varekode.',
            ),
        ),
	);

    public $belongsTo = array(
        // TODO
        'Needle',
        'AvailabilityCategory'
    );

    public function beforeSave($options = array())
    {   
        // Check if the max-girth value was given 
        if(!empty($this->data['NeedleVariant']['girth_max']) && $this->data['NeedleVariant']['girth_max'] != 0)
        {
            // Check if the max girth is bigger than the min girth
            if($this->data['NeedleVariant']['girth'] >= $this->data['NeedleVariant']['girth_max'])
            {
                SessionComponent::setFlash('Maximums tykkelse er mindre eller lig med  minimums tykkelse. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }
        }

        // Check if the stock_quantity is sent
        if(!empty($this->data['NeedleVariant']['stock_quantity']))
        {
           // Find the best matching availability_category for this item
            $new_availability_category = $this->AvailabilityCategory->findBestMatch($this->data['NeedleVariant']['stock_quantity'], 'needle');
            // Update the relation of this item to the found matching availability_category
            $this->data['NeedleVariant']['availability_category_id'] = $new_availability_category['AvailabilityCategory']['id']; 
        }
    }


    public function afterSave($created, $options = array())
    {   

        // Sets the file to be null if no file was given
        if(empty($this->data['NeedleVariant']['file']))
        {
            $this->data['NeedleVariant']['file'] = null;
        }

        // Upload the image of the variant
        if(FileComponent::fileGiven($this->data['NeedleVariant']['file']))
        {   

             // Check if the upload went well (The image is limited to 500 x 500 pixels)
            if(!FileComponent::uploadAndResizeImage($this->data['NeedleVariant']['file'], $this->data['NeedleVariant']['id'], 'png', 'needle_variants', 500, 500))
            {   
                // Was this a creation of an item?
                if($created)
                {   
                    // Delete the entry in the database
                    $this->delete($this->data['NeedleVariant']['id']);
                }
                // It did not go well delete the image and inform the user
                SessionComponent::setFlash('Billedet til denne strikkepindsvariant blev ikke uploadet korrekt. Strikkepindsvarianten blev ikke gemt.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }
        }

        // If the item have no original_id then it is the original_id
        if(empty($this->data['NeedleVariant']['original_id']))
        {
            $this->saveField('original_id',$this->data['NeedleVariant']['id']);
        }
        
        if(!empty($this->data['NeedleVariant']['needle_id']))
        {
            $this->Needle->updateProductCount($this->data['NeedleVariant']['needle_id']);   
        } 
        else if(!empty($this->data['NeedleVariant']['id']))
        {
            $needle_variant = $this->find('first', array('conditions' => array('NeedleVariant.id' => $this->data['NeedleVariant']['id'])));
            $this->Needle->updateProductCount($needle_variant['NeedleVariant']['needle_id']);       
            
        }
    }


    public function edit($data = null)
    {
        // Find the old model
        $old_needle_variant = $this->find('first', array('conditions' => array('NeedleVariant.id' => $data['NeedleVariant']['id'])));

        // Update data from the old model
        $new_needle_variant['NeedleVariant']['previous_id'] = $old_needle_variant['NeedleVariant']['id'];
        $new_needle_variant['NeedleVariant']['created'] = $old_needle_variant['NeedleVariant']['created'];
        $new_needle_variant['NeedleVariant']['previous_price'] = $old_needle_variant['NeedleVariant']['price'];

        // Update data from the view
        $new_needle_variant['NeedleVariant']['product_code'] = $data['NeedleVariant']['product_code'];
        $new_needle_variant['NeedleVariant']['stock_quantity'] = $data['NeedleVariant']['stock_quantity'];
        $new_needle_variant['NeedleVariant']['item_quantity'] = $data['NeedleVariant']['item_quantity'];
        $new_needle_variant['NeedleVariant']['price'] = $data['NeedleVariant']['price'];
        $new_needle_variant['NeedleVariant']['show_discount'] = $data['NeedleVariant']['show_discount'];
        $new_needle_variant['NeedleVariant']['girth'] = $data['NeedleVariant']['girth'];
        $new_needle_variant['NeedleVariant']['girth_max'] = $data['NeedleVariant']['girth_max'];
        $new_needle_variant['NeedleVariant']['length'] = $data['NeedleVariant']['length'];
        $new_needle_variant['NeedleVariant']['is_wire'] = $data['NeedleVariant']['is_wire'];
        $new_needle_variant['NeedleVariant']['intern_product_code'] = $data['NeedleVariant']['intern_product_code'];

        // Update relations from the data
        $new_needle_variant['NeedleVariant']['availability_category'] = $this->AvailabilityCategory->findBestMatch($data['NeedleVariant']['stock_quantity'], 'needle');
        $new_needle_variant['NeedleVariant']['needle_id'] = $data['NeedleVariant']['needle_id'];

        // Find the discount if any (else 0)
        if(!empty($old_needle_variant['NeedleVariant']))
        {
            if($old_needle_variant['NeedleVariant']['price'] > $data['NeedleVariant']['price'])
            {
                $new_needle_variant['NeedleVariant']['discount'] = intval(floor((1-($data['NeedleVariant']['price']/$old_needle_variant['NeedleVariant']['price']))*100));
            }
            else
            {
                $new_needle_variant['NeedleVariant']['discount'] = 0;
            }
        }


        // If the save went well
        if(!$this->saveAll($new_needle_variant))
        {
            return false;
        }
        $new_id = $this->id;

        // Upload a new image of the variant if there is one given
        if(!empty($data['NeedleVariant']['file']) && FileComponent::fileGiven($data['NeedleVariant']['file']))
        {
             // Check if the upload went well (The image is limited to 500 x 500 pixels)
            if(!FileComponent::uploadAndResizeImage($data['NeedleVariant']['file'], $new_id, 'png', 'needle_variants', 500, 500))
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
            FileComponent::copyFile($old_needle_variant['NeedleVariant']['id'],'png', true, 'needle_variants', $new_id, 'png', true, 'needle_variants');
        }

        // Update the old model
        $this->set($old_needle_variant);
        $this->saveField('parrent_id', $new_id);

        // Set the old version to inactive
        $this->deactivate($old_needle_variant['NeedleVariant']['id'], true);

        $this->updateCouponRelation($old_needle_variant['NeedleVariant']['id'], $new_id);

        // If the save went well
        if(empty($old_needle_variant))
        {
            return false;
        }

        // It all went well
        return true;
    }

    // Sets the is_active to 0
    public function deactivate($id = null, $delete_superfluous_associations = false)
    {   
        /// Find the requested item
        $needle_variant = $this->find('first', array('conditions' => array('NeedleVariant.id' => $id)));

        // Does the item exist?
        if(empty($needle_variant))
        {
            // Tell the user that the requested item did not exist 
            SessionComponent::setFlash('Denne strikkepindvariant findes ikke. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        // Is the item active?
        else if(!$needle_variant['NeedleVariant']['is_active'])
        {
            // Tell the user that the requested item already is inactive
            SessionComponent::setFlash('Denne strikkepindsvariant er allerede inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        // Was the save a failure?
        $this->id = $needle_variant['NeedleVariant']['id'];

        // Was the save a failure?
        if(!$this->saveField('is_active', 0))
        {   
            // Tell the user that the is_active property was not changed.
            SessionComponent::setFlash('Strikkepindsvariant blev ikke sat som inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        // If the file of this variant exists_delete it
        if(FileComponent::fileExists($needle_variant['NeedleVariant']['id'], 'png', true , 'needle_variants'))
        {
            if(!FileComponent::deleteFile($needle_variant['NeedleVariant']['id'], 'png', true , 'needle_variants'))
            {
                // Inform the user that the file is still on the server
                SessionComponent::setFlash('Billedet til denne strikkepindsvariant blev ikke slettet og ligger stadig på serveren.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }  
        }

        // It went well
        return true;
    }

    public function updateCouponRelation($old_needle_variant_id, $new_needle_variant_id)
    {
        $coupon = new Coupon();
        $old_relations = $coupon->CouponsNeedleVariant->find('all', array('conditions' => array('CouponsNeedleVariant.needle_variant_id' => $old_needle_variant_id)));

        foreach ($old_relations as $key => $relation) {
            $coupon->CouponsNeedleVariant->id = $relation['CouponsNeedleVariant']['id'];
            $coupon->CouponsNeedleVariant->saveField('needle_variant_id', $new_needle_variant_id);
        }
    }


}

?>
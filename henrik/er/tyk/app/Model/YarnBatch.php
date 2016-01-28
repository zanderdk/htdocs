<?php
App::uses('AppModel', 'Model');
App::uses('Coupon', 'Model');

class YarnBatch extends AppModel 
{
	public $displayField = 'intern_product_code';
    
	public $validate = array(
        'batch_code' => array(
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
        'stock_quantity' => array(
            'naturalNumber' => array(
                'rule'    => array('naturalNumber', true),
                'allowEmpty' => false,
                'message' => 'Du skal angive hvor mange af dette garn-parti du har. Det må ikke være negativt.',
            )
        ),
        'item_quantity' => array(
            'naturalNumber' => array(
                'rule'    => array('naturalNumber', true),
                'allowEmpty' => false,
                'message' => 'Du skal angive hvor mange produkter der med i en odre af denne varer. Det må ikke være negativt.',
            )
        ),
        'intern_product_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive en intern varekode.',
            ),
        ),
	);

    public $hasOne = array(
        'Previous' => array(
            'className' => 'YarnBatch',
            'foreignKey' => 'previous_id'
        )
    );

    public $belongsTo = array(
        // TODO
        'YarnVariant',
        'AvailabilityCategory',
        'Parrent' => array(
            'className' => 'YarnBatch',
            'foreignKey' => 'parrent_id'
        )
    );

    public function beforeSave($options = array())
    {
        // Check if the stock_quantity is sent
        if(!empty($this->data['YarnBatch']['stock_quantity']))
        {
            // Find the best matching availability_category for this item
            $new_availability_category = $this->AvailabilityCategory->findBestMatch($this->data['YarnBatch']['stock_quantity'], 'yarn');
        }
        else
        {   
            // Send 0 because it was given
            $new_availability_category = $this->AvailabilityCategory->findBestMatch(0, 'yarn');
        }

        // Update the relation of this item to the found matching availability_category
        $this->data['YarnBatch']['availability_category_id'] = $new_availability_category['AvailabilityCategory']['id']; 
    }

    public function afterSave($created, $options = array())
    {   
        if(!empty($this->data['YarnBatch']['yarn_variant_id']))
        {
            $this->YarnVariant->updateProductCount($this->data['YarnBatch']['yarn_variant_id']);   
        }
    }

    public function edit($data = null)
    {
        // Find the old model
        $old_yarn_batch = $this->find('first', array('conditions' => array('YarnBatch.id' => $data['YarnBatch']['id'])));

        // Update data from the old model
        $new_yarn_batch['YarnBatch']['created'] = $old_yarn_batch['YarnBatch']['created'];
        $new_yarn_batch['YarnBatch']['previous_id'] = $old_yarn_batch['YarnBatch']['id'];
        $new_yarn_batch['YarnBatch']['previous_price'] = $old_yarn_batch['YarnBatch']['price'];

        // Update data from the view
        $new_yarn_batch['YarnBatch']['batch_code'] = $data['YarnBatch']['batch_code'];
        $new_yarn_batch['YarnBatch']['stock_quantity'] = $data['YarnBatch']['stock_quantity'];
        $new_yarn_batch['YarnBatch']['item_quantity'] = $data['YarnBatch']['item_quantity'];
        $new_yarn_batch['YarnBatch']['price'] = $data['YarnBatch']['price'];
        $new_yarn_batch['YarnBatch']['show_discount'] = $data['YarnBatch']['show_discount'];
        $new_yarn_batch['YarnBatch']['intern_product_code'] = $data['YarnBatch']['intern_product_code'];

        // Update relations from the data
        $new_yarn_batch['YarnBatch']['availability_category'] = $this->AvailabilityCategory->findBestMatch($data['YarnBatch']['stock_quantity'], 'yarn');
        $new_yarn_batch['YarnBatch']['yarn_variant_id'] = $data['YarnBatch']['yarn_variant_id'];

        // Find the discount if any (else 0)
        if(!empty($old_yarn_batch['YarnBatch']))
        {
            if($old_yarn_batch['YarnBatch']['price'] > $data['YarnBatch']['price'])
            {
                $new_yarn_batch['YarnBatch']['discount'] = intval(floor((1-($data['YarnBatch']['price']/$old_yarn_batch['YarnBatch']['price']))*100));
            }
            else
            {
                $new_yarn_batch['YarnBatch']['discount'] = 0;
            }
        }

        // If the save went well
        if(!$this->save($new_yarn_batch))
        {
            return false;
        }
        $new_id = $this->id;

        // Update the old model
        $this->set($old_yarn_batch);
        $this->saveField('parrent_id', $new_id);

        // Set it to inactive
        $this->deactivate($old_yarn_batch['YarnBatch']['id']);
        $this->updateCouponRelation($old_yarn_batch['YarnBatch']['id'], $new_id);
        // It all went well
        return true;
    }

    // Sets the is_active to 0
    public function deactivate($id = null)
    {   
        /// Find the requested item
        $yarn_batch = $this->find('first', array('conditions' => array('YarnBatch.id' => $id)));

        // Does the item exist?
        if(empty($yarn_batch))
        {
            // Tell the user that the requested item did not exist 
            SessionComponent::setFlash(' Dette garnparti findes ikke. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
        // Is the item active?
        else if(!$yarn_batch['YarnBatch']['is_active'])
        {   
            // Tell the user that the requested item already is inactive
            SessionComponent::setFlash(' Dette garnparti er allerede inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        // Was the save a failure?
        $this->id = $yarn_batch['YarnBatch']['id'];

        if(!$this->saveField('is_active', 0))
        {   
            // Tell the user that the is_active property was not changed.
            SessionComponent::setFlash(' Garnpartiet blev ikke sat som inaktivt. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }

        $this->YarnVariant->updateProductCount($yarn_batch['YarnBatch']['yarn_variant_id']);

        // It went well
        return true;
    }

    public function updateCouponRelation($old_yarn_batch_id, $new_yarn_batch_id)
    {
        $coupon = new Coupon();
        $old_relations = $coupon->CouponsYarnBatch->find('all', array('conditions' => array('CouponsYarnBatch.yarn_batch_id' => $old_yarn_batch_id)));

        foreach ($old_relations as $key => $relation) {
            $coupon->CouponsYarnBatch->id = $relation['CouponsYarnBatch']['id'];
            $coupon->CouponsYarnBatch->saveField('yarn_batch_id', $new_yarn_batch_id);
        }
    }
}

?>
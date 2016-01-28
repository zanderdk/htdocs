<?php
App::uses('AppModel', 'Model');

class AvailabilityCategory extends AppModel 
{
	public $displayField = 'name';

	public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive et navn.',
            ),
        ),
        'show_amont' => array(
            'notEmpty' => array(
                'rule'    => array('boolean'),
                'message' => 'Du skal angive om antallet af varen skal vises.',
            ),
        ),
        'is_below' => array(
            'naturalNumber' => array(
                'rule' => array('naturalNumber', true),
                'allowEmpty' => false,
                'message' => 'Du skal angive et tal antallet af varen skal være under for at være i denne kategori.',
            ),
        ),
        'color' => array(
            'rule'    => array('inList', array('default', 'primary', 'success', 'info', 'warning', 'danger', 'supreme')),
            'allowEmpty' => false,
            'message' => 'Du skal vælge en farve til kategorien.'
         ),
        'type' => array(
            'rule'    => array('inList', array('yarn', 'needle')),
            'allowEmpty' => false,
            'message' => 'Du skal vælge en type til kategorien.'
         ),
	);

    public $hasMany = array(
        // TODO 
        'YarnBatch',
        'NeedleVariant'
    );

    public function afterSave($created, $options = array())
    {
        $this->updateDefault();

        // Saves all yarn-batches if the is_below is updated
        $yarn_batches = $this->YarnBatch->find('all', array('conditions' => array('YarnBatch.is_active' => true)));

        if(!empty($yarn_batches))
        {
            $this->YarnBatch->saveAll($yarn_batches);
        }

        // Saves all yarn-batches if the is_below is updated
        $needle_variants = $this->NeedleVariant->find('all', array('conditions' => array('NeedleVariant.is_active' => true)));

        if(!empty($needle_variants))
        {
            $this->NeedleVariant->saveAll($needle_variants);
        }
        
    }

    public function beforeDelete($cascade = true)
    {   
        // Check if there is any relations
        if($this->hasRelations($this->id))
        {   
            // Tell the user that there is a relation and deny deletion
            SessionComponent::setFlash(' Der er ting som er bundet til denne beholdningskategori. Tryk på info knappen for at se hvad.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }
    }

    public function findBestMatch($quantity = PHP_INT_MAX, $type)
    {   
        // Finds all availability categories where the type matches the input sorted ascendingly with the is_below limit
        $availability_categories = 
        $this->find('all', array('order' => array('AvailabilityCategory.is_below' => 'asc'), 
                                 'conditions' => array('AvailabilityCategory.type' => $type))
        );



        // Run through every found availability categories 
        foreach ($availability_categories as $availability_category) 
        {   
            // If the quantity is lower than the is_below limit of this availability category
            if($quantity <= $availability_category['AvailabilityCategory']['is_below'])
            {   
                return $availability_category;
            }
        }

        // Return the default availability category of this type
        return $this->find('first', array('conditions' => array('AvailabilityCategory.is_default' => '1', 'AvailabilityCategory.type' => $type)));
    }

    public function hasRelations($id)
    {
        $related_yarn_batches = $this->YarnBatch->find('all', array('conditions' => array('YarnBatch.availability_category_id' => $id)));

        // TODO check other relations when added

        if(!empty($related_yarn_batches))
        {
            return true;
        }

        return false;
    }

    // Updates the is_default value for every AvailabilityCategory
    private function updateDefault()
    {
        // Find all AvailabilityCategories sorted by is_below
        $yarn_availability_categories = $this->find('all', array('conditions' => array('AvailabilityCategory.type' => 'yarn'),'order' => array('AvailabilityCategory.is_below' => 'desc')));
        $needle_availability_categories = $this->find('all', array('conditions' => array('AvailabilityCategory.type' => 'needle'),'order' => array('AvailabilityCategory.is_below' => 'desc')));

        // Run through all AvailabilitCategories with the yarn type
        foreach ($yarn_availability_categories as $i => $availability_category) 
        {   
            // Is it the first in the list (ergo the one with the largest is_below value)
            if($i == 0)
            {   
                // If the is_default is set to 0 set it to 1
                if(!$availability_category['AvailabilityCategory']['is_default'])
                {
                    $availability_category['AvailabilityCategory']['is_default'] = 1;
                    $this->save($availability_category);
                }
            }
            else
            {   
                // If the is_default is set to 1 set it to 0
                if($availability_category['AvailabilityCategory']['is_default'])
                {
                    $availability_category['AvailabilityCategory']['is_default'] = 0;
                    $this->save($availability_category);
                }
            } 
        }

        // Run through all AvailabilitCategories with the needle type
        foreach ($needle_availability_categories as $i => $availability_category) 
        {   
            // Is it the first in the list (ergo the one with the largest is_below value)
            if($i == 0)
            {   
                // If the is_default is set to 0 set it to 1
                if(!$availability_category['AvailabilityCategory']['is_default'])
                {
                    $availability_category['AvailabilityCategory']['is_default'] = 1;
                    $this->save($availability_category);
                }
            }
            else
            {   
                // If the is_default is set to 1 set it to 0
                if($availability_category['AvailabilityCategory']['is_default'])
                {
                    $availability_category['AvailabilityCategory']['is_default'] = 0;
                    $this->save($availability_category);
                }
            } 
        }
    }
}

?>
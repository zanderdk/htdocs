<?php
App::uses('AppModel', 'Model');

class RecipeSeason extends AppModel 
{
	public $displayField = 'name';

	public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive et navn.',
            ),
        )
	);

    public $hasMany = array(
        // TODO 
        'Recipe'
    );

    public function beforeDelete()
    {   
        // TODO
        // Check if there is any thing related to it 
        // if yes => deny deletion
        // if no => delete!
    }
}

?>
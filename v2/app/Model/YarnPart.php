<?php
App::uses('AppModel', 'Model');

class YarnPart extends AppModel 
{
	public $displayField = 'id';
    
	public $validate = array(
        'percentage' => array(
            'number' => array(
                  'rule'    => array('range', 0, 101),
                  'allowEmpty' => true,
                  'message' => 'Du skal angive en procent del med værdi mellem 1-100'
            )
        ),
    );

    public $belongsTo = array(
        // TODO
        'Yarn',
        'Material'
    );
}

?>
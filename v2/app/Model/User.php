<?php
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {
    public $validate = array(
        'email' => array(
            'required' => array(
                'rule' => array('email'),
                'message' => 'En emial der ikke allerede er i systemet er påkrævet.'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Du skal skrive et kodeord.'
            )
        ),
    );

    public function beforeSave($options = array()) 
    {
        if (isset($this->data[$this->alias]['password'])) 
        {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }
}
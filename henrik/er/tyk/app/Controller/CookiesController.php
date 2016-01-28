<?php
App::uses('AppController', 'Controller');

class CookiesController extends AppController 
{

    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('accept');
    }

    public function accept() 
    {
        $this->Cookie->write('Cookie.accepted', 'yes', false);
        $this->redirect($this->referer());
    }

}

?>
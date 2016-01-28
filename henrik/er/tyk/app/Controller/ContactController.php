<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class ContactController extends AppController 
{
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('index');
    }

    public function index()
    {

        // Random numbers to exclude bots
        $rnd1 = rand(1,9);
        $rnd2 = rand(1,9);

        // Send it to the view
        $this->set('rnd1', $rnd1);
        $this->set('rnd2', $rnd2);

        if($this->Session->check('Contact.Email'))
        {
            $this->set('email', $this->Session->read('Contact.Email'));
        }
        else
        {
            $this->set('email', null);
        }

        if($this->request->is('post'))
        {
            if($this->Session->read('Contact.rnd1') + $this->Session->read('Contact.rnd2') != $this->request->data['Email']['math_answer'])
            {
                $this->Session->setFlash('Du svarede forkert på regnestykket. Prøv igen.', null, array(), 'error');
                $this->Session->write('Contact.Email', $this->request->data['Email']);
                $this->redirect(array('controller' => 'contact', 'action' => 'index'));
            }
            $fromMail = empty($this->request->data['Email']['email']) ? 'ingen@email.dk' : $this->request->data['Email']['email'];
            $fromName = empty($this->request->data['Email']['name']) ? 'BundgaardsGarn: IntetNavn' : 'BundgaardsGarn: '.$this->request->data['Email']['name']; 
            $Email = new CakeEmail();
            $Email->from(array($fromMail => $fromName));
            $Email->to('kontakt@bundgaardsgarn.dk');
            $Email->subject($this->request->data['Email']['subject']);
            $Email->send($this->request->data['Email']['textarea']);
            $this->Session->setFlash('Din mail blev sendt, vi svarer hurtigst muligt.', null, array(), 'success');
            $this->Session->delete('Contact.Email');
        }
        else
        {
            // Set the random numbers in the view
            $this->Session->write('Contact.rnd1', $rnd1);
            $this->Session->write('Contact.rnd2', $rnd2);
        }   
    }

}

?>
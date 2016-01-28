<?php
class UsersController extends AppController {

    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('add');
    }

    public function index()
    {
        // Redirect the user to the login screen if trying ti access
        return $this->redirect(array('controller' => 'users', 'action' => 'login'));
    }

    public function add() 
    {
        $user_creation_allowed = false; // CREATION: Set to true
        $safety_string = "fugl22tun33"; // CREATION: Change

        if(!$user_creation_allowed)
        {   
            // Send the user to the users-index
            $this->Session->setFlash('Der opstod en fejl. Du kan ikke oprette brugere på nuværende tidspunkt. Kontakt admin.', null, array(), 'warning');
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

        if($this->request->is('post')) 
        {
            $this->User->create();
           
            // Check if the user knows the secret password
            if($this->request->data['User']['string'] == $safety_string)
            {
                // Check if the user was saved correctly
                if ($this->User->save($this->request->data)) 
                {
                    $this->Session->setFlash('Brugeren blev oprettet.', null, array(), 'success');

                    // Send the user to the users-login
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                }
                else
                {
                    // Tell the user that the user was not saved caused by an error.
                    $this->Session->setFlash('Der opstod en fejl. Brugeren blev ikke gemt.', null, array(), 'error');
                }
            }
            else
            {
                // Tell the user that the user was not saved caused by an error.
                $this->Session->setFlash('Der opstod en fejl. Brugeren blev ikke gemt. Du har skrevet sikkerhedskoden forket. Kontakt admin.', null, array(), 'warning');
            } 
        }
    }

    public function login() 
    {   
        // Is the user already logged in
        if($this->isAuthorized())
        {
            // Send the user to where he tried to access
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }

        // Was it a post request?
        if ($this->request->is('post')) 
        {    
            // Try to log in the user
            $this->Auth->login();

            // Was it a succesful log in?
            if($this->isAuthorized())
            {
                $this->Session->setFlash('Logget ind.', null, array(), 'success');

                // Send the user to where he tried to access
                $this->redirect($this->Auth->redirect());
            }
            else
            {
                // Tell the user that he entered a wrong credentials
                $this->Session->setFlash('Forkert e-mail eller kodeord. Prøv igen.', null, array(), 'warning');  
            }
        }
    }

    public function logout() 
    {
        $this->Session->setFlash('Logget ud.', null, array(), 'success');
        return $this->redirect($this->Auth->logout());

    }

    public function isAuthorized()
    {
        return parent::isAuthorized();
    }
}

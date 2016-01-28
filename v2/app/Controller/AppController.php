<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('CakeNumber', 'Utility');
App::uses('CakeTime', 'Utility');
CakeNumber::addFormat('DKK', array('before' => 'DKK ', 'thousands' => '.', 'decimals' => ',', 'zero' => 'Gratis'));
CakeNumber::addFormat('SAVING', array('before' => '', 'thousands' => '.', 'decimals' => ',', 'zero' => 'Gratis'));

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller 
{
    public $helpers = array('Html', 'Form', 'Session');

    public $components = array(
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'pages', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'pages', 'action' => 'index'),
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email')
                )
            )
        ),
        'Security',
        'Cookie'
    );


    public function beforeFilter() 
    {
        // Secure that the user uses SSL
        $this->Security->blackHoleCallback = 'forceSSL';
        $this->Security->requireSecure();

        // Is the user logged in
        if($this->isAuthorized())
        {
            // Tell the view that the user is logged in
            $this->set('logged_in', true);
            $this->Auth->allow();
        }
        else
        {
            // Tell the view that the user is no logged in
            $this->set('logged_in', false);
        }

        $this->regulateAcceptCookie();
        $this->ensureOrderSession();

        // Count he amount of items in the cart
        $this->loadModel('Order');
        $order = $this->Order->find('first', array('conditions' => array('Order.id' => $this->Cookie->read('Order.id'))));
        $this->set('cart_amount', $order['Order']['amount']);
    }

    public function beforeRender()
    {
        // Set a variable to handle if the cookie allowance cookie isset
        $this->set('accept_cookies', $this->Cookie->check('Cookie.accepted'));
        $this->set('menu_tabs', $this->createMenuStructure());
    }

    public function ensureOrderSession()
    {
        // Load the order model
        $this->loadModel('Order');

        // Check if there exist an order cookie
        if($this->Cookie->check('Order.id'))
        {  
            // Find the order given the id
            $order = $this->Order->find('first', array('conditions' => array('Order.id' => $this->Cookie->read('Order.id'), 'state' => 'unplaced')));

            // If it exists, keep it
            if(!empty($order))
            {
                $this->Order->set($order);
                $this->Order->refresh();
                return;
            }
        }

        // If it does not create a new
        $this->Order->create();
        $this->Order->save(array('state' => 'unplaced'));
        $this->Cookie->write('Order.id', $this->Order->id, false);
    }

    public function regulateAcceptCookie()
    {
        // Find the first part of the string that refered to this
        $refer_string = substr ($this->referer(),0,26);

        // Check if the user directed around the page
        if ($refer_string == 'https://bundgaardsgarn.dk/') 
        {   
            // Accept cookies if you navigated around the page
            $this->Cookie->write('Cookie.accepted', 'yes', false);

        }
    }

    // Redirects the user to the SSL secure version of the webpage
    public function forceSSL()
    {
        return $this->redirect('https://' . env('SERVER_NAME') . $this->here);
    }

    // Ensures authorization
    public function isAuthorized()
    {
        $user = $this->Auth->user();

        if(!empty($user))
        {
            return true;
        }
        return false;
    }

    // Creates the structure of the menu
    public function createMenuStructure() 
    {
        // Load the Menu, Yarn and Needle model
        $this->loadModel('Menu');
        $this->loadModel('Yarn');
        $this->loadModel('Needle');

        // Create array to handle the menu structure
        $menu_structure = array();

        // Find all menus, yarns and needles
        $menus = $this->Menu->find('all', array('order' => array('Menu.type' => 'asc', 'Menu.name' => 'asc')));
        $index_counter = 0;
        $previous_menu_type = '';

        // Id for keeping the correct menu open
        $active_menu_id = $this->Session->read('Menu.active');
        $this->Session->delete('Menu.active');

        foreach ($menus as $key => $value) 
        {
            $menu = $value['Menu'];
            $skip = true;

            if(empty($menu_structure[$menu['type']]['is_active']))
            {
                $menu_structure[$menu['type']]['is_active'] = 0;
            }

            foreach ($value['Yarn'] as $key => $yarn) 
            {
                if($yarn['is_active'] && ($yarn['product_count'] > 0 || $this->isAuthorized()))
                {
                    $skip = false;
                }
            }

            if($skip) 
            {
                foreach ($value['Needle'] as $key => $needle) {
                    if($needle['is_active'] && ($needle['product_count'] > 0 || $this->isAuthorized()))
                    {
                        $skip = false;
                    }
                }    
            }

            if($skip)
            {
                continue;
            }
            
            // If the previous type was not this type
            if($menu['type'] != $previous_menu_type) {
                $index_counter = 0; // Reset the index counter
            }

            $menu_structure[$menu['type']][$index_counter] = array('id' => $menu['id'], 'name' => $menu['name']);            

            if($menu['id'] == $active_menu_id)
            {
                $menu_structure[$menu['type']][$index_counter]['is_active'] = 1;
                $menu_structure[$menu['type']]['is_active'] = 1;    
            }
            else
            {
                $menu_structure[$menu['type']][$index_counter]['is_active'] = 0;
            }


            $index_counter ++;
            $previous_menu_type = $menu['type'];
        }

        
        return $menu_structure;
    }
}
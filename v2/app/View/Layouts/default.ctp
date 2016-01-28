<?php
/**
 *
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
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        Bundgaardsgarn
    </title>
    <?php
        echo $this->Html->meta('icon');

        echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'));

        // Include stylesheets
        echo $this->Html->css(array(
            'bootstrap.min.css',
            'bootstrap-fileupload.min.css',
            'bootstrap-datetimepicker.min.css',
            'custom.css'
        ));

        // Include Scripts
        echo $this->Html->script(array(
            'jquery-1.10.2.min.js',
            'bootstrap.min.js',
            'bootstrap-datetimepicker.min.js'
        ));
        
        //Fetch
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
    ?>

</head>
<body>
    <!-- Facebook like -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/da_DK/sdk.js#xfbml=1&version=v2.4&appId=349172198526012";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <!-- NAVBAR -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="visible-xs navbar-brand" href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'index')); ?>"><?php echo $this->Html->image('logo.png', array('style' => 'height:30px; margin-top:-5px;')); ?></a>
                <a class="hidden-xs navbar-brand" href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'index')); ?>"><?php echo $this->Html->image('logo.png', array('style' => 'height:50px; margin-top:-15px;')); ?></a>
                <span class="visible-xs" style="height:70px; vertical-align:middle;">
                <?php $cart_icon_string = '<span style="text-align:center; font-size:1.5em; vertical-align: middle; line-height:70px;" class="glyphicon glyphicon-shopping-cart"></span>';
                            if($cart_amount > 0)
                            {
                                $cart_icon_string = '<span style="test-align:center; vertical-align: middle; margin-top:3px; background:#900000;" class="badge">'.$cart_amount.'</span> ' . $cart_icon_string;
                            }
                            echo $this->Html->link($cart_icon_string,
                                array('controller' => 'payments', 'action' => 'cart'),
                                array('escapeTitle' => false, 'style' => 'color:#777;')
                        );?>
                        
                </span>
            </div>
            
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
<!--
                    <li class="visible-xs"><a href="<?php echo $this->Html->url(array('controller' => 'yarns', 'action' => 'index')); ?>">Garn</a></li>
                    <li class="visible-xs"><a href="<?php echo $this->Html->url(array('controller' => 'needles', 'action' => 'index')); ?>">Strikepinde/hæklenåle</a></li>
-->

                    <li><a href="<?php echo $this->Html->url(array('controller' => 'recipes', 'action' => 'index')); ?>">Opskrifter</a></li>
                    <li><a href="<?php echo $this->Html->url(array('controller' => 'contact', 'action' => 'index')); ?>">Kontakt</a></li>
                    <li><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'conditions')); ?>">Betingelser</a></li>
                    <li><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'about')); ?>">Om os</a></li>
                    <li><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'newsletter')); ?>">Nyhedsbrev</a></li>


                    <?php foreach($menu_tabs as $menu_tab_name => $menu_content) : ?>
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle visible-xs" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">                                    <?php 
                                    switch ($menu_tab_name) {
                                            case 'yarn':
                                                echo 'Garn';
                                                break;
                                            case 'knit':
                                                echo 'Strikkepinde';
                                                break;
                                            case 'crochet':
                                                echo 'Hæklenåle';
                                                break;
                                            case 'surplus_yarn':
                                                echo 'Restgarn';
                                                break;
                                        } ?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                                    <?php foreach($menu_content as $key => $menu) : ?>
                                        <?php if(!is_numeric($key)) {continue;} ?>
                                        <?php
                                            if($menu_tab_name == 'knit' || $menu_tab_name == 'crochet')
                                            {
                                                $controller = 'needles';
                                            }
                                            else if ($menu_tab_name == 'yarn' || $menu_tab_name == 'surplus_yarn') 
                                            {
                                                $controller = 'yarns';
                                            }
                                        ?>
                                        <li <?php if($menu['is_active']) echo 'class="active"'; ?>><a href="
                                        <?php 
                                            echo $this->Html->url(array(
                                                'controller' => $controller,
                                                'action' => 'index',
                                                $menu['id']
                                            ));
                                        ?>
                                        "><?php echo $menu['name']; ?></a></li>
                                    <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endforeach; ?>

                    
                </ul>

                <!-- CART LINK -->
                <ul class="hidden-xs nav navbar-nav navbar-right">
                    <li>
                        <?php $cart_icon_string = '<span style="text-align:center; font-size:1.5em; vertical-align: middle;" class="glyphicon glyphicon-shopping-cart"></span>';
                            if($cart_amount > 0)
                            {
                                $cart_icon_string = '<span style="test-align:center; vertical-align: middle; margin-top:3px; background:#900000;" class="badge">'.$cart_amount.'</span> ' . $cart_icon_string;
                            }
                            echo $this->Html->link($cart_icon_string,
                                array('controller' => 'payments', 'action' => 'cart'),
                                array('escapeTitle' => false)
                        );?>
                    </li>
                </ul>
                <!-- CART LINK COLLAPSE -->
                
                <!-- ADMIN PANEL -->
                <?php if($logged_in) : ?>
                <ul class="nav navbar-nav navbar">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-lock"></span> Admin<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'availability_categories', 'action' => 'index')); ?>">Beholdningskategorier</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'colors', 'action' => 'index')); ?>">Farver</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'frontpage_items', 'action' => 'index')); ?>">Forside artikler</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'yarns', 'action' => 'index')); ?>">Garn</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'competitions', 'action' => 'index')); ?>">Konkurrencer</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'coupons', 'action' => 'index')); ?>">Kuponer</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'materials', 'action' => 'index')); ?>">Materialer</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'menus', 'action' => 'index')); ?>">Menuer</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'brands', 'action' => 'index')); ?>">Mærker</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'recipes', 'action' => 'index')); ?>">Opskrifter</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index')); ?>">Opskriftskategorier</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'orders', 'action' => 'index')); ?>">Ordre (aktive)</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'orders', 'action' => 'resolved_orders')); ?>">Ordre (sendt)</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'needles', 'action' => 'index')); ?>">Strikkepinde/Hæklenåle</a></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'care_labels', 'action' => 'index')); ?>">Vaskemærker</a></li>
                            <li class="divider"/>
                            <li>
                            <?php echo $this->Html->link(
                            '<p class="text-center" style="margin:0;"><span class="glyphicon glyphicon-log-out"></span></p>',
                            array('controller' => 'users', 'action' => 'logout'),
                            array('escapeTitle' => false, 'style' => 'margin:0;')
                            );?>
                            </li>
                            <li class="divider"/>
                        </ul>
                    </li>
                </ul>
                <?php endif; ?>
                <!-- ADMIN PANEL COLLAPSE -->
            </div>
        </div>
    </nav>
    <!-- NAVBAR COLLAPSE -->

    <!-- SESSION ERROR PRINTING -->
    <div class="container">
        <?php if($this->Session->check('Message.error') && $this->Session->read('Message.error') != null) : ?>
            <div class="alert alert-danger fade in">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->Session->flash('error'); ?>
            </div>
        <?php endif; ?>

        <?php if($this->Session->check('Message.warning') && $this->Session->read('Message.warning') != null) : ?>
            <div class="alert alert-warning fade in">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->Session->flash('warning'); ?>
            </div>
        <?php endif; ?>

        <?php if($this->Session->check('Message.success') && $this->Session->read('Message.success') != null) : ?>
            <div class="alert alert-success fade in">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->Session->flash('success'); ?>
            </div>
        <?php endif; ?>

        <?php if($this->Session->check('Message.info') && $this->Session->read('Message.info') != null) : ?>
            <div class="alert alert-info fade in">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->Session->flash('info'); ?>
            </div>
        <?php endif; ?>

        <?php if($this->Session->check('Message.flash') && $this->Session->read('Message.flash') != null) : ?>
            <div class="alert alert-info fade in">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->Session->flash(); ?>
            </div>
        <?php endif; ?>           
    </div>
    <!-- SESSION ERROR PRINTING COLLAPSE -->
    
    <!-- COOKIE ACCEPTANCE -->
    <?php if(!$accept_cookies) : ?>
        <div class="alert alert-info fade in navbar-fixed-bottom" role="alert" style="margin-bottom:0;">
            <h4 class="text-center"> Vi bruger Cookies </h4>
            <div class="container">
                <p> Bundgaardsgarn bruger Cookies til at holde styr på din indkøbskurv, og forbedre din oplevelse af vores hjemmeside. Du accepterer at vi bruger Cookies, hvis du klikker OK eller klikker dig videre til anden side. <a href="#" class="alert-link">Læs mere om Cookies</a>.</p>
            </div>
            <?php echo $this->Html->link(
                '<span>OK</span>',
                array('controller' => 'cookies', 'action' => 'accept'),
                array('class' => 'btn btn-info btn-sm btn-block', 'escapeTitle' => false, 'style' => 'width:100px; margin:0 auto;')
            );?>
        </div>
    <?php endif; ?>
    <!-- COOKIE ACCEPTANCE COLLAPSE -->

    <!-- CONTAINER -->
    <div class="container" style="margin-bottom:30px;">

        <!-- SIDE MENU -->
        <div class="col-lg-2 col-md-3 col-sm-4 hidden-xs side-menu">
            <div class="panel-group" id="accordion">
                <!--START-->
                <?php foreach($menu_tabs as $menu_tab_name => $menu_content) : ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $menu_tab_name; ?>">
                            <h4 class="panel-title panel-danger" >
                                <a>
                                    <?php 
                                    switch ($menu_tab_name) {
                                            case 'yarn':
                                                echo 'Garn';
                                                break;
                                            case 'knit':
                                                echo 'Strikkepinde';
                                                break;
                                            case 'crochet':
                                                echo 'Hæklenåle';
                                                break;
                                            case 'surplus_yarn':
                                                echo 'Restgarn';
                                                break;
                                        } ?>
                                </a>
                            </h4>
                        </div>
         
                        <div id="collapse<?php echo $menu_tab_name; ?>" class="panel-collapse collapse <?php if($menu_content['is_active']) echo 'in'; ?>">
                            <div class="panel-body">
                                <ul class="nav nav-pills nav-stacked">
                                    <?php foreach($menu_content as $key => $menu) : ?>
                                        <?php if(!is_numeric($key)) {continue;} ?>
                                        <?php
                                            if($menu_tab_name == 'knit' || $menu_tab_name == 'crochet')
                                            {
                                                $controller = 'needles';
                                            }
                                            else if ($menu_tab_name == 'yarn' || $menu_tab_name == 'surplus_yarn') 
                                            {
                                                $controller = 'yarns';
                                            }
                                        ?>
                                        <li <?php if($menu['is_active']) echo 'class="active"'; ?>><a href="
                                        <?php 
                                            echo $this->Html->url(array(
                                                'controller' => $controller,
                                                'action' => 'index',
                                                $menu['id']
                                            ));
                                        ?>
                                        "><?php echo $menu['name']; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!--END-->
            </div>
        </div>
        <!-- SIDE MENU COLLAPSE -->
        
        <!-- CONTENT -->
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12 content">
            <?php echo $this->fetch('content'); ?>
                <div class="payments">
                <h4 class="page-header">Hos os kan du betale med:</h4>
                <div class="wrapper">
                    <div class="card"><img src="/v2/img/payment/paypal.png" alt="Paypal"></div>
                    <div class="card"><img src="/v2/img/payment/visa.png" alt="Visa"></div>
                    <div class="card"><img src="/v2/img/payment/dankort.png" alt="Dankort"></div>
                </div>
                <div class="facebook"><a href="http://www.facebook.com/bundgaardsgarn/" target="_blank"><img src="/v2/img/frontpage_items/FBlogo.png" alt="Find os på Facebook"></a></div>
        </div>
        </div>
        <!-- CONTENT COLLAPSE -->

        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-4 hidden-xs"></div>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <br/>
                <!--<div class="fb-like" data-href="https://www.facebook.com/BundgaardsGarn" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>-->
            </div>
        </div>
    </div>
    <!-- CONTAINER COLLAPSE -->
    
</body>

</html>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-33288293-1', 'auto');
  ga('send', 'pageview');

</script>

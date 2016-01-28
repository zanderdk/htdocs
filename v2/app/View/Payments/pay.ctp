<div class="page-header">
  <h1><span style="color:#5cb85c;">Indkøbskurv </span>&raquo; <span style="color:#5cb85c;">Levering </span>  &raquo; <span style="color:#5cb85c;">Ordreoversigt </span>  &raquo; Betal</h1>
</div>

<div class="well well-sm">
    Du er ved at købe varer for <strong><?php  echo $this->Number->currency($order['Order']['price'], 'DKK'); ?></strong>
</div>


<?php /* echo $this->Form->create(
    'Customer', 
    array(
        'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control',
        'label' => false,
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'label label-danger error-msg'))
        ),
    )
); ?>

<!-- card owner input -->
<?php echo $this->Form->input('card_owmer', array('required' => true, 'label' => 'Kortindehaver', 'value' => '')); ?>

<!-- card number input -->
<?php echo $this->Form->input('card_number', array('required' => true, 'label' => 'Kortnummer', 'value' => '',
                                                            'between' => '<div class="input-group"><div class="input-group-addon"><span class="glyphicon glyphicon-credit-card"> </span></div>',
                                                            'after' => '</div>')); ?>

<hr style="border:none;"/> <!-- Fixes input-group-addon do not remove! -->

<div class="row">

    <!-- expiry month input -->
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <?php echo $this->Form->input('expiry.month', array('required' => true, 'label' => 'Måned', 'options' => array($expiry['months']))); ?>
    </div>

    <!-- expiry year input -->
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <?php echo $this->Form->input('expiry.year', array('required' => true, 'label' => 'År', 'options' => array($expiry['years']))); ?>
    </div>

    <!-- cvc input -->
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <?php echo $this->Form->input('cvv', array('required' => true, 'label' => 'CVV', 'value' => '',
                                                            'between' => '<div class="input-group"><div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>',
                                                            'after' => '</div>')); ?>
    </div>
</div>

<?php debug("Indsæt - hvordanfandenlavermankreditkort?"); ?>

<!-- PayPal Logo --><table border="0" cellpadding="10" cellspacing="0" align="center"><tr><td align="center"></td></tr><tr><td align="center"><a href="#" title="Saadan fungerer PayPal" onclick="javascript:window.open('https://www.paypal.com/dk/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=700, height=600');"><img src="https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_PayPal_betalingsmuligheder_dk.jpg" border="0" alt="PayPal Acceptance Mark"></a></td></tr></table><!-- PayPal Logo -->

<!-- Submit Button -->
<p class="text-center" style="margin:0;">
<?php echo $this->html->tag(
        'button', 
        '</span> <span style="font-size:1.3em;">Betal </span>', 
        array(
                'class' => 'btn btn-success btn-lg btn-block', 
                'type' => 'submit'
        )
    ); ?>
</p>

<br/>

<?php echo $this->Form->end(); ?>
*/ ?>



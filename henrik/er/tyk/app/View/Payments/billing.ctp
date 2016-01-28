<div class="page-header">
  <h1><span style="color:#5cb85c;">Indkøbskurv </span>&raquo; Levering  &raquo;<span class="text-muted"> Ordreoversigt &raquo; Betal</span></h1>
</div>
<?php echo $this->Form->create(
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

<?php echo $this->Form->input('Customer.id', array('value' => $customer['id'])); ?>

<!-- Name -->
<div class="row">
    <!-- first_name input -->
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $this->Form->input('Customer.first_name', array('label' => 'Fornavn', 'value' => $customer['first_name'])); ?>
    </div>
    <!-- last_nanme input -->
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $this->Form->input('Customer.last_name', array('label' => 'Efternavn' , 'value' => $customer['last_name'])); ?>
    </div>
</div>
<!-- Name collapse -->

<div class="row">
    <!-- email_adress input -->
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $this->Form->input('Customer.email_address', array('label' => 'Email', 'value' => $customer['email_address'], 
                                                            'between' => '<div class="input-group"><div class="input-group-addon"><span class ="glyphicon glyphicon-envelope"></span></div>',
                                                            'after' => '</div>')); ?>
    </div>
    <!-- phone_number input -->
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $this->Form->input('Customer.phone_number', array('label' => 'Telefon', 'value' => $customer['phone_number'],
                                                            'between' => '<div class="input-group"><div class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></div>',
                                                            'after' => '</div>')); ?>
    </div>
</div>

<!-- Billing address --> 
<h3 class="page-header">Fakturerings adresse</h3>
    
    <?php echo $this->Form->input('Customer.BillingAddress.id', array('value' => $customer['billing_address_id'])); ?>

<div class="row">
    <!-- country -->
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <?php echo $this->Form->input('Customer.BillingAddress.country', array('label' => 'Land', 'value' => 'Danmark', 'disabled')); ?>
    </div>
    <!-- zip_code input -->
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <?php echo $this->Form->input('Customer.BillingAddress.zip_code', array('label' => 'Postnummer', 'value' => $customer['BillingAddress']['zip_code'])); ?>
    </div>

    <!-- city_name input -->
    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
        <?php echo $this->Form->input('Customer.BillingAddress.city_name', array('label' => 'By', 'value' => $customer['BillingAddress']['city_name'])); ?>
    </div>
</div>

<div class="row">
    <!-- street_name input -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php echo $this->Form->input('Customer.BillingAddress.street', array('label' => 'Gade', 'value' => $customer['BillingAddress']['street'])); ?>
    </div>

</div>

<hr/>

<?php /*debug("Fjern - vil du have nyhedbreve");*/ ?>

<!-- Checkboxes -->
<?php echo $this->Form->input('Customer.ShippingAddress.same_shipping_address', array('id' => 'show_shipping_address', 'type' => 'checkbox', 'class' => false, 'before' => '<label>Få leveret til samme adresse</label> ', 'checked' => $customer['ShippingAddress']['same_shipping_address']));?> 

<!-- Checkboxes collapse -->

<!-- Shipping address --> 
<div id="shipping_address" class="shipping_address" style="display:
    <?php if($customer['ShippingAddress']['same_shipping_address']) 
        {
            echo 'none';
        }
        else 
        {
            echo 'visible';
        } ?>
        ;">
    <h3 class="page-header">Leverings adresse</h3>

        <?php echo $this->Form->input('Customer.ShippingAddress.id', array('value' => $customer['shipping_address_id'])); ?>
    <div class="row">
        <!-- country -->
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <?php echo $this->Form->input('Customer.ShippingAddress.country', array('label' => 'Land', 'value' => 'Danmark', 'disabled', 'required' => false)); ?>
        </div>
        <!-- zip_code input -->
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <?php echo $this->Form->input('Customer.ShippingAddress.zip_code', array('label' => 'Postnummer', 'required' => false, 'value' => $customer['ShippingAddress']['zip_code'])); ?>
        </div>

        <!-- city_name input -->
        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
            <?php echo $this->Form->input('Customer.ShippingAddress.city_name', array('label' => 'By', 'required' => false, 'value' => $customer['ShippingAddress']['city_name'])); ?>
        </div>
    </div>

    <div class="row">
        <!-- street_name input -->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php echo $this->Form->input('Customer.ShippingAddress.street', array('label' => 'Gade', 'required' => false, 'value' => $customer['ShippingAddress']['street'])); ?>
        </div>
    </div>
</div>
<!-- Shipping address collapse -->

<!-- Conditions -->
<?php echo $this->Form->input('Condition.accepted', array('id' => 'show_shipping_address', 'type' => 'checkbox', 'class' => false, 'before' => '<label>Har du accepteret vores <a href="'.$this->Html->url(array('controller' => 'pages', 'action' => 'conditions')).'">betingelser</a>?</label> '));?>

<hr/>

<?php echo $this->Form->input('Order.customer_note', array('label' => 'Eventuel besked til forhandler', 'value' => $customer_note)); ?>

<!-- Submit Button -->
<p class="text-center" style="margin:0;">
    <?php echo $this->html->tag(
            'button', 
            '</span> <span style="font-size:1.3em;">Gå til betaling </span><span class="glyphicon glyphicon-arrow-right">', 
            array(
                    'class' => 'btn btn-success btn-lg btn-block', 
                    'type' => 'submit'
            )
        ); ?>
</p>

<?php echo $this->Form->end(); ?>

</br>
<a href="
    <?php echo $this->Html->url(array(
                            'controller' => 'payments',
                            'action' => 'cart',
                        )); ?>"
    type="button" class="btn btn-default btn-lg btn-block">
    <span class="glyphicon glyphicon-arrow-left"></span>
    <span style="font-size:1.3em;"><strong>Tilbage</strong></span>
</a>

</br>

<!-- Function to display shipping address -->
<script>
    $(document).ready(function(){
        $('#show_shipping_address').change(function(){
            if(!this.checked) $('#shipping_address').show();
            else $('#shipping_address').hide();
        });
    });
</script>
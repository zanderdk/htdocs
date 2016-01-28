<h1 class="page-header">Find vinder af <?php echo $competition['Competition']['title']; ?></h1>

<?php if(!empty($winner)) : ?>
    <label>Email</label> <?php echo $winner['email_address']; ?> <br/>
    <label>Navn</label> <?php echo $winner['name']; ?> <br/>
    <label>Tlf</label> <?php echo $winner['phone_number']; ?>
<?php endif; ?>

<?php echo $this->Form->create(); ?>

<!-- Submit Button -->
<p class="text-center" style="margin:0;">
<?php echo $this->html->tag(
        'button', 
        '<span class="glyphicon glyphicon-star"></span> <span style="font-size:1.3em;">Find vinder</span>', 
        array(
                'class' => 'btn btn-md btn-default', 
                'type' => 'submit', 
                'style' => 'width:200px;'
        )
    ); ?>
</p>

<?php echo $this->Form->end(); ?>
<br/>
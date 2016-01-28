<h1 class="page-header">Kontakt</h1>
<p class="lead text-muted">Her kan du skrive en mail, hvis du har spørgsmål, kommentarer eller andet i forbindelse med vores produkter. Hvis du ikke angiver navn og eller email kan vi ikke svare på din mail.</p>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-9">
        
            <?php echo $this->Form->create('Email', 
                array(
                    'inputDefaults' => array(
                'div' => '',
                'wrapInput' => false,
                'class' => 'form-control',
                'label' => false,
                ),
                    'class' => 'well',
                )
            );?>
            <?php echo $this->Form->input('name', array('label' => 'Navn')); ?>
            <br/>
            <?php echo $this->Form->input('email', array('label' => 'Email')); ?>
            <br/>
            <?php echo $this->Form->input('subject', array('label' => 'Emne', 'required')); ?>
            <br/>
            <?php echo $this->Form->input('textarea', array('rows' => '5', 'label' => 'Besked', 'required'));?>
            <br/>
            <label>Sikkerhed - er du et menneske?</label>
            <p class="text-muted">Dette regnestykke skal besvares sikkerhedsmæssige årsager for at sikre os at du ikke er en robot.</p>
      <div class="input-group">
        <span class="input-group-addon">Hvad er <?php echo $rnd1; ?> + <?php echo $rnd2; ?> ? </span>
        <?php echo $this->Form->input('math_answer'); ?>
        </div>
      <br/>
            
            <!-- The Submit button -->
            <?php echo '<p class="text-center" style="margin:0;">'; ?>
            <?php echo $this->html->tag('button', '<span class="glyphicon glyphicon-send"></span> <span style="font-size:1.3em;">Send</span>', array('class' => 'btn btn-md btn-primary', 'type' => 'submit', 'style' => 'width:200px;')); ?>
            <?php echo '</p>'; ?>
            <?php echo $this->Form->end(); ?>
    </div>

</div>
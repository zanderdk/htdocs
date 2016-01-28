<h1 class="page-header">Brugere</h1>
<?php 

// Create the form
echo $this->Form->create('User', 
    array(
        'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control',
        'label' => false,
        ),
        'class' => 'well',
    )
);

    // The form inputs
    echo $this->Form->input('email', array('label' => 'Email'));
    echo $this->Form->input('password', array('label' => 'Kodeord'));
    echo $this->Form->input('string', array('label' => 'Sikkerhedskode'));

    // The submit-button
    echo '<p class="text-center" style="margin:0;">';
    echo $this->html->tag('button', '<span class="glyphicon glyphicon-floppy-disk"></span>', array('class' => 'btn btn-md btn-primary', 'type' => 'submit', 'style' => 'width:200px;'));
    echo '</p>';

// Ends the form
echo $this->Form->end();
?>
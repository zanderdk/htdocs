<h1 class="page-header">
    <span>Konkurrencer</span>

    <!-- Add -->
    <?php echo $this->Html->link(
    '<span class="glyphicon glyphicon-plus"></span>',
    array('controller' => 'competitions', 'action' => 'add'),
    array('class' => 'btn btn-success btn-sm pull-right', 'escapeTitle' => false,)
    );?>
</h1>

<table class="table">
    <thead>
        <th>#</th>
        <th>Titel</th>
        <th>Billede</th>
        <th style="width:35%;">Beskrivelse</th>
        <th>Status</th>
        <th>Operation</th>
    </thead>
    <tbody>
        <?php foreach($competitions as $competition) : ?>
        <tr>
            <td><?php echo $competition['Competition']['id']; ?></td>
            <td><?php echo $competition['Competition']['title']; ?></td>
            <td><?php echo $this->Html->image('/img/competitions/'.$competition['Competition']['id'].'.png', array('style' => 'width:100px;', 'class' => 'thumbnail'));?></td>
            <td><?php echo $competition['Competition']['description']; ?></td>
            <td>
                <?php if($competition['Competition']['is_active']) : ?>
                    <p>Aktiv</p>
                <?php else : ?>
                    <p>Lukket</p>
                <?php endif; ?>
            </td>
            <td>
                <!-- Operations -->
                <!-- Find winner -->
                <?php echo $this->Html->link(
                '<span class="glyphicon glyphicon-star"></span>',
                array('controller' => 'competitions', 'action' => 'find_winner', $competition['Competition']['id']),
                array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
                );?>

                <!-- Edit -->
                <?php echo $this->Html->link(
                '<span class="glyphicon glyphicon-pencil"></span>',
                array('controller' => 'competitions', 'action' => 'edit', $competition['Competition']['id']),
                array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
                );?>

                <!-- If competition is active, show close button, if the competition is inactive show reopen button -->
                <?php if($competition['Competition']['is_active']) : ?>

                    <!-- Close -->
                    <?php echo $this->Html->link(
                        '<span class="glyphicon glyphicon-fire"></span>',
                        array('controller' => 'competitions', 'action' => 'close', $competition['Competition']['id']),
                        array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
                        'Er du sikker på du vil lukke denne Konkurrence?'
                    );?>

                <?php else : ?>

                    <!-- Close -->
                    <?php echo $this->Html->link(
                        '<span class="glyphicon glyphicon-leaf"></span>',
                        array('controller' => 'competitions', 'action' => 'reopen', $competition['Competition']['id']),
                        array('class' => 'btn btn-success btn-xs', 'escapeTitle' => false),
                        'Er du sikker på du vil genåbne denne Konkurrence?'
                    );?>

                <?php endif; ?>

                <!-- Operations Collapse -->
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
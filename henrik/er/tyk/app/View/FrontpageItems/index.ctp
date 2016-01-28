<h1 class="page-header">
    <span>Forside artikler</span>

    <!-- Add -->
    <?php echo $this->Html->link(
    '<span class="glyphicon glyphicon-plus"></span>',
    array('controller' => 'frontpage_items', 'action' => 'add'),
    array('class' => 'btn btn-success btn-sm pull-right', 'escapeTitle' => false,)
    );?>
</h1>

<table class="table">
    <thead>
        <th style="width:4%;">#</th>
        <th style="width:20%;">Billed</th>
        <th style="width:30%;">url</th>
        <th style="width:20%;">Knap-tekst</th>
        <th style="width:20%;">Beskrivelse</th>
        <th style="width:6%;">Operationer</th>
    </thead>
    <tbody>
        <?php foreach($frontpage_items as $frontpage_item) : ?>
        <tr>
            <td><?php echo $frontpage_item['FrontpageItem']['id']; ?></td>
            <td> <div style="background:url('/v2/img/frontpage_items/<?php echo $frontpage_item['FrontpageItem']['id']; ?>.png') center no-repeat;background-size: contain; width:100px; height:100px;">
                    </div></td>
            <td><?php echo $frontpage_item['FrontpageItem']['url']; ?></td>
            <td><?php echo $frontpage_item['FrontpageItem']['button_text']; ?></td>
            <td><?php echo $frontpage_item['FrontpageItem']['description']; ?></td>
            <td>
                <!-- Operations -->

                <!-- Edit -->
                <?php echo $this->Html->link(
                '<span class="glyphicon glyphicon-pencil"></span>',
                array('controller' => 'frontpage_items', 'action' => 'edit', $frontpage_item['FrontpageItem']['id']),
                array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
                );?>

                <!-- Delete -->
                <?php echo $this->Html->link(
                    '<span class="glyphicon glyphicon-trash"></span>',
                    array('controller' => 'frontpage_items', 'action' => 'delete', $frontpage_item['FrontpageItem']['id']),
                    array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
                    'Er du sikker på du vil slette denne artikel?'
                );?>
                <!-- Operations Collapse -->
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
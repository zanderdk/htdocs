<h1 class="page-header">Aktive ordre</h1>
<table class="table">
        <thead>
            <th>Ordre nr.</th>
            <th>Modtaget</th>
            <th>Status</th>
            <th>Antal Varer</th>
            <th>Pris</th>
            <th>Operationer</th>
        </thead>
        <tbody>
            <?php foreach ($orders as $i => $order) : ?>
                <tr>
                    <td><?php echo $order['Order']['id']; ?></td>
                    <td><?php echo $order['Order']['date_received']; ?></td>
                    <td>
                        <?php 
                        if($order['Order']['state'] == 'received')
                        {
                            echo 'Modtaget';
                        }
                        else if($order['Order']['state'] == 'packed')
                        {
                            echo 'Pakket';
                        }
                        ?>
                    </td>
                    <td><?php echo $order['Order']['amount']; ?></td>
                    <td><?php echo $this->Number->currency($order['Order']['price'], 'DKK');?></td>
                    <td>
                            <!-- View as recipt -->
                            <?php echo $this->Html->link(
                            '<span class="glyphicon glyphicon-file"></span>',
                            array('controller' => 'orders', 'action' => 'view_as_receipt', $order['Order']['id']),
                            array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
                            );?>
                        <?php if($order['Order']['state'] == 'received') : ?>
                            <!-- Pack -->
                            <?php echo $this->Html->link(
                            '<span class="glyphicon glyphicon-list-alt"></span>',
                            array('controller' => 'orders', 'action' => 'pack', $order['Order']['id']),
                            array('class' => 'btn btn-primary btn-xs', 'escapeTitle' => false,)
                            );?>
                        <?php elseif($order['Order']['state'] == 'packed') : ?>
                            <!-- Send -->
                            <?php echo $this->Html->link(
                            '<span class="glyphicon glyphicon glyphicon-barcode"></span>',
                            array('controller' => 'orders', 'action' => 'send', $order['Order']['id']),
                            array('class' => 'btn btn-success btn-xs', 'escapeTitle' => false,)
                            );?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
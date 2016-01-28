<h1 class="page-header">Gamle ordre</h1>
<table class="table">
        <thead>
            <th>Ordre nr.</th>
            <th>Transaktions nr.</th>
            <th>Modtaget</th>
            <th>Afviklet</th>
            <th>Antal Varer</th>
            <th>Pris</th>
            <th>Operationer</th>
        </thead>
        <tbody>
            <?php foreach ($orders as $i => $order) : ?>
                <tr>
                    <td><?php echo $order['Order']['id']; ?></td>
                    <td><?php echo $order['Order']['transaction_id']; ?></td>
                    <td><?php echo $order['Order']['date_received']; ?></td>
                    <td><?php echo $order['Order']['date_resolved']; ?></td>
                    <td><?php echo $order['Order']['amount']; ?></td>
                    <td><?php echo $this->Number->currency($order['Order']['price'], 'DKK');?></td>
                    <td>
                        <!-- View as recipt -->
                        <?php echo $this->Html->link(
                        '<span class="glyphicon glyphicon-file"></span>',
                        array('controller' => 'orders', 'action' => 'view_as_receipt', $order['Order']['id']),
                        array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
                        );?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
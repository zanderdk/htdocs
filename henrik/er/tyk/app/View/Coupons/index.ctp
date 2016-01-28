<h1 class="page-header">
    <span>Kuponner</span>

    <!-- Add -->
    <?php echo $this->Html->link(
    '<span class="glyphicon glyphicon-plus"></span>',
    array('controller' => 'coupons', 'action' => 'add'),
    array('class' => 'btn btn-success btn-sm pull-right', 'escapeTitle' => false,)
    );?>
</h1>

<table class="table">
    <thead>
        <th>#</th>
        <th>Kode</th>
        <th>Rabat</th>
        <th>Oprettelsesdato</th>
        <th>Udløbsdato</th>
        <th>Antal tilbage</th>
        <th>Status</th>
        <th class="text-right">Operationer</th>
    </thead>
    <tbody>
        <?php foreach($coupons as $coupon) : ?>
        <tr>
            <td><?php echo $coupon['Coupon']['id']; ?></td>
            <td><?php echo $coupon['Coupon']['key']; ?></td>
            <td>
                <?php echo empty($coupon['Coupon']['percentage_discount']) ? 'DKK ' . $coupon['Coupon']['actual_discount'] : $coupon['Coupon']['percentage_discount'] . '%'; ?>
            </td>
            <td><?php echo $coupon['Coupon']['created']; ?> </td>
            <td><?php echo $coupon['Coupon']['expiration_date']; ?> </td>
            <td><?php echo $coupon['Coupon']['amount']; ?> </td>
            <td><?php echo $coupon['Coupon']['is_active'] ? 'Aktiv' : 'Deaktiveret'; ?></td>
            <td class="text-right">
                <!-- Operations -->
                    
                    <!-- Info -->
                    <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#couponinfo<?php echo $coupon['Coupon']['id']; ?>">
                        <span class="glyphicon glyphicon-info-sign"></span>
                    </button>

                    <div class="modal" id="couponinfo<?php echo $coupon['Coupon']['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title text-left" id="myModalLabel">Note</h4>
                                </div>
                                <div class="modal-body text-left"> 
                                        <p><?php echo $coupon['Coupon']['note']?></p>
                                        <?php if(empty($coupon['Coupon']['note'])) : ?>
                                            <p class="text-muted">Ingen note</p>
                                        <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php if($coupon['Coupon']['is_active']): ?>
                    <!-- Deactivate -->
                    <?php echo $this->Html->link(
                        '<span class="glyphicon glyphicon glyphicon-fire"></span>',
                        array('controller' => 'coupons', 'action' => 'deactivate', $coupon['Coupon']['id']),
                        array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
                        'Er du sikker på du vil deaktivere denne kupon?'
                    );?>
                    <?php else : ?>
                    <!-- Reactivate -->
                    <?php echo $this->Html->link(
                        '<span class="glyphicon glyphicon glyphicon-leaf"></span>',
                        array('controller' => 'coupons', 'action' => 'reactivate', $coupon['Coupon']['id']),
                        array('class' => 'btn btn-success btn-xs', 'escapeTitle' => false),
                        'Er du sikker på du vil reaktivere denne kupon?'
                    );?>
                <!-- Operations Collapse -->
                <?php endif; ?>
            </td>


        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
App::uses('AppModel', 'Model');

class OrderItem extends AppModel 
{
	public $displayField = 'id';

	public $validate = array(
        'amount' => array(
            'naturalNumber' => array(
                'rule'    => array('naturalNumber', true),
                'allowEmpty' => false,
                'message' => 'Du skal angive et antal.',
            )
        ),
	);

    public $belongsTo = array(
        // TODO 
        'NeedleVariant',
        'YarnBatch',
        'Recipe',
        'Order',
        'ColorSample' => array(
            'className' => 'Yarn',
            'foreignKey' => 'color_sample_id'
        ),
    );

    public function resetPrice()
    {
        $order_item = $this->find('first', array('conditions' => array('OrderItem.id' => $this->id)));
        $price = 0;

        if($order_item['OrderItem']['yarn_batch_id'] != 0)
        {
             $price = $order_item['YarnBatch']['price'] * $order_item['OrderItem']['amount'];
        }
        else if($order_item['OrderItem']['needle_variant_id'] != 0)
        {
            $price = $order_item['NeedleVariant']['price'] * $order_item['OrderItem']['amount'];
        }

        else if($order_item['OrderItem']['recipe_id'] != 0)
        {
            $price = $order_item['Recipe']['price'] * $order_item['OrderItem']['amount'];
        }

        else if($order_item['OrderItem']['color_sample_id'] != 0)
        {
            $price = $order_item['ColorSample']['price'] * $order_item['OrderItem']['amount'];
        }

        $this->saveField('price', $price);
        $this->saveField('saving', 0);
        
    }


}

?>
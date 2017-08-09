<?php
class transactionItems extends adfModelBase {
    protected $_fields = array(
        'transaction_id','domain','order_type','quantity','unit_price','total_price','revised_price','revised_percentage','addedon','updatedon'
    );
}
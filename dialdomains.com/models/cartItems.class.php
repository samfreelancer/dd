<?php
class cartItems extends adfModelBase {
    protected $_fields = array(
        'cart_id','domain','is_voice_domain','quantity','unit_price','total_price','revised_price','addedon'
    );
    public function addCartItem($cartData){
        if (false === $this->checkItemExists($cartData['cart_id'], $cartData['domain'])) {
            $item_id = $this->add($cartData);
            return $item_id;    
        }
        return false;
    }
    public function getUserCartItems($userId = '', $sessionId = ''){
        $where = '';
        if (!empty($userId)) {
            $where = 'c.user_id = "'.$this->db->escape($userId).'"';
        } else if (!empty($sessionId)) {
            $where = 'c.session_id = "'.$this->db->escape($sessionId).'"';
        }
        if (!empty($where)) {
            $query = "SELECT c.id,c.user_id,c.session_id,c.total_price as cart_total,c.sub_total_price,ci.domain,ci.quantity,ci.unit_price,ci.total_price,ci.revised_price,ci.is_voice_domain FROM cart as c INNER JOIN cartItems as ci ON c.id = ci.cart_id WHERE ".$where;
            $data = $this->getByQuery($query);
            if (!empty($data)) {
                $return = [];
                foreach ($data as $d) {
                    $return[$d['domain']] = $d;
                }
                return $return;    
            }
        }
        return array();
    }
    public function deleteFromUserCart($domain,$cartId){
        $where= 'cart_id="'.$this->db->escape($cartId).'" AND domain = "'.$this->db->escape($domain).'"';
        return $this->deleteWhere($where);
    }
    public function checkItemExists($cart_id, $domain) {
        $where = 'cart_id = '.$this->db->escape($cart_id).' AND domain = "'.$this->db->escape($domain).'"';
        if (false === $data = $this->getOneWhere($where)){
            return false;
        }
        return $data;
    }
    public function getCartTotalByItems($cart_id) {
        $query = "SELECT SUM(total_price) as total_price FROM cartItems WHERE cart_id = '".$cart_id."'";
        return $this->getByQuery($query);
    }
}
?>

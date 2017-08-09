<?php
class cart extends adfModelBase {
    protected $_fields = array(
        'user_id','session_id','total_price','sub_total_price','created_at','updated_at'
    );
    public function addToCart($cartData){
        $user_id = !empty($cartData['user_id']) ? $cartData['user_id'] : '';
        $session_id = !empty($cartData['session_id']) ? $cartData['session_id'] : '';
        $cart = '';
        $cart = $this->getUserCart($user_id, $session_id);
        $ar = [];
        $price = lib_improve_price($ar, $cartData['unit_price']);
        if (empty($cart)) { // new cart create
            $cartData['revised_price'] = $cartData['quantity'] * $price;
            $cartData['sub_total_price'] = $cartData['total_price'] = $cartData['revised_price'];
            $cart_id = $this->add($cartData);
            $cartData['cart_id'] = $cart_id;
            $cartItems = new cartItems();
            $item_id = $cartItems->addCartItem($cartData);
            return $cartData;
        } else { // user cart already exists
            // check if the same item already exists in cart
            $cartItems = new cartItems();
            $cart_id = '';
            foreach ($cart as $dat) {
                $cart_id = $dat['id'];
                break;    
            }
            if (false === $cartItems->checkItemExists($cart_id, $cartData['domain'])) {
                // new item to add in cart
                $cartData['cart_id'] = $cart_id;
                $cartData['revised_price'] = $cartData['quantity'] * $price;
                $cartData['total_price'] = $cartData['quantity'] * $cartData['revised_price'];
                $item_id = $cartItems->addCartItem($cartData);
                $total = $cartItems->getCartTotalByItems($cart_id);
                if(!empty($total[0]['total_price'])) {
                    $cartData['total_price'] = $cartData['sub_total_price'] = $total[0]['total_price']; 
                    $this->update($cart_id, $cartData);
                }
                return $cartData;
            } else {
                // item already exists in cart
            }
        }
        return false;
    }
    public function updateQuantity($cartData) {
        $user_id = !empty($cartData['user_id']) ? $cartData['user_id'] : '';
        $session_id = !empty($cartData['session_id']) ? $cartData['session_id'] : '';
        $cart = '';
        if (false !== $cart = $this->getUserCart($user_id, $session_id)){ // check if user cart exists
            $cartItems = new cartItems();
            $cart_id = '';
            foreach ($cart as $dat) {
                $cart_id = $dat['id'];
                break;    
            }
            // check if item user updating already exists in cart
            if (false !== $item = $cartItems->checkItemExists($cart_id, $cartData['domain'])) {
                $result = array();
                $cartData['total_price'] = $cartData['quantity'] * $item['revised_price'];
                // update cart item    
                $cartItems->update($item['id'], $cartData);
                $total = $cartItems->getCartTotalByItems($cart_id);
                if(!empty($total[0]['total_price'])) {
                    $cartData['total_price'] = $cartData['sub_total_price'] = $total[0]['total_price']; 
                    $this->update($cart_id, $cartData);
                }
                return $cartData;
            } else {
                // item doesn't exists in cart
            }
        } else {
            // user cart doesn't exists
        }
        return false;
    }
    public function getUserCart($userId = '', $session_id = ''){
        $cartItems = new cartItems();
        return $cartItems->getUserCartItems($userId, $session_id);
    }
    public function getUserMainCart($userId = '', $session_id = '') {
        if (!empty($userId)) {
            $where = 'user_id = "'.$this->db->escape($userId).'"';
        } else if (!empty($sessionId)) {
            $where = 'session_id = "'.$this->db->escape($sessionId).'"';
        }
        $cart = '';
        if (!empty($where)) {
            $cart = $this->getOneWhere($where);
            if(!empty($cart)) {
                return $cart;
            }
        }
        return false;
    }
    public function deleteFromUserCart($cartData){
        $user_id = !empty($cartData['user_id']) ? $cartData['user_id'] : '';
        $session_id = !empty($cartData['session_id']) ? $cartData['session_id'] : '';
        $cart = '';
        $cart = $this->getUserMainCart($user_id, $session_id);
        if (false !== $cart){ // check if user cart exists
            $cart_id = $cart['id'];
            $cartItems = new cartItems();
            $cartItems->deleteFromUserCart($cartData['domain'], $cart_id);
            $total = $cartItems->getCartTotalByItems($cart_id);
            if(!empty($total[0]['total_price'])) {
                $cartData['total_price'] = $cartData['sub_total_price'] = $total[0]['total_price']; 
                $this->update($cart_id, $cartData);
                return true;
            } else {
                // no total value or zero means no item available in cart
                // delete cart entry and for safe side
                // delete all cart items
                $where= 'cart_id="'.$this->db->escape($cart_id).'"';
                $cartItems->deleteWhere($where);
                
                $where= 'id="'.$this->db->escape($cart_id).'"';
                $this->deleteWhere($where);
                return true;
            }
        }
        return $cart;
    }
    
    public function deleteUserCart($user_id) {
        $cart = false;
        $cart = $this->getUserMainCart($user_id, '');
        if (false !== $cart){ // check if user cart exists
            $cart_id = $cart['id'];
            $where= 'cart_id="'.$this->db->escape($cart_id).'"';
            $cartItems = new cartItems();
            $cartItems->deleteWhere($where);
            
            $where= 'id="'.$this->db->escape($cart_id).'"';
            $this->deleteWhere($where);
            return true;
        }
        return false;
    }
    
    public function updateCartUser($session_id, $user_id) {
        if (!empty($user_id)) {
            $query = "UPDATE `{$this->_table}` set user_id ='".$user_id."' WHERE session_id = '".$session_id."'";
            return $this->executeQuery($query);
        }
    }
}
?>

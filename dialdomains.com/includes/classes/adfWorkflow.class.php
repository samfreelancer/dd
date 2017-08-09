<?php
class adfWorkflow {
    public function __construct() {
        $this->status = adfStatus::getInstance();
        
        if (!isset($_SESSION['next_request'])) {
            $this->reset();
        }

        if (!empty($_REQUEST['then'])) {
            $this->add($_REQUEST['then'], lib_request('cb'));
        }
    }

    public function add($destination, $callback = '') {
        $_SESSION['next_request'][$destination] = array(
            'ca'    => $destination,
            'cb'    => $callback,
        );
    }

    public function reset() {
        $_SESSION['next_request'] = array();
        $_SESSION['last_request'] = '';
    }

    public function exists() {
        if (isset($_SESSION['next_request']) && count($_SESSION['next_request']) > 0) {
            return true;
        }

        return false;
    }

    public function getNext($destroy = false) {
        reset($_SESSION['next_request']);
        list($k, $nr) = each($_SESSION['next_request']);

        if ($destroy) {
            unset($_SESSION['next_request'][$k]);
        }

        reset($_SESSION['next_request']);
        return $nr;
    }

    public function goToNext() {
        $nr = $this->getNext(true);

        if (!empty($nr['cb'])) {
            $cb = explode('/', $nr['cb']);
            $cb[0] = $cb[0] . 'Controller';
            if (method_exists($cb[0], $cb[1])) {
                $i = new $cb[0]();
                $i->$cb[1]();
            }
        }

        $_SESSION['last_request'] = $nr['ca'];

        lib_redirect($nr['ca']);
    }

    public function resetIfOffWorkflow() {
        if (!lib_is_post()) {
            if ($this->exists()) {
                $n = $this->getLast();

                if (!empty($n)) {
                    $registry = adfRegistry::getInstance();

                    if (!preg_match("#^{$registry->controller}/{$registry->action}.*#i", $n)) {
                        $this->reset();
                        #$this->status->message("Workflow has automatically been reset. $n to {$registry->controller}/{$registry->action}");
                        $this->status->message("Workflow has been updated to reflect the current task.");
                    }
                }
            }
        }
    }

    public function getLast() {
        return $_SESSION['last_request'];
    }

    
}
<?php
class adfBaseValidator {
    protected
      $model,
      $event,
      $recid,
      $field,
      $errors;

    /**
     * Class constructor
     *
     * @param AppModel $model An instance of the model calling this class
     * @param string $event The event type, one of add or update
     * @param int $recid The record ID must be specified when event type is update
     */
    public function __construct(adfModelBase $model, $event = 'add', $recid = null) {
        if (!in_array($event, array('add', 'update'))) {
            trigger_error("Event type must be either add or update", E_USER_ERROR);
        }

        if ($event == 'update' && !lib_is_int($recid)) {
            trigger_error("Record ID must be specified when the event type is update", E_USER_ERROR);
        }

        $this->model = $model;
        $this->event = $event;
        $this->recid = $recid;
    }

    public function hasFailed($data) {
        $this->errors = array();
        $classReflection = new ReflectionClass($this);

        if ($this->event === 'update'){
            $oldValues = $this->model->getById($this->recid);
        }

        foreach ($data as $k => $v) {
            $this->field = $k;
            if (method_exists($this, $k) && ($this->event !== 'update' || $oldValues[$k] != $v)){
               if ($classReflection->getMethod($k)->getNumberOfParameters() == 2){
                   $this->$k($v, $data);
               } else {
                   $this->$k($v);
               }
            }

        }

        return count($this->errors) > 0 ? true : false;
    }

    public function finalChecks($unchangedData){
        $this->errors = array();
        if (method_exists($this, '_finalChecks')){
            $this->_finalChecks($unchangedData);
        }
        return count($this->errors) == 0;
    }

    public function getFirstError() {
        foreach ($this->errors as $field => $n) {
            foreach ($this->errors[$field] as $error) {
                return $error;
            }
        }
    }

    public function getErrors() {
        return $this->errors;
    }

    public function fail($message) {
        if (!array_key_exists($this->field, $this->errors)) {
            $this->errors[$this->field] = array();
        }

        $this->errors[$this->field][] = $message;
    }

    public function isLongerThan($value, $length) {
        return strlen($value) > $length;
    }

    public function isValidEmail($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function isLengthBetween($min, $max, $value) {
        return (strlen($value) >= $min && strlen($value) <= $max) ? true : false;
    }

    public function isLettersAndNumbersOnly($value) {
      return preg_match('#^[0-9a-z]+$#i', $value);
    }

    public function __call($name, $arguments) {
        return;
    }
}
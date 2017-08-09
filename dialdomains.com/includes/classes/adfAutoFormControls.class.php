<?php
class adfAutoFormControls {
    public function getControlCode($type, $attributes, $php = true) {
        $this->_checkAttributes('input', 'table.name,field.name', $attributes);

        $attributes['generated_name']       = libCreateFieldName($attributes['table.name'], $attributes['field.name']);
        $attributes['generated_id']         = empty($attributes['field.id']) ? $attributes['generated_name'] : $attributes['field.id'];
        $attributes['attributes.custom']    = $this->_createCustomAttributes($attributes);
        
        switch ($type) {
            case 'input':
                $control = $this->_input($attributes, 'text');
                break;
            case 'select':
                $control = $this->_select($attributes);
                break;
            case 'radio':
                $control = $this->_radio($attributes);
                break;
            case 'checkbox':
                $control = $this->_checkbox($attributes);
                break;
            case 'textarea':
                $control = $this->_textarea($attributes);
                break;
            case 'selectmultiple':
                $control = $this->_select($attributes, true);
                break;
            case 'hidden':
                $control = $this->_input($attributes, 'hidden');
                break;
            case 'password':
                $control = $this->_input($attributes, 'password');
                break;
            case 'file':
                $control = $this->_file($attributes);
                break;
            default:
                $control = null;
                trigger_error("$type is not a recognized form control type.", E_USER_ERROR);
        }

        if (!$php) {
            $buf = new adfBuffer();
            $buf->start();
            eval('?>' . $control);
            $control = $buf->stop();
        }

        return $control;
    }

    protected function _checkAttributes($control, $required, $attributes) {
        $required = explode(',', $required);

        foreach ($required as $r) {
            if (empty($attributes[$r])) {
                trigger_error("The $control control requires the $r attribute", E_USER_NOTICE);
            }
        }
    }

    protected function _createCustomAttributes($attributes) {
        $code = '';

        if (!empty($attributes['attributes.custom']) && is_array($attributes['attributes.custom']) && count($attributes['attributes.custom']) > 0) {
            foreach ($attributes['attributes.custom'] as $k => $v) {
                $code .= "$k=\"$v\" ";
            }
        }

        return $code;
    }

    protected function _textarea($attributes) {
        $c = '<textarea id="'
            . $attributes['generated_id'] . '" '
            . ((empty($attributes['field.class'])) ? '' : 'class="' . trim($attributes['field.class']) . '" ')
            . ' name="' . $attributes['generated_name'] . '" '
            . $attributes['attributes.custom'] . ">"
            . '<?php echo lib_request(\'' . $attributes['generated_name'] . '\'); ?>'
            . "</textarea>";

        return $c;
    }

    protected function _select($attributes, $allowMultiple = false) {
        $this->_checkAttributes('select', 'field.choices', $attributes);
        
        $c = '<select id="'
            . $attributes['generated_id'] . '" '
            . ((empty($attributes['field.class'])) ? '' : 'class="' . trim($attributes['field.class']) . '" ')
            . (($allowMultiple) ? 'multiple="multiple" ' : '')
            . ' name="' . $attributes['generated_name'] . '" '
            . $attributes['attributes.custom'] . ">\n";
        
        foreach ($attributes['field.choices'] as $key => $value) {
            $c .= '<option '
               . '<?php if (lib_request(\'' . $attributes['generated_name'] . '\') == (string) \'' . $key . '\') { echo \'selected="selected" \'; } ?> '
               . 'value="' . $key . '">'
               . $value
               . "</option>\n";
        }

        $c .= "</select>\n";

        return $c;
    }

    protected function _checkbox($attributes) {
        $this->_checkAttributes('checkbox', 'field.choices', $attributes);

        $c = '';
        $counter = 1;

        foreach ($attributes['field.choices'] as $key => $value) {
            $c .= '<input id="' . $attributes['generated_id'] . $counter . '" '
                . ((empty($attributes['field.class'])) ? '' : 'class="' . trim($attributes['field.class']) . '" ')
                . '<?php if (lib_request(\'' . $attributes['generated_name'] . $counter . '\') == (string) \'' . $key . '\') { echo \'checked="checked" \'; } ?> '
                . 'type="checkbox" '
                . 'name="' . $attributes['generated_name'] . '[]" '
                . $attributes['attributes.custom'] . ' '
                . 'value="' . $key . '" />'
                . '<label for="' . $attributes['generated_id'] . $counter . '">' . $value . "</label>\n";

            $counter++;
        }

        return $c;
    }

    protected function _radio($attributes) {
        $this->_checkAttributes('radio', 'field.choices', $attributes);

        $c = '';
        $counter = 1;

        foreach ($attributes['field.choices'] as $key => $value) {
            $c .= '<input id="' . $attributes['generated_id'] . $counter . '" '
                . ((empty($attributes['field.class'])) ? '' : 'class="' . trim($attributes['field.class']) . '" ')
                . '<?php if (lib_request(\'' . $attributes['generated_name'] . '\') == (string) \'' . $key . '\') { echo \'checked="checked" \'; } ?> '
                . 'type="radio" '
                . 'name="' . $attributes['generated_name'] . '" '
                . $attributes['attributes.custom'] . ' '
                . 'value="' . $key . '" />'
                . '<label for="' . $attributes['generated_id'] . $counter . '">' . $value . "</label>\n";

            $counter++;
        }

        return $c;
    }

    protected function _input($attributes, $type = 'text') {
        $c = '<input id="' 
            . $attributes['generated_id'] . '" '
            . ((empty($attributes['field.class'])) ? '' : 'class="' . trim($attributes['field.class']) . '" ')
            . 'type="' . $type . '" name="' . $attributes['generated_name']
            . '" value="<?php echo lib_request(\'' . $attributes['generated_name'] . '\'); ?>" '
            . $attributes['attributes.custom'] . '/>';

        return $c;
    }

    protected function _file($attributes) {
        $c = '<input id="'
            . $attributes['generated_id'] . '" '
            . ((empty($attributes['field.class'])) ? '' : 'class="' . trim($attributes['field.class']) . '" ')
            . 'type="file" name="' . $attributes['generated_name']
            . $attributes['attributes.custom'] . '/>';

        return $c;
    }
}
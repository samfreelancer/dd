<?php
class adfAutoForm {
    /**
     * The MySQL table name
     * @var string
     * @access protected
     */
    protected $table = '';

    /**
     * An array of control types
     *
     * @var array
     * @access protected
     */
    protected $controlTypes = array(
        'input',
        'password',
        'hidden',
        'select',
        'selectmultiple',
        'radio',
        'checkbox',
        'file',
        'textarea'
    );

    /**
     * Maps MySQL field types to HTML Form Controls
     * 
     * @var array
     * @access protected
     */
    protected $fieldTypeToControlMap = array(
        'tinyint'               => 'input',
        'smallint'              => 'input',
        'mediumint'             => 'input',
        'int'                   => 'input',
        'bigint'                => 'input',
        'decimal'               => 'input',
        'float'                 => 'input',
        'double'                => 'input',
        'real'                  => 'input',
        'bit'                   => 'input',
        'bool'                  => 'select',
        'date'                  => 'input',
        'datetime'              => 'input',
        'timestamp'             => 'input',
        'time'                  => 'input',
        'year'                  => 'input',
        'char'                  => 'input',
        'varchar'               => 'input',
        'tinytext'              => 'input',
        'text'                  => 'textarea',
        'mediumtext'            => 'textarea',
        'longtext'              => 'textarea',
        'binary'                => 'input',
        'varbinary'             => 'input',
        'tinyblob'              => 'textarea',
        'mediumblob'            => 'textarea',
        'blob'                  => 'textarea',
        'longblob'              => 'textarea',
        'enum'                  => 'select',
        'set'                   => 'checkbox',
        'geometry'              => 'textarea',
        'point'                 => 'textarea',
        'linestring'            => 'textarea',
        'polygon'               => 'textarea',
        'multipoint'            => 'textarea',
        'multilinestring'       => 'textarea',
        'multilinepolygon'      => 'textarea',
        'geometrycollection'    => 'textarea',
    );

    /**
     * Maps MySQL field names to the corresponding control type
     *
     * @var array
     * @access protected
     */
    protected $fieldNameToControlMap = array();

    /**
     * An array of attributes indexed by field name
     *
     * @var array
     * @access protected
     */
    protected $fieldAttributes = array();

    /**
     * An array of form attributes
     * 
     * @var array
     */
    protected $formAttributes = array(
        'form.action'       => '',
        'form.method'       => 'post',
        'form.name'         => 'adfForm',
        'form.submit.text'  => 'Submit',
        'form.charset'      => 'UTF-8',
    );

    /**
     * The path to the form templates
     *
     * @var string
     * @access protected
     */
    protected $templatePath = 'views/blocks/form/';

    /**
     * The template folder name used to generate the form.
     * 
     * @var string
     * @access protected
     */
    protected $template = 'default';

    /**
     * An array of MySQL fields for the specified table
     * 
     * @var array
     * @access protected
     */
    protected $fields = array();

    /**
     * A mapped array, MySQL field name to field type
     *
     * @var array
     * @access protected
     */
    protected $fieldTypes = array();

    /**
     * An instance of adfTableDefinitions
     * 
     * @var object
     * @access protected
     * @see adfTableDefinitions
     */
    protected $tDef;

    /**
     * An array of active fields
     *
     * @var array
     * @access protected
     */
    protected $activeFields = array();

    /**
     * Constructor
     * 
     * @param string $table The table name to generate the form from
     */
    public function __construct($table, adfTableDefinitions $tableDef) {
        $this->table    = $table;
        $this->tDef     = $tableDef;
        
        $this->_setFields();
        $this->_mapFieldsToControls();
    }

    /**
     * Adds multiple active fields to the form.  Active fields are fields that
     * will be displayed.
     *
     * @param mixed $fields A field or array of fields
     * @return The new number of active fields
     */
    public function addActiveFields($fields) {
        if (!is_array($fields)) {
            $this->addActiveField($fields);
        } else {
            foreach ($fields as $field) {
                $this->addActiveField($field);
            }
        }

        return count($this->activeFields);
    }

    /**
     * Adds an active field to the form.  Active fields are fields that will be
     * displayed.
     *
     * @param string $name The field name
     * @return void
     */
    public function addActiveField($field) {
        if (!in_array($field, $this->fields)) {
            trigger_error("A field by the name of $field does not exist in the table {$this->table}", E_USER_NOTICE);
            return false;
        }

        $this->activeFields[$field] = $field;
    }

    /**
     * Clears all active fields.
     *
     * @return void
     */
    public function clearActiveFields() {
        $this->activeFields = array();
    }

    /**
     * Remove an active field.
     * 
     * @param string $name The field name
     */
    public function removeActiveField($name) {
        unset($this->activeFields[$name]);
    }

    /**
     * Set the template path relative to the site root directory.
     *
     * @param string $path The template path
     * @return void;
     */
    public function setTemplatePath($path) {
        $this->templatePath = $path;
    }

    public function setFieldAttribute($field, $attribute, $value) {
        $valid = array(
            'attributes.custom',
            'field.id',
            'field.class',
            'field.choices',
            'control.type',
        );

//        commented out to allow attributes to be set before the field is added
//        if (!in_array($field, $this->fields)) {
//            trigger_error("field $field does not exist in table {$this->table}.  Try refreshing the table cache if it was recently created.");
//        }

        if (!in_array($attribute, $valid)) {
            trigger_error("$attribute is not valid for field $field.  Must be one of " . implode(",", $valid), E_USER_NOTICE);
        }

        if ($attribute == 'field.choices') {
            if (!is_array($value)) {
                trigger_error("Third parameter must be an array when setting the choices attribute", E_USER_NOTICE);
            }
        } elseif ($attribute == 'control.type') {
            if (!in_array($value, $this->controlTypes)) {
                trigger_error("$attribute value $value is not valid for field $field.  Must be one of " . implode(",", $this->controlTypes), E_USER_NOTICE);
            }
        } else {
            if (!is_string($value)) {
                trigger_error("Third parameter must be a string when setting the $attribute attribute", E_USER_NOTICE);
            }
        }

        $this->fieldAttributes[$field][$attribute] = $value;
        
        return $this;
    }

    
    public function getAddRecordForm($php = false) {
        $cGen       = new adfAutoFormControls();
        $code       = '';
        $pkey       = $this->tDef->getPrimaryKey($this->table);

        foreach ($this->activeFields as $field) {
            if ($field == $pkey) { # skip the primary key field
                continue;
            }

            $type       = $this->fieldNameToControlMap[$field];

            $control = $cGen->getControlCode($type, $this->_getAttributesByField($field, $type), $php) . "<br />\n";

            $code .= $control;
        }

        return $code;
    }

    protected function _getAttributesByField($field, $type) {
        $attributes = array(
            'table.name'    => $this->table,
            'field.name'    => $field,
        );

        if (isset($this->fieldAttributes[$field]) && is_array($this->fieldAttributes[$field]) && count($this->fieldAttributes[$field]) > 0) {
            foreach ($this->fieldAttributes[$field] as $key => $value) {
                $attributes[$key] = $value;
            }
        }

        $attributes['field.class'] = (empty($attributes['field.class'])) ? $type : $attributes['field.class'] . ' ' . $type;

        return $attributes;
    }

    public function addField($field, $type, $choices) {
        if (!in_array($type, $this->controlTypes)) {
            trigger_error("$type is not a valid form control type for field $field.", E_USER_NOTICE);
            return false;
        }

        if (in_array($type, array('enum', 'set'))) {
            if (!is_array($options)) {
                trigger_error("Third parameter must be an array when type is of enum or set", E_USER_ERROR);
                return false;
            } else {
                $this->setFieldAttribute($field, 'field.choices', $choices);
            }
        }

        $this->fields[]             = $field;
        $this->fieldTypes[$field]   = $type;
    }

    /**
     * Sets the MySQL table field names
     *
     * @access protected
     */
    protected function _setFields() {
        $this->fields       = $this->tDef->getFields($this->table);
        $this->fieldTypes   = $this->tDef->getFieldTypes($this->table);
        $attributes         = $this->tDef->getFieldAttributes($this->table);

        foreach ((array) $this->fields as $field) {
            if (in_array($this->fieldTypes[$field], array('enum', 'set'))) {
                if (!empty($attributes[$field]) && is_array($attributes[$field]) && count($attributes[$field]) > 0) {
                    $selections = array();
                    
                    foreach ($attributes[$field] as $value) {
                        $selections[$value] = ucfirst($value);
                    }

                    $this->setFieldAttribute($field, 'field.choices', $selections);
                }
            }
        }
    }

    protected function _mapFieldsToControls() {
        foreach ($this->fields as $fieldName) {
            if (!array_key_exists($fieldName, $this->fieldTypes)) {
                trigger_error("Field {$this->table}.$fieldName has an unknown type.  Try refreshing the table cache.", E_USER_NOTICE);
                $this->fieldNameToControlMap[$fieldName] = 'input';
            }

            $this->fieldNameToControlMap[$fieldName] = $this->fieldTypeToControlMap[$this->fieldTypes[$fieldName]];
        }
    }
}
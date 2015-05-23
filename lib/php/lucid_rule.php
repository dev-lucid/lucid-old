<?php

class lucid_rule implements interface__lucid_rule
{
    public $js_constructor = 'new lucid.rule';

    public function __construct($field='', $type='', $message = '', $parameters=array())
    {
        $this->field      = $field;
        $this->type       = $type;
        $this->message    = $message;
        $this->parameters = $parameters;
    }

    public function validate($data)
    {
        $value = $data[$this->field];
        switch($this->type)
        {
            case 'match':
                $match_value = $data[$this->parameters['match']];
                return ($value == $match_value);
                break;
            case 'length':
                if(isset($this->parameters['min']) and strlen($value) < isset($this->parameters['min']))
                {
                    return false;
                }
                if(isset($this->parameters['max']) and strlen($value) > isset($this->parameters['max']))
                {
                    return false;
                }
                return true;
                break;
            case 'email':
                return (preg_match('/([a-z0-9_]+|[a-z0-9_]+\.[a-z0-9_]+)@(([a-z0-9]|[a-z0-9]+\.[a-z0-9]+)+\.([a-z]{2,4}))/i',$value) == 1);
                break;
        }
    }

    public function render_javascript()
    {
        $js = $this->js_constructor.'(\''.$this->field.'\',\''.$this->type.'\',\''.$this->message.'\','.json_encode($this->parameters).')';
        return $js;
    }
}

?>
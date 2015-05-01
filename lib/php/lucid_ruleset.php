<?php

interface interface__lucid_rule
{
    public function __construct($field, $type, $message, $parameters);
    public function validate($data);
    public function render_javascript();
}

class lucid_ruleset
{
    public $rules = [];
    public $js_constructor = 'new lucid.ruleset';

    public function __construct()
    {
        $rules = func_get_args();
        foreach($rules as $rule)
        {
            $this->add_rule($rule);
        }
    }

    public function add_rule($new_rule)
    {
        if (!$new_rule instanceof interface__lucid_rule)
        {
            throw new Exception('lucid_ruleset: added rules must implement interface__lucid_rule. See lucid/lib/php/lucid_ruleset.php for interface details.');
        }
        $new_rule->parent = $this;
        $new_rule->index  = count($this->rules);
        $this->rules[] = $new_rule;
        return $this;
    }

    public function validate($data = null)
    {
        if (is_null($data))
        {
            $data = $_REQUEST;
        }
        $errors = [];
        $error_count = 0;
        foreach($this->rules as $rule)
        {
            $result = $rule->validate($data);
            if ($result !== true)
            {
                if (!is_array($errors[$rule->field]))
                {
                    $errors[$rule->field] = [];
                }
                $error_count++;
                $errors[$rule->field][] = $rule->message;
            }
        }

        return ($error_count === 0)?true:$errors;
    }

    public function render_javascript($form_name)
    {
        $js = 'lucid.ruleset.rulesets[\''.$form_name.'\'] = '.$this->js_constructor.'(';
        $js_rules = [];
        for($i=0; $i <count($this->rules); $i++)
        {
            $js_rules[] = $this->rules[$i]->render_javascript();
        }
        $js .= implode(',',$js_rules).');';
        return $js;
    }

    public function send_javascript($form_name)
    {
        lucid::javascript($this->render_javascript($form_name));
    }
}

?>
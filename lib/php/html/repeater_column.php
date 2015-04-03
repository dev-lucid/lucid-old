<?php
/* This class is used to create a fuelex repeater. */

class lucid_html_repeater_column
{
    public $label       = '';
    public $property    = '';
    public $sortable    = false;
    public $width       = '10%';
    public $render_func = null;
    public $parent      = null;

    function __construct($label=null,$property=null,$sortable=false,$width=null,$render_func=null)
    {
        $this->label       = $label;
        $this->property    = $property;
        $this->sortable    = $sortable;
        $this->width       = $width;
        $this->render_func = $render_func;
    }

    function get_js_object()
    {
        return ['label'=>$this->label,'property'=>$this->property,'sortable'=>$this->sortable];
    }

    function render($data)
    {
        $render_func = $this->render_func;
        if (is_null($render_func))
        {
            return $data[$this->property];
        }
        else
        {
            $render_func = $this->render_func;
            return $render_func($this,$data);
        }
    }
}

?>
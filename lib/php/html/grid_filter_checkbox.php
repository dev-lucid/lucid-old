<?php

class lucid_html_grid_filter_checkbox extends lucid_html_grid_filter implements interface__lucid_html_grid_filter
{
    public $html   = '';
    public $parent = null;
    public $index  = null;
    public $name   = null;
    public $label  = null;

    public function __construct($name=null, $label = '')
    {
        $this->name       = $name;
        $this->label  = $label;
    }

    public function render_html()
    {
        $this->parent->html .= '<div class="input-group pull-right">';
        $this->parent->html .= '
      <span class="input-group-addon">
        <input id="'.$this->parent->id.'-'.$this->name.'" type="checkbox" aria-label="..." onchange="lucid.html.grid.grids[\''.$this->parent->id.'\'].page0().reloadData();">
      </span>
      
      <span class="input-group-addon form-control">'.$this->label.'</span>
    </div>';

        #$this->parent->html .= '</div>';
    }

    public function render_javascript()
    {
        return 'lucid.html.grid.grids[\''.$this->parent->id.'\'].addFilter(new lucid.html.grid.filter.checkbox(\''.$this->name.'\'));';
    }

    public function apply_to_data_source()
    {
        $is_checked = (lucid::request($this->parent->id.'-'.$this->name) == 'yes');
        if($is_checked)
        {
            $this->parent->data_source->where('role_id',1);    
        }
    }
}

?>
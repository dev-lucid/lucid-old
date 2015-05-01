<?php

class lucid_html_grid_filter_select extends lucid_html_grid_filter implements interface__lucid_html_grid_filter
{
    public $html        = '';
    public $parent      = null;
    public $index       = null;
    public $column      = null;
    public $select_data = null;
    public $id_field    = null;
    public $label_field = null;
    public $blank_label = null;
    public $grid_position = 'bottom';
    

    public function __construct($column=null, $select_data = null, $blank_label = '', $label_prefix = '', $label_suffix = '', $id_field = null, $label_field = null)
    {
        $this->column       = $column;
        $this->select_data  = $select_data;
        $this->blank_label  = $blank_label;
        $this->label_prefix = $label_prefix;
        $this->label_suffix = $label_suffix;
        $this->id_field     = $id_field;
        $this->label_field  = $label_field;
    }

    public function render_html()
    {
        $this->html  = '<div class="input-group pull-right">';
        $this->html .= '<select id="'.$this->parent->id.'-'.$this->column.'" class="form-control" onchange="lucid.html.grid.grids[\''.$this->parent->id.'\'].page0().reloadData();">';

        $this->id_field    = (is_null($this->id_field))?0:$this->id_field;
        $this->label_field = (is_null($this->label_field))?1:$this->label_field;

        if(!is_null($this->blank_label))
        {
            $this->html .= '<option value="">'.$this->blank_label.'</option>';
        }
        foreach($this->select_data as $option)
        {
            $this->html .= '<option value="'.$option[$this->id_field].'">'.$this->label_prefix.$option[$this->label_field].$this->label_suffix.'</option>';
        }
        $this->html .= '</select></div>';
        $this->parent->html_template_fields['filters-'.$this->grid_position] .= $this->html;
    }

    public function render_javascript()
    {
        return 'lucid.html.grid.grids[\''.$this->parent->id.'\'].addFilter(new lucid.html.grid.filter.select(\''.$this->column.'\'));';
    }

    public function apply_to_data_source()
    {
        $term = lucid::request($this->parent->id.'-'.$this->column);
        if(is_null($term) or $term =='')
        {

        }
        else
        {
            $this->parent->data_source->where($this->column,$term);    
        }
    }
}

?>
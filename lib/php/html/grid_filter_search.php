<?php

class lucid_html_grid_filter_search extends lucid_html_grid_filter implements interface__lucid_html_grid_filter
{
    public $html          = '';
    public $parent        = null;
    public $index         = null;
    public $search_fields = [];
    public $id_suffix     = null;
    public $grid_position = 'top';

    public function __construct($search_fields=[],$id_suffix = 'search')
    {
        $this->search_fields = $search_fields;
        $this->id_suffix     = $id_suffix;
    }

    public function render_html()
    {
        $this->html = '<div class="input-group pull-right" style="width:200px;">';
        $this->html .= '<input type="text" class="form-control input-sm" placeholder="'.lucid::i18n('ui-grid-search-placeholder').'" id="'.$this->parent->id.'-'.$this->id_suffix.'" onkeyup="lucid.html.grid.grids[\''.$this->parent->id.'\'].delayedReload();" />';
        $this->html .= '<span class="input-group-btn"><button class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-search"></span></button></span>';
        $this->html .= '</div>';
        $this->parent->html_template_fields['filters-'.$this->grid_position] .= $this->html;
    }

    public function render_javascript()
    {
        return 'lucid.html.grid.grids[\''.$this->parent->id.'\'].addFilter(new lucid.html.grid.filter.search(\''.$this->id_suffix.'\'));';
    }

    public function apply_to_data_source()
    {
        $clauses = [];
        $terms   = explode(' ',trim(lucid::request($this->parent->id.'-'.$this->id_suffix,'')));
            
        if(count($terms) > 0)
        {
            foreach($terms as $term)
            {
                if($term != '')
                {
                    $term_clauses = [];
                    foreach($this->search_fields as $field)
                    {
                        $term_clauses[] = $field.' like \'%'.addslashes($term).'%\'';
                    }
                    $clauses[] = '('.implode(' or ',$term_clauses).')';
                }
            }
        }

        if(count($clauses) > 0)
        {
            $this->parent->data_source->where_raw('('.implode(' and ',$clauses).')');
        }
    }
}

?>
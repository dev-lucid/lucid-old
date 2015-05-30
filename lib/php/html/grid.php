<?php

interface interface__lucid_html_grid_filter
{
    public function __construct();
    public function render_html();
    public function render_javascript();
    public function apply_to_data_source();
}

interface interface__lucid_html_grid_column
{
    public function __construct($label, $property, $sortable = false, $width = null, $render_function = null);
    public function render_width();
    public function render_header($format = 'html');
    public function render_cell($format='html',$data=array());
}

class lucid_html_grid
{
    # passed as parameters
    public $title                = null;
    public $id                   = null;
    public $url                  = null;
    public $data_source          = null;
    public $parameters           = null;
    public $columns              = [];
    public $filters              = [];

    # some styling options
    public $style_panel          = 'primary';
    public $style_hover          = true;
    public $style_striped        = true;
    public $style_bordered       = false;

    public $pager_show_first     = true;
    public $pager_show_previous  = true;
    public $pager_show_next      = true;
    public $pager_show_last      = true;
    public $pager_show_selector  = true;
    public $pager_btn_style      = 'info';
    public $pager_btn_size       = ' btn-sm';
    
    # for the recordset sorting/paging
    public $page_size            = 10;
    public $page_index           = 0;
    public $sort_column          = null;
    public $sort_direction       = null;
    public $total_rows           = null;
    public $final_page           = null;
    
    # used for output
    public $html                 = '';
    public $js                   = '';

    # template layout and field array
    public $html_template_fields      = [
        'title'=>'',
        'filters-top'=>'',
        'filters-bottom'=>'',
        'table'=>'',
        'pager'=>'',
    ];
    public $html_template             = <<<TEMPLATE
<div id="{{id}}" class="lucid-html-grid panel panel-{{style_panel}}">
    <div class="panel-heading clearfix">
        <h5 class="pull-left">{{title}}</h5>
        <div class="pull-right">{{filters-top}}</div>
    </div>
    <div class="table-responsive">
        {{table}}
    </div>
    <div class="panel-footer clearfix">
        <div class="pull-left">{{filters-bottom}}</div>
        <div class="btn-group pull-right" role="group">{{pager}}</div>
    </div>
</div>
TEMPLATE;
    
    # options!
    # these features are not yet implemented. Please stand by :(
    public $views                = ['list']; #,'thumbnail'
    public $page_count_options   = [10,20,50,100]; 
    public $thumbnail_function   = null;
    public $thumbnail_alignment  = 'justify';
    public $thumbnail_selectable = false;
    public $thumbnail_template   = '<div class=""><img height="75" src="{{thumbnail_src}}" width="65"><span>{{thumbnail_label}}</span></div>';


    public function __construct($title, $id, $url, $data_source, $parameters = [], $columns = [], $filters = [])
    {
        $this->title       = $title;
        $this->id          = $id;
        $this->url         = $url;
        $this->data_source = $data_source;
        $this->parameters  = $parameters;
        foreach($columns as $column)
        {
            $this->add_column($column);
        }
        foreach($filters as $filter)
        {
            $this->add_filter($filter);
        }
    }

    public function add_column($new_column)
    {
        if (!$new_column instanceof interface__lucid_html_grid_column)
        {
            throw new Exception('lucid_html_grid: added columns must implement interface__lucid_html_grid_column. See lucid/lib/php/html/grid.php for interface details.');
        }
        $new_column->parent = $this;
        $new_column->index  = count($this->columns);
        $this->columns[] = $new_column;
        return $this;
    }

    public function add_filter($new_filter)
    {
        if(!$new_filter instanceof interface__lucid_html_grid_filter)
        {
            throw new Exception('lucid_html_grid: added filters must implement interface__lucid_html_grid_filter. See lucid/lib/php/html/grid.php for interface details.');
        }
        $new_filter->parent = $this;
        $this->filters[] = $new_filter;
        return $this;
    }

    /* used to update multiple options at once */
    public function set_config($options=array())
    {
        foreach($options as $name=>$value)
        {
            $this->$name = $value;
        }
    }

    public function render_title()
    {
        $this->html_template_fields['title'] = $this->title;
    }

    public function render_filters()
    {
        $this->html = '';
        if(count($this->filters) > 0)
        {
            foreach($this->filters as $filter)
            {
                $filter->render_html();
            }
        }
        $this->html_template_fields['filters'] = $this->html;
        $this->html = '';
    }

    public function render_table_thead($format = 'html')
    {
        if ($format == 'html')
        {
            $this->html .= '<thead><tr>';
        }
        foreach($this->columns as $column)
        {
            $column->render_header($format);
        }
        if ($format == 'html')
        {
            $this->html .= '</tr></thead>';
        }
    }

    public function get_data()
    {
        # look for parameters in the request
        lucid::log_request();
        
        $this->page_size      = lucid::request($this->id.'-page_size',     $this->page_size);
        $this->page_index     = lucid::request($this->id.'-page_index',    $this->page_index);
        $this->sort_column    = lucid::request($this->id.'-sort_column',   $this->sort_column);
        $this->sort_direction = lucid::request($this->id.'-sort_direction',$this->sort_direction);

        # if no sort column has been specified at this point, loop over the columns and find a sort column
        if (is_null($this->sort_column))
        {
            lucid::log('looking for sort column, previously config was null. looping over columns');
            for ($i = 0;$i < count($this->columns); $i++)
            {
                if ($this->columns[$i]->sortable == true)
                {
                    #lucid::log('column '.$i.' was sortable, using that');
                    $this->sort_column    = $i;
                    $this->sort_direction = 'asc';
                    $i = count($this->columns);
                }
                else
                {
                    #lucid::log('column '.$i.' was NOT sortable');
                }
            }
        }

        # if we have a sort column, apply it:
        if (!is_null($this->sort_column))
        {
            if($this->sort_direction == 'asc')
            {
                $this->data_source->order_by_asc($this->columns[$this->sort_column]->property);
            }
            else
            {
                $this->data_source->order_by_desc($this->columns[$this->sort_column]->property);
            }
        }

        foreach($this->filters as $filter)
        {
            $filter->apply_to_data_source();
        }

        # get the total number of rows without limit/offset
        $this->total_rows = $this->data_source->count();
        $this->final_page = ceil($this->total_rows / $this->page_size) - 1;

        # apply limit/offset, get final data.
        $this->data_source->limit($this->page_size);
        $this->data_source->offset($this->page_size * $this->page_index);
        $this->data = $this->data_source->find_many();
    }

    public function render_table_body($format = 'html')
    {
        
        foreach($this->data as $row)
        {
            if ($format == 'html')
            {
                $this->html .= '<tr>';
            }
            foreach($this->columns as $column)
            {
                $column->render_cell($format,$row);
            }
            if ($format == 'html')
            {
                $this->html .= '</tr>';
            } 
        }
    }

    public function render_pager()
    {
        $this->html = '';

        if ($this->pager_show_first === true)
        {
            $this->html .= '<button type="button" class="btn btn-'.$this->pager_btn_style.$this->pager_btn_size.'" onclick="lucid.html.grid.grids[\''.$this->id.'\'].changePage(\'first\');"><span class="glyphicon glyphicon-step-backward"></span></button>';
        }
        if ($this->pager_show_previous === true)
        {
            $this->html .= '<button type="button" class="btn btn-'.$this->pager_btn_style.$this->pager_btn_size.'" onclick="lucid.html.grid.grids[\''.$this->id.'\'].changePage(\'previous\');"><span class="glyphicon glyphicon-backward"></span></button>';
        }


        if ($this->pager_show_next === true)
        {
            $this->html .= '<button type="button" class="btn btn-'.$this->pager_btn_style.$this->pager_btn_size.'" onclick="lucid.html.grid.grids[\''.$this->id.'\'].changePage(\'next\');"><span class="glyphicon glyphicon-forward"></span></button>';
        }
        if ($this->pager_show_last === true)
        {
            $this->html .= '<button type="button" class="btn btn-'.$this->pager_btn_style.$this->pager_btn_size.'" onclick="lucid.html.grid.grids[\''.$this->id.'\'].changePage(\'last\');"><span class="glyphicon glyphicon-step-forward"></span></button>';
        }

        if ($this->pager_show_selector === true)
        {
            $this->html .= '<button type="button" class="btn btn-'.$this->pager_btn_style.$this->pager_btn_size.' dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span id="'.$this->id.'--page_display">
                Page '.($this->page_index + 1).' of '.($this->final_page + 1).'</span> <span class="caret"></span></button>';
            $this->html .= '<ul class="dropdown-menu" role="menu" id="'.$this->id.'--page-pulldown">';
            for($i=0; $i <= $this->final_page; $i++)
            {
                $this->html .= '<li class="clickable" onclick="lucid.html.grid.grids[\''.$this->id.'\'].changePage('.$i.');"><a>'.lucid::i18n('ui-grid-pager-pageof',($i + 1),($this->final_page + 1)).'</a></li>';
            }
            $this->html .= '</ul>';
        }

        $this->html_template_fields['pager'] = $this->html;
        $this->html = '';
    }



    public function render_content($format = 'html')
    {
        if ($format == 'html')
        {
            # render out the various parts of the grid
            $this->render_title();
            $this->render_filters();
            $this->render_pager($format);
            
            # start the table
            $this->html  = '';
            $this->html .='<table class="table';
            $this->html .= ($this->style_hover === true)?   ' table-hover':'';
            $this->html .= ($this->style_striped === true)? ' table-striped':'';
            $this->html .= ($this->style_bordered === true)?' table-bordered':'';

            # fixed column widths
            $this->html .= '"><colgroup>';
            foreach($this->columns as $column)
            {
                $column->render_width();
            }
            $this->html .= '</colgroup>';

            # build the actual table.
            $this->render_table_thead($format);
            $this->html .= '<tbody>';
            $this->render_table_body($format);
            $this->html .= '</tbody></table>';

            # set the template field
            $this->html_template_fields['table'] = $this->html;
            $this->html = '';

            # update some final keys in the template_fields array just before they're applied
            $this->html_template_fields['id'] = $this->id;
            $this->html_template_fields['style_panel'] = $this->style_panel;

            $final_html = $this->html_template;
            foreach($this->html_template_fields as $key=>$value)
            {
                $final_html = str_replace('{{'.$key.'}}',$value,$final_html);
            }
            return $final_html;
        }        
    }

    public function render_javascript()
    {
        $js_options = [
            'id'                   => $this->id,
            'url'                  => $this->url,
            'parameters'           => $this->parameters,
            'page_size'            => $this->page_size,
            'page_index'           => $this->page_index,
            'sort_column'          => $this->sort_column,
            'sort_direction'       => $this->sort_direction,
            'total_rows'           => $this->total_rows,
            'final_page'           => $this->final_page,
            'thumbnail_selectable' => $this->thumbnail_selectable,
            'thumbnail_template'   => $this->thumbnail_template,
        ];

        $this->js = '(new lucid.html.grid('.json_encode($js_options).'));';
        foreach($this->filters as $filter)
        {
            $this->js .= $filter->render_javascript();
        }
        return $this->js;
    }

    public function render($format = 'html')
    {
        # this sets all properties needed for the query and retrieves the data.
        # this must be done before the javascript is generated as the javascript must contain
        # the sort column/direction
        $this->get_data();
        if ($format == 'html')
        {
            lucid::javascript($this->render_javascript());
        }
        return $this->render_content($format);
    }

    public function build_data_return()
    {
        $this->render_table_body('html');
        lucid::replace('#'.$this->id.' > div > table.table > tbody',$this->html);
        $new_properties = [
            'page_size'  => $this->page_size,
            'page_index' => $this->page_index,
            'total_rows' => $this->total_rows,
            'final_page' => $this->final_page,
        ];
        lucid::javascript('lucid.html.grid.grids[\''.$this->id.'\'].updateProperties('.json_encode($new_properties).');');
    }

    public function handle_return()
    {
        if(lucid::request('action') == $this->url)
        {
            $this->get_data();
            lucid::special($this->id,$this->build_data_return());
        }
        lucid::view_return($this);
    }
}

?>
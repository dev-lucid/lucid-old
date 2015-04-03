<?php
/* This class is used to create a fuelex repeater. */

class lucid_html_repeater
{
    # passed as parameters
    public $title              = null;
    public $id                 = null;
    public $url                = null;
    public $data_source        = null;
    public $columns            = [];

    # used for output
    public $html               = '';
    public $js                 = '';

    # options!
    public $static_height        = null;
    public $filters              = [];
    public $views                = ['list','thumbnail'];
    public $page_count_options   = [10,20,50,100];
    public $thumbnail_function   = null;
    public $thumbnail_alignment  = 'justify';
    public $thumbnail_selectable = false;
    public $thumbnail_template   = '<div class="thumbnail repeater-thumbnail"><img height="75" src="{{thumbnail_src}}" width="65"><span>{{thumbnail_label}}</span></div>';


    function __construct($title='Data',$id=null,$url=null,$data_source=null,$columns = [])
    {
        $this->title       = $title;
        $this->id          = $id;
        $this->url         = $url;
        $this->data_source = $data_source;
        foreach($columns as $column)
        {
            $this->add_column($column);
        }
        lucid::log('repeater created');
    }

    /* used to update multiple options at once */
    function set_config($options=array())
    {
        foreach($options as $name=>$value)
        {
            $this->$name = $value;
        }
    }

    public function header()
    {
        $this->html .= '<div class="repeater-header">';
        $this->header_left();
        $this->header_right();
        $this->html .= '</div>';
    }

    public function header_left()
    {
        $this->html .= '<div class="repeater-header-left">';
        $this->header_title();
        $this->html .= '</div>';
    }

    public function header_title()
    {
        $this->html .= '<span class="repeater-title">'.$this->title.'</span>';
    }

    public function header_right()
    {
        $this->html .= '<div class="repeater-header-right">';
        $this->header_filters();
        $this->header_views();
        $this->html .= '</div>';
    }

    public function header_filters()
    {
        $this->html .= '<div class="btn-group repeater-views" data-toggle="buttons">';
        foreach($this->filters as $filter)
        {
            $this->html .= $filter->render($this);
        }
        $this->html .= '</div>';
    }

    public function header_views()
    {
        if(count($this->views) > 0)
        {
            $this->html .= '<div class="btn-group repeater-views" data-toggle="buttons">';
            foreach($this->views as $view)
            {
                switch($view)
                {
                    case 'list':
                        $this->html .= '<label class="btn btn-default active"><input name="repeaterViews" type="radio" value="list" /><span class="glyphicon glyphicon-list"></span></label>';
                        break;
                    case 'thumbnail':
                        $this->html .= '<label class="btn btn-default"><input name="repeaterViews" type="radio" value="thumbnail" /><span class="glyphicon glyphicon-th"></span></label>';
                        break;
                }
            }
            $this->html .= '</div>';
        }
    }

    public function viewport()
    {
        $this->html .= '<div class="repeater-viewport">';
        $this->html .= '<div class="repeater-canvas"></div>';
        $this->html .= '<div class="loader repeater-loader"></div>';
        $this->html .= '</div>';
    }

    public function footer()
    {
        $this->html .= '<div class="repeater-footer">';
        $this->footer_left();
        $this->footer_right();
        $this->html .= '</div>';
    }

    public function footer_left()
    {
        $this->html .= '<div class="repeater-footer-left">';
        $this->footer_itemization();
        $this->html .= '</div>';
    }

    public function footer_itemization()
    {
        $this->html .= '<div class="repeater-itemization">';
        $this->footer_itemization_count();
        $this->footer_itemization_per_page_selector();
        $this->html .= '</div>';
    }

    public function footer_itemization_count()
    {
        $this->html .= '<span><span class="repeater-start"></span> - <span class="repeater-end"></span> of <span class="repeater-count"></span> items</span>';
    }

    public function footer_itemization_per_page_selector()
    {
        $this->html .= '<div class="btn-group selectlist" data-resize="auto">';
        $this->html .= '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">';
        $this->html .= '<span class="selected-label">&nbsp;</span>';
        $this->html .= '<span class="caret"></span>';
        $this->html .= '<span class="sr-only">Toggle Dropdown</span>';
        $this->html .= '</button>';

        $this->html .= '<ul class="dropdown-menu" role="menu">';
        foreach($this->page_count_options as $option)
        {
            $this->html .= '<li data-value="'.$option.'"><a class="clickable">'.$option.'</a></li>';
        }
        $this->html .= '</ul>';

        $this->html .= '<input class="hidden hidden-field" name="itemsPerPage" readonly="readonly" aria-hidden="true" type="text" />';
        $this->html .= '</div>';
        $this->html .= '<span>Per Page</span>';
    }

    public function footer_right()
    {
        $this->html .= '<div class="repeater-footer-right">';
        $this->footer_pagination();
        $this->html .= '</div>';
    }

    public function footer_pagination()
    {
        $this->html .= '<div class="repeater-pagination">';
        $this->footer_pagination_btn_previous();
        $this->footer_pagination_selector();
        $this->footer_pagination_btn_next();
        $this->html .= '</div>';
    }

    public function footer_pagination_selector()
    {
        $this->html .= '<label class="page-label" id="myPageLabel">Page</label>';

        $this->html .= '<div class="repeater-primaryPaging active">';
        $this->html .= '<div class="input-group input-append dropdown combobox">';
        $this->html .= '<input type="text" class="form-control" aria-labelledby="myPageLabel">';
        $this->html .= '<div class="input-group-btn">';
        $this->html .= '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">';
        $this->html .= '<span class="caret"></span>';
        $this->html .= '<span class="sr-only">Toggle Dropdown</span>';
        $this->html .= '</button>';
        $this->html .= '<ul class="dropdown-menu dropdown-menu-right"></ul>';
        $this->html .= '</div>';
        $this->html .= '</div>';
        $this->html .= '</div>';

        $this->html .= '<input type="text" class="form-control repeater-secondaryPaging" aria-labelledby="myPageLabel" />';
        $this->html .= '<span>of <span class="repeater-pages"></span></span>';
    }

    public function footer_pagination_btn_previous()
    {
        $this->html .= '<button type="button" class="btn btn-default btn-sm repeater-prev">';
        $this->html .= '<span class="glyphicon glyphicon-chevron-left"></span>';
        $this->html .= '<span class="sr-only">Previous Page</span>';
        $this->html .= '</button>';
    }

    public function footer_pagination_btn_next()
    {
        $this->html .= '<button type="button" class="btn btn-default btn-sm repeater-next">';
        $this->html .= '<span class="glyphicon glyphicon-chevron-right"></span>';
        $this->html .= '<span class="sr-only">Next Page</span>';
        $this->html .= '</button>';
    }

    public function render_html()
    {
        $this->html .= '<div class="fuelux">';
        $this->html .= '<div class="repeater" id="'.$this->id.'"';
        if(!is_null($this->static_height))
        {
            $this->html .= ' data-staticheight="'.$this->static_height.'"';
        }
        $this->html .= '>';

        $this->header();
        $this->viewport();
        $this->footer();

        $this->html .= '</div></div>';
        return $this->html;
    }

    public function render_javascript()
    {
        $all_columns = [];
        foreach($this->columns as $column)
        {
            $all_columns[] = $column->get_js_object();
        }
        $js_options = [
            'id'=>$this->id,
            'url'=>$this->url,
            'columns'=>$all_columns,
            'thumbnail_selectable'=>$this->thumbnail_selectable,
            'thumbnail_template'=>$this->thumbnail_template,
        ];

        $this->js = 'lucid.html.repeater.setup('.json_encode($js_options).');';
        return $this->js;
    }

    public function add_column($new_column)
    {
        $new_column->parent = $this;
        $this->columns[] = $new_column;
        return $this;
    }

    public function get_data()
    {
        # get the count of records without limit/offset
        $total = $this->data_source->count();

        # get sort/limit/filter/offset from url, perform the query
        $this->data_source->limit($_REQUEST['pageSize']);
        $this->data_source->offset($_REQUEST['pageIndex'] * $_REQUEST['pageSize']);
        if(isset($_REQUEST['sortBy']))
        {
            $sort_function = ($_REQUEST['sortDirection'] == 'asc')?'order_by_asc':'order_by_desc';
            $this->data_source->$sort_function($_REQUEST['sortBy']);
        }
        $results = $this->data_source->find_many();

        # compile the final data, using the column rendering functions
        $data = [];
        foreach($results as $result)
        {
            $row = [];
            foreach($this->columns as $column)
            {
                $row[$column->property] = $column->render($result);
            }

            if(!is_null($this->thumbnail_function))
            {
                $thumbnail_function = $this->thumbnail_function;
                $fields = $thumbnail_function($this,$result);
                foreach($fields as $name=>$value)
                {
                    $row[$name] = $value;
                }
            }
            $data[] = $row;
        }
      
        return array(
            'total'=>$total,
            'items'=>$data
        );
    }
}

?>
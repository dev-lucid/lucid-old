<?php

class lucid_html_grid_column implements interface__lucid_html_grid_column
{
    # passed as parameters
    public $label           = null;
    public $property        = null;
    public $sortable        = null;
    public $width           = null;
    public $render_function = null;

    # set via the lucid_html_grid add_column function
    public $parent          = null;
    public $index           = null;

    public function __construct($label, $property, $sortable = false, $width = null, $render_function = null)
    {
        $this->label           = $label;
        $this->property        = $property;
        $this->sortable        = $sortable;
        $this->width           = $width;
        $this->render_function = $render_function;
    }

    public function render_width()
    {
        if(is_null($this->width))
        {
            $this->parent->html .= '<col width="1%" />';
        }
        else
        {
            $this->parent->html .= '<col width="'.$this->width.'" />';
        }
    }

    public function render_header($format = 'html')
    {
        if ($format == 'html')
        {
            $this->parent->html .= '<th';

            if($this->sortable == true)
            {
                $this->parent->html .= ' class="clickable lucid-html-grid-sort';

                if($this->parent->sort_column == $this->index)
                {
                    $this->parent->html .= ' lucid-html-grid-sort-'.$this->parent->sort_direction;
                }


                $this->parent->html .= '" onclick="lucid.html.grid.grids[\''.$this->parent->id.'\'].resort('.$this->index.',this);"';
            }
            else
            {
                $this->parent->html .= ' class="lucid-html-grid-nosort"';
            }

            $this->parent->html .= '>';
        }
        
        $this->parent->html .= $this->label;

        if ($format == 'html')
        {
            $this->parent->html .= '</th>';
        }
    }

    public function render_cell($format = 'html',$data=array())
    {
        if ($format == 'html')
        {
            $this->parent->html .= '<td>';
        }
        
        if(is_null($this->render_function))
        {
            $this->parent->html .= $data->{$this->property};
        }
        else
        {
            $render_func = $this->render_function;
            $this->parent->html .= $render_func($this,$format,$data);
        }

        if ($format == 'html')
        {
            $this->parent->html .= '</td>';
        }        
    }
}

?>
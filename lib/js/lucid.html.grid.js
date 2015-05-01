lucid.html.grid = function(options){
    this.filters = [];
    this.filterValues = {};
    for (var key in options){
        this[key] = options[key];
    };
    lucid.html.grid.grids[this.id] = this;
};
lucid.html.grid.grids={};

lucid.html.grid.prototype.addFilter=function(newFilter){
    newFilter.parent = this;
    newFilter.index  = this.filters.length;
    this.filters.push(newFilter);
    return this;
}

lucid.html.grid.prototype.changePage=function(newPage){
    var doReload = true;
    switch(newPage){
        case 'first':
            if(this.page_index == 0){
                console.log('lucid.html.grid.changePage: passed value first, was already on first page');
                console.log(this);
                doReload = false;
            }else{
                this.page_index = 0;
            }
            break;
        case 'previous':
            if(this.page_index == 0){
                console.log('lucid.html.grid.changePage: passed value previous, tried to access page -1');
                console.log(this);
                this.page_index = 0;
                doReload = false;
            }else{
                this.page_index--;
            }
            break;
        case 'next':
            this.page_index++;
            if(this.page_index > this.final_page){
                console.log('lucid.html.grid.changePage: passed value next, tried to access page beyond the final page in data');
                console.log(this);
                this.page_index = this.final_page;
                doReload = false;
            }
            break;
        case 'last':
            if(this.page_index == this.final_page){
                console.log('lucid.html.grid.changePage: passed value last, was already on last page');
                console.log(this);
                doReload = false;
            }else{
                this.page_index = this.final_page;
            }
            break;
        default:
            // this is called when a specific page number is passed as the parameter
            if(this.page_index == newPage || newPage > this.final_page){
                console.log('lucid.html.grid.changePage: passed value '+newPage+', invalid value. Value must be != .page_index, <= .final_page');
                console.log(this);
                doReload = false;
            }else{
                this.page_index = newPage;
            }
            break;
    }

    if(doReload){
        this.updatePager();
        this.reloadData();
    }
};


lucid.html.grid.prototype.resort=function(newCol,colObj){
    if(newCol == this.sort_column){
        this.sort_direction = (this.sort_direction == 'asc')?'desc':'asc';
    }else{
        this.sort_direction = 'asc';
    }
    var tr = jQuery(colObj).parent();
    tr.find('th:nth-child('+(this.sort_column + 1)+')').removeClass('lucid-html-grid-sort-asc').removeClass('lucid-html-grid-sort-desc');
    tr.find('th:nth-child('+(newCol + 1)+')').addClass('lucid-html-grid-sort-'+this.sort_direction);
    this.sort_column = newCol;

    this.page_index = 0;
    this.updatePager();
    this.reloadData();
};

lucid.html.grid.prototype.updateProperties=function(newProperties){
    for(var key in newProperties){
        this[key] = newProperties[key];
    }
    this.updatePager();
};

lucid.html.grid.prototype.updatePager=function(){
    var newText = 'Page '+(parseInt(this.page_index) + 1) +' of '+(parseInt(this.final_page) + 1);
    jQuery('#'+this.id+'--page_display').html(newText);
};

lucid.html.grid.prototype.page0=function(){
    this.page_index = 0;
    return this;
}

lucid.html.grid.prototype.delayedReload=function(){
    window.clearTimeout(this.reloadTimeout);
    this.reloadTimeout = window.setTimeout('lucid.html.grid.grids[\''+this.id+'\'].page0().reloadData();',800);
}

lucid.html.grid.prototype.reloadData=function(){
    this.dataForReload = this.parameters;
    this.dataForReload[this.id+'-page_index']     = this.page_index;
    this.dataForReload[this.id+'-page_size']      = this.page_size;
    this.dataForReload[this.id+'-sort_column']    = this.sort_column;
    this.dataForReload[this.id+'-sort_direction'] = this.sort_direction;

    for(var i=0; i<this.filters.length; i++){
        this.filters[i].getValue();
    }
    lucid.request(this.url,this.dataForReload,false);
};

lucid.html.grid.filter = function(){

};

lucid.html.grid.filter.search = function(idSuffix){
    this.idSuffix = idSuffix;
};
lucid.html.grid.filter.search.prototype=new lucid.html.grid.filter();

lucid.html.grid.filter.search.prototype.getValue=function(){
    this.parent.dataForReload[this.parent.id+'-'+this.idSuffix] = jQuery('#'+this.parent.id+'-'+this.idSuffix).val();
};

lucid.html.grid.filter.select = function(column){
    this.column = column;
};
lucid.html.grid.filter.select.prototype=new lucid.html.grid.filter();

lucid.html.grid.filter.select.prototype.getValue=function(){
    this.parent.dataForReload[this.parent.id+'-'+this.column] = jQuery('#'+this.parent.id+'-'+this.column).val();
};

lucid.html.grid.filter.checkbox = function(name){
    this.name = name;
};
lucid.html.grid.filter.checkbox.prototype=new lucid.html.grid.filter();

lucid.html.grid.filter.checkbox.prototype.getValue=function(){
    this.parent.dataForReload[this.parent.id+'-'+this.name] = (jQuery('#'+this.parent.id+'-'+this.name).is(':checked'))?'yes':'no';
};

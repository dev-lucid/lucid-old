lucid.html = {};

lucid.html.repeater={};

lucid.html.repeater.dataSource=function(options,callback){
    console.log('lucid.html.repeater.dataSource called, options are:');
    console.log(options);
    console.log(this);

    var columns = this.columns;
    var options = {
        'pageIndex'     : options.pageIndex,
        'pageSize'      : options.pageSize,
        'sortDirection' : options.sortDirection,
        'sortBy'        : options.sortProperty,
        'filterBy'      : options.filter.value || '',
        'searchBy'      : options.search || '',
        'id'            : this.repeaterId
    };
    $.ajax({
        'type': 'post',
        'url': 'app.php?action='+this.url,
        'data': options
    }).done(function(data) {
        // get the returned data out of the special hash using the id as the key
        data = data.special[options.id];        

        var startIndex = ((options.pageIndex * options.pageSize) + 1)
        var endIndex   = (startIndex + options.pageSize) - 1;
        endIndex = (endIndex > data.total)?data.total:endIndex;

        // configure datasource
        var dataSource = {
            'page':    options.pageIndex,
            'pages':   Math.ceil(data.total / options.pageSize),
            'count':   data.total,
            'start':   startIndex,
            'end':     endIndex,
            'columns': columns,
            'items':   data.items
        };

        // pass the datasource back to the repeater callback
        callback(dataSource);
    });
};

lucid.html.repeater.setup=function(options){
    console.log('lucid.html.repeater.setup: setting up repeater!');
    
    $(function() {
        // initialize the repeater
        $('#'+options.id+'').repeater({
            // setup your custom datasource to handle data retrieval;
            // responsible for any paging, sorting, filtering, searching logic
            'dataSource' : lucid.html.repeater.dataSource,
            'repeaterId' : options.id,
            'url'        : options.url,
            'columns'    : options.columns,
            'thumbnail_selectable': options.thumbnail_selectable,
            'thumbnail_template': options.thumbnail_template
        });
    });
};


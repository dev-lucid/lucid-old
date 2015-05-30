var lucid = {config:{}};
lucid.config.navState = {};

lucid.init=function(){
};

lucid.sendAnalytics=function(newUrl){
    if(typeof(ga) == 'function'){
        ga('send', 'pageview', {'page': newUrl});
    }
};

lucid.request=function(todo,data,sendAnalytics,customHandler){
    var successFuncs = [lucid.ajaxSuccess];
    if(sendAnalytics === true){
        lucid.sendAnalytics(todo);
    }
    if(typeof(customHandler) == 'function'){
        successFuncs.push(customHandler);
    }
    var timestamp = (new Date()).valueOf();
    if(typeof(data) != 'object'){
        data = {};
    }
    data['_nav_state'] = lucid.config.navState;

    console.log('lucid/lib/js/lucid.js::lucid.request: sending request with data:');
    console.log(data);

    jQuery.ajax(
        'app.php?action='+todo+'&_time='+timestamp,{
            'success':successFuncs,
            'error':lucid.ajaxError,
            'data':data,
            'contentType': "application/x-www-form-urlencoded",
            'dataType':'json',
            'method':'POST'
        }
    );
};

lucid.ajaxSuccess=function(response,status,request){
    var start_time = response.start_time;
    if(response.success !== true){
        lucid.ajaxError(response,response.message,response)
    }else{
        lucid.handleResponse(response);
    }
};

lucid.ajaxError=function(response,message,errorDetails){
    console.log('lucid/lib/js/lucid.js::ajaxError: '+message);
};

lucid.handleResponse=function(response){
    console.log('lucid/lib/js/lucid.js::handleResponse: called, preparing to handle content updates');
    console.log(response);

    lucid.lastResponse = response;
    try{
        for(var selector in response.append){
            jQuery(selector).append(response.append[selector]);
        }
        for(var selector in response.prepend){
            jQuery(selector).prepend(response.prepend[selector]);
        }

        var newMode = false;
        for(var selector in response.replace){
            /*
            var thisMode = lucid.checkContentDisplayMode(selector)
            if(thisMode !== false){
                newMode = thisMode;
            }
            */
            /*
            if(typeof(lucid.contentAreaLastReplace[selector]) != 'number')
                lucid.contentAreaLastReplace[selector] = 0;
            if(response.end_time > lucid.contentAreaLastReplace[selector]){
                lucid.contentAreaLastReplace[selector] = response.end_time;
                jQuery(selector).html(response.replace[selector]);
            }else{
                console.log('did NOT update '+selector+', request received out of order');
            }
            */
            jQuery(selector).html(response.replace[selector]);
        }
        if(typeof(response.title) == 'string')
            jQuery('title').html(response.title);
        if(typeof(response.keywords) == 'string')
            jQuery('meta[name=keywords]').attr('keywords', response.keywords);
        if(typeof(response.description) == 'string')
            jQuery('meta[name=description]').attr('description', response.description);

        /*
        if(newMode !== false && newMode != lucid.config.contentDisplayMode){
            lucid.changeDisplayMode(lucid.config.contentDisplayMode,newMode);
            lucid.config.contentDisplayMode = newMode;
        }
        */
    }
    catch(e){
        console.log('lucid/lib/js/lucid.js::handleResponse: Exception occured when trying to handle response from server: ');
        console.log(e);
    }
    try{
        if(response.javascript !== ''){
            eval(response.javascript);
        }
    }
    catch(e){
        /*
        var idx = 'error_'+(new Date().valueOf());
        lucid.jserrors[idx] = response.javascript;
        console.log('Error in javascript eval on line '+e.line+': '+e.message);
        console.log('To retrieve full source of executed code: lucid.jserrors.'+idx);
        lucid.request('lucid_jserror/record_error',{
            'line':e.line,
            'message':e.message,
            'source':response.javascript
        });
        */
    }
};

lucid.changeDisplayMode=function(oldMode,newMode){
    console.log('lucid/lib/js/lucid.js::changeDisplayMode: changing display mode to '+newMode+' from '+oldMode);
    jQuery(oldMode).fadeOut(100);
    jQuery(newMode).fadeIn(500);
};

lucid.checkContentDisplayMode=function(selector){
    for(var triggerArea in lucid.config.displayModeTriggers){
        var selectors = lucid.config.displayModeTriggers[triggerArea]
        for(var i=0;i<selectors.length;i++){
            if(selectors[i] == selector){
                return triggerArea;
            }
        }
    }
    return false;
};

lucid.showError=function(){

};

lucid.redirect=function(newUrl){
    console.log('lucid/lib/js/lucid.js::redirect:: new url is: '+newUrl);
    location.href=newUrl;
};

lucid.handleHashchange=function(){
    /*
    var newLink = jQuery('body').find('a[href=\''+window.location.hash+'\']');
    newLink.each(function(){
        var thisLink = jQuery(this);
        var curLink = thisLink.parent().parent().find('li.active');
        curLink.each(function(){
            jQuery(this).removeClass('active');
        });
        thisLink.parent().addClass('active');
    });
    */
    var urlData  = {};
    var urlParts = (new String(window.location.hash).replace('#!','')).split('|');
    console.log(urlParts);
    newUrl = urlParts[0];
    for(var i=1;i<urlParts.length;i+=2){
        urlData[urlParts[i]] = decodeURIComponent(urlParts[i + 1]);
    }

    lucid.request(newUrl,urlData,true);
};

window.onhashchange = lucid.handleHashchange;
if(new String(location.hash)+'' !== ''){
    lucid.handleHashchange();
}else{
    window.location.hash = '#!view/index';
}
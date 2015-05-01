lucid.form={};

lucid.form.errorTemplate = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>{{title}}</strong> {{msg}}</div>';

lucid.form.showErrors=function(formObj,errorList){
    console.log('lucid/lib/js/lucid.form::showErrors: called for form '+formObj);
    console.log(errorList);
    if(typeof(formObj) == 'string'){
        var formObj = document.forms[formObj]||document.getElementById(formObj);  
    }
    var $formObj = jQuery(formObj);
    var errorArea = $formObj.find('.form-errors');

    // no error area exists, create one
    if(errorArea.length == 0){
        html = '<div class="form-errors">test</div>';
        $formObj.prepend(html);
        errorArea = $formObj.find('.form-errors');
    }

    var finalErrorHtml = new String(lucid.form.errorTemplate);
    var errorMsgHtml = '';
    for(var field in errorList){
        for(var i=0;i<errorList[field].length;i++){
            errorMsgHtml += errorList[field][i] + '<br />';
        }
    }
    console.log(errorMsgHtml);
    finalErrorHtml = finalErrorHtml.replace('{{title}}','The following errors occured:<br />');
    finalErrorHtml = finalErrorHtml.replace('{{msg}}',errorMsgHtml);
    errorArea.html(finalErrorHtml);    
};

lucid.form.submit=function(formObj){
    var $formObj = jQuery(formObj);

    // revise the form a bit to submit through a hidden iframe. This is necessary for file uploads
    if(lucid.form.iframeForSubmitCreated !== true){
        $('<iframe id="lucidSubmitIframe" name="lucidSubmitIframe" style="position: absolute;left: -20px;top: -20px;height:15px;width:15px;opacity:0%;">').appendTo('body');
        lucid.form.iframeForSubmitCreated = true;
    }
    
    // Set some properties of the form. This ensures that the form will upload files correctly
    $formObj.attr('target','lucidSubmitIframe');
    $formObj.attr('method','post');
    $formObj.attr('enctype','multipart/form-data');

    

    // try to find a form identifier. This is used to use the right validation rules
    var formIdentifier = formObj.id||formObj.getAttribute('name');
    if(typeof(formIdentifier) == 'undefined'){
        formIdentifier = 'form'+(new Date().valueOf());
    }
    var action = new String($formObj.attr('action'));
    if(typeof(action) == 'undefined'){
        console.log("lucid/lib/js/lucid.form.js::submit: could not find an action for form "+formIdentifier+". This form cannot be submitted via lucid.form.submit without an action attribute.");
        return false;
    }
    
    // including this hidden field changes how the server sends back json responses. Including it will cause the server to assume the request was submitted via an iframe
    if(!formObj['_form_identifier']){
        $formObj.append('<input type="hidden" name="_form_identifier" value="' + formIdentifier + '" />');
    }
    
    // change the action of the form to the right url
    if(action.substring(0,7) != 'app.php'){
        action = 'app.php?action=' + action;
        $formObj.attr('action',action);
    }
    
    // we need to build an array of all the form data to send to the validation library
    var formData = {};
    console.log($formObj.serializeArray());
    $.each($formObj.serializeArray(), function(_, kv) {
        if (formData.hasOwnProperty(kv.name)) {
            formData[kv.name] = $.makeArray(formData[kv.name]);
            formData[kv.name].push(kv.value);
        }else {
            formData[kv.name] = kv.value;
        }
    });
    
    console.log("lucid/lib/js/lucid.form.js::submit: Final form data to validate:");
    console.log(formData);
 
    // default to ok, set to false if it fails validation
    var okForSubmit = true;

    // check for a ruleset. If one exists, test it to see if submit is allowed
    if(typeof(formIdentifier) == 'undefined'){
        console.log("lucid/lib/js/lucid.form.js::submit: could not find an identifier for a form. That's ok, but you won't be able to use rulesets/rules without either an id or a name attribute on the form tag. ");
    }else{
        var ruleset = lucid.ruleset.rulesets[formIdentifier+''];
        if(typeof(ruleset) == 'object'){
            var results = ruleset.validate(formData);
            if(results !== true){
                okForSubmit = false;
                lucid.form.showErrors(formObj,results);
            }
        }else{
            console.log('lucid/lib/js/lucid.form.js::submit: could not find a ruleset for form '+formIdentifier+'. Consider adding one!');
        }
    }

    return okForSubmit;
};

lucid.form.selectOptionsRefresh=function(selObj){
    var obj = jQuery(selObj);
    var url = obj.attr('data-url');
    var data = {
        'table':obj.attr('data-table'),
        'filter_value':obj.val()
    };
    lucid.request(url,data,false,function(data, textStatus, jqXHR){
        var newSelObj       = jQuery(selObj.form[obj.attr('data-change-field')]);
        var newSelObjFrmGrp = lucid.form.findFormGroup(newSelObj);
        if ('new_options' in data.special){
            newSelObj.html(data.special.new_options);
            newSelObjFrmGrp.show();
        }else{
            newSelObj.html('<option value=""></option>');
            newSelObjFrmGrp.hide();
        }
    });
};

lucid.form.findFormGroup=function(inputObj){
    var notFound = true;
    var objToTest = inputObj;
    while(notFound){
        objToTest = objToTest.parent();
        if(typeof(objToTest) == 'undefined'){
            return false;
        }else if(objToTest.hasClass('form-group')){
            return objToTest;
        }
    }
    return false;
};



lucid.form={};

lucid.form.submit=function(formObj){
    var $formObj = jQuery(formObj);
    var action = $formObj.attr('action');
    var formIdentifier = formObj.id||formObj.name;
    if(typeof(action) == 'undefined'){
        console.log("lucid/lib/js/lucid.form.js::submit: could not find an action for form "+formIdentifier+". This form cannot be submitted via lucid.form.submit without an action attribute.");
        return false;
    }
    
    var formData = {};
    $.each($formObj.serializeArray(), function(_, kv) {
        if (formData.hasOwnProperty(kv.name)) {
            formData[kv.name] = $.makeArray(formData[kv.name]);
            formData[kv.name].push(kv.value);
        }else {
            formData[kv.name] = kv.value;
        }
    }); 
    console.log("lucid/lib/js/lucid.form.js::submit: Final form data is:");
    console.log(formData); 
    
    // default to ok, set to false if it fails validation
    var okForSubmit = true;

    // check for a ruleset. If one exists, test it to see if submit is allowed
    if(typeof(formIdentifier) == 'undefined'){
        console.log("lucid/lib/js/lucid.form.js::submit: could not find an identifier for a form. That's ok, but you won't be able to use rulesets/rules without either an id or a name attribute on the form tag. ");
    }else{
        var ruleset = lucid.ruleset.rulesets[formIdentifier+''];
        if(typeof(ruleset) == 'object'){
            var results = ruleset.checkData(formData);
            if(results !== true){
                okForSubmit = false;
                lucid.showErrors(formObj,results);
            }
        }else{
            console.log('lucid/lib/js/lucid.form.js::submit: could not find a ruleset for form '+formIdentifier+'. Consider adding one!');
        }
    }

    if(okForSubmit){
        lucid.request(action,formData);
    }

    return false;
};
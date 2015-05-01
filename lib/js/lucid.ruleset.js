lucid.ruleset={};
lucid.ruleset=function(){
    this.rules = arguments;
};

lucid.ruleset.prototype.validate=function(data){
    var errors = {};
    var errorCount = 0;
    for(var i=0; i < this.rules.length; i++){
        var result = this.rules[i].validate(data);
        if (result !== true){
            if(typeof(errors[this.rules[i].field]) == 'undefined'){
                errors[this.rules[i].field] = [];
            }
            errorCount++;
            errors[this.rules[i].field].push(this.rules[i].message);
        }
    }
    return (errorCount === 0)?true:errors;
};

lucid.ruleset.rulesets={};
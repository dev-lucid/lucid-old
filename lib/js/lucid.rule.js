lucid.rule=function(field, type, message, parameters){
    this.field      = field;
    this.type       = type;
    this.message    = message;
    this.parameters = parameters;
};

lucid.rule.prototype.validate=function(data){
    var value = data[this.field];
    switch(this.type)
    {
        case 'length':
            if('min' in this.parameters && new String(value) < this.parameters['min']){
                return false;
            }
            if('max' in this.parameters && new String(value) > this.parameters['max']){
                return false;
            }
            return true;
            break;
        case 'email':
            return (/.+\@.+\..+/.test(value) == true);
            break;
    }
};

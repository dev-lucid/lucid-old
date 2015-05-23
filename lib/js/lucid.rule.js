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
        case 'match':
            var match_value = data[this.parameters['match']];
            console.log('field_match: '+value +'/'+match_value);
            return (value === match_value);
            break;
        case 'length':
            if('min' in this.parameters && new String(value).length < this.parameters['min']){
                return false;
            }
            if('max' in this.parameters && new String(value).length > this.parameters['max']){
                return false;
            }
            return true;
            break;
        case 'email':
            return (/.+\@.+\..+/.test(value) == true);
            break;
    }
};

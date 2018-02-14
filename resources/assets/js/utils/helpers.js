import Vue from 'vue';

export function processSettings(rawSettings) {
    let processedSettings = {}

    for (var j = 0; j < rawSettings.length; j++) {
        let setting = rawSettings[j]
        let key = setting.key
        // let initProp = 'init' + key.charAt(0).toUpperCase() + key.slice(1)

        if(setting.type == 'boolean') {
            processedSettings[key] = stringToBoolean(setting.value)
            // console.log(initProp+': '+this.stringToBoolean(setting[j].value))
        }
        else if(setting.type == 'number') {
            processedSettings[key] = Number(setting.value)
            // console.log(initProp+': ' + Number(setting[j].value))
        }
        else {
            processedSettings[key] = setting.value
            // console.log(initProp+': ' + blockSettings[j].value)
        }
    }

    return processedSettings
}

export function getComponentByName(name) {
    let globalComponents = Object.getPrototypeOf(Vue.options.components)

    return globalComponents[name]
        ? globalComponents[name]
        : undefined
}

export function processSettingsConfig(name) {
    let globalComponents = Object.getPrototypeOf(Vue.options.components)
    let component = globalComponents[name]||undefined

    return component
        ? component.options.customSettings
        : undefined
}

function stringToBoolean(string){
    switch(string.toLowerCase().trim()){
        case "true": case "yes": case "1": return true;
        case "false": case "no": case "0": case null: return false;
        default: return Boolean(string);
    }
}

/**
 * Deep diff between two object, using lodash
 * @param  {Object} object Object compared
 * @param  {Object} base   Object to compare with
 * @return {Object}        Return a new object who represent the diff
 */
export function difference(object, base) {
    function changes(object, base) {
        return _.transform(object, function(result, value, key) {
            if (!_.isEqual(value, base[key].default)) {
                result[key] = (_.isObject(value) && _.isObject(base[key].default)) ? changes(value, base[key].default) : value;
            }
        });
    }
    return changes(object, base);
}

/**
 * Deep diff between component settings, using lodash
 * @param  {Object} object Object compared
 * @param  {Object} base   Object to compare with that holds default values of component
 * @return {Object}        Return a new object who represent the diff
 */
export function differenceSettings(object, base) {
    // console.log(object)
    // console.log(base)
    function changes(object, base) {
        return _.transform(object, function(result, value, key) {
            if (!_.isEqual(value, base[key]['default'])) {
                result[key] = (_.isObject(value) && _.isObject(base[key]['default'])) ? changes(value, base[key]['default']) : value;
            }
        });
    }
    return changes(object, base);
}

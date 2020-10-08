window.Helper = {};

window.Helper.callback = function(callback, args, scope) {
    if (!callback) {
        return;
    }
    if (!args) {
        if (scope) {
            callback.call(scope);
        }
        else {
            callback();
        }
        return;
    }
    if (args instanceof Array) {
        if (scope) {
            callback.apply(scope, args);
        }
        else {
            callback.apply(null, args);
        }
        return;
    }
    if (scope) {
        callback.call(scope, args);
    }
    else {
        callback(args);
    }
};

window.Helper.callService = function(serviceName, args, callback, scope) {
    args = args || {};
    args._token = $('meta[name="csrf-token"]').attr('content');
    if (serviceName.includes(".") && !args.hasOwnProperty('action')) {
        [serviceName, args.action] = serviceName.split(".");
    }
    $.post(`${window.location.origin}/${serviceName}`, args, function(result) {
        Helper.callback(callback, result, scope);
    })
};


window.Helper.getSession = function(key, callback, scope) {
    Helper.callService("sessionService", {
        method: "get",
        key: key
    }, function(data) {
        Helper.callback(callback, data.value, scope);
    });
};

window.Helper.setSession = function(key, value, callback, scope) {
    Helper.callService("sessionService", {
        method: "set",
        key: key,
        value: value
    }, callback, scope);
};

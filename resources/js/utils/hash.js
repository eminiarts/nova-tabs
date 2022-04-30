
export function parseLocationHash() {
    var hash = window.location.hash;
    return hash.split('&').reduce(function (res, item) {
        var parts = decodeURIComponent(item).split('=');
        if (parts[0].length) {
            res[parts[0]] = parts[1];
        }
        return res;
    }, {});
}
export function updateLocationHash(data) {
    var out = [];
        for (var key in data) {
        if (data.hasOwnProperty(key)) {
            out.push(key + '=' + encodeURIComponent(data[key]));
        }
    }
    window.location.hash = out.join('&')
}

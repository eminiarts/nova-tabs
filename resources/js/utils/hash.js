
export function parseLocationHash() {
  const hash = window.location.hash;
  return hash.split('&').reduce(function (res, item) {
    const parts = decodeURIComponent(item).split('=');
    if (parts[0].length) {
            res[parts[0].replace('#', '')] = parts[1];
        }
        return res;
    }, {});
}
export function updateLocationHash(data) {
  const out = [];
    for (const key in data) {
      if (data.hasOwnProperty(key)) {
        out.push(key + '=' + encodeURIComponent(data[key]));
      }
    }

    history.replaceState(history.state, '', '#' + out.join('&'));
}

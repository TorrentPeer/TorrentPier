/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

/**
 * Cookie
 **/

/**
 * Set a cookie
 *
 * @param name
 * @param value
 * @param days
 * @param path
 * @param domain
 * @param secure
 */
function setCookie(name, value, days, path, domain, secure) {
  if (days !== 'SESSION') {
    var date = new Date();
    days = days || 365;
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    var expires = date.toGMTString();
  } else {
    var expires = '';
  }

  document.cookie = name + '=' + encodeURI(value) + ((expires) ? '; expires=' + expires : '') + ((path) ? '; path=' + path : ((cookiePath) ? '; path=' + cookiePath : '')) + ((domain) ? '; domain=' + domain : ((cookieDomain) ? '; domain=' + cookieDomain : '')) + ((secure) ? '; secure' : ((cookieSecure) ? '; secure' : ''));
}

/**
 * Returns a string containing value of specified cookie, or null if cookie does not exist.
 *
 * @param name
 * @returns {string|null}
 */
function getCookie(name) {
  var c, RE = new RegExp('(^|;)\\s*' + name + '\\s*=\\s*([^\\s;]+)', 'g');
  return (c = RE.exec(document.cookie)) ? c[2] : null;
}

/**
 * Remove a cookie
 *
 * @param name
 * @param path
 * @param domain
 */
function deleteCookie(name, path, domain) {
  setCookie(name, '', -1, path, domain);
}

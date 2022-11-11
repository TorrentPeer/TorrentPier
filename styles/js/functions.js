/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

/**
 * Functions
 **/

// prototype $
function $p() {
  var elements = [];
  for (var i = 0; i < arguments.length; i++) {
    var element = arguments[i];
    if (typeof element === 'string')
      element = document.getElementById(element);
    if (arguments.length === 1)
      return element;
    elements.push(element);
  }
  return elements;
}

/**
 * imgFit
 *
 * @param img
 * @param maxW
 * @returns {boolean}
 */
function imgFit(img, maxW) {
  img.title = 'Размеры изображения: ' + img.width + ' x ' + img.height;
  if (typeof (img.naturalHeight) === 'undefined') {
    img.naturalHeight = img.height;
    img.naturalWidth = img.width;
  }
  if (img.width > maxW) {
    img.height = Math.round((maxW / img.width) * img.height);
    img.width = maxW;
    img.title = 'Нажмите на изображение, чтобы посмотреть его в полный размер';
    img.style.cursor = 'move';
    return false;
  } else if (img.width === maxW && img.width < img.naturalWidth) {
    img.height = img.naturalHeight;
    img.width = img.naturalWidth;
    img.title = 'Размеры изображения: ' + img.naturalWidth + ' x ' + img.naturalHeight;
    return false;
  } else {
    return true;
  }
}

/**
 * Переадресация
 *
 * @param link
 */
function redirect(link) {
  $(window).attr('location', link)
}

/**
 * Проверка на пустое значение
 *
 * @param x
 * @returns {boolean}
 */
function isEmpty(x) {
  if (Array.isArray(x)
    || typeof x === 'string'
    || x instanceof String
  ) {
    return x.length === 0;
  }

  if (x instanceof Map || x instanceof Set) {
    return x.size === 0;
  }

  if (({}).toString.call(x) === '[object Object]') {
    return Object.keys(x).length === 0;
  }

  return false;
}

/**
 * Перезагрузка страницы
 */
function reload() {
  history.go(0);
}

/**
 * Показывает/скрывает блок
 *
 * @param id
 */
function toggle_block(id) {
  var el = document.getElementById(id);
  el.style.display = (el.style.display === 'none') ? '' : 'none';
}

/**
 * Включить отключенный элемент
 *
 * @param id
 * @param val
 */
function toggle_disabled(id, val) {
  document.getElementById(id).disabled = (val) ? 0 : 1;
}

/**
 * Рандомное число
 *
 * @param min
 * @param max
 * @returns {*}
 */
function rand(min, max) {
  return min + Math.floor((max - min + 1) * Math.random());
}

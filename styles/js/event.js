/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

/**
 * Event actions
 **/

/**
 * addEvent
 *
 * @param obj
 * @param type
 * @param fn
 */
function addEvent(obj, type, fn) {
  if (obj.addEventListener) {
    obj.addEventListener(type, fn, false);
    EventCache.add(obj, type, fn);
  } else if (obj.attachEvent) {
    obj["e" + type + fn] = fn;
    obj[type + fn] = function () {
      obj["e" + type + fn](window.event);
    };
    obj.attachEvent("on" + type, obj[type + fn]);
    EventCache.add(obj, type, fn);
  } else {
    obj["on" + type] = obj["e" + type + fn];
  }
}

/**
 * EventCache
 *
 * @type {{add: EventCache.add, listEvents: *[], flush: EventCache.flush}}
 */
var EventCache = function () {
  var listEvents = [];
  return {
    listEvents: listEvents, add: function (node, sEventName, fHandler) {
      listEvents.push(arguments);
    }, flush: function () {
      var i, item;
      for (i = listEvents.length - 1; i >= 0; i = i - 1) {
        item = listEvents[i];
        if (item[0].removeEventListener) {
          item[0].removeEventListener(item[1], item[2], item[3]);
        }
        if (item[1].substring(0, 2) !== "on") {
          item[1] = "on" + item[1];
        }
        if (item[0].detachEvent) {
          item[0].detachEvent(item[1], item[2]);
        }
        item[0][item[1]] = null;
      }
    }
  };
}();

if (document.all) {
  addEvent(window, 'unload', EventCache.flush);
}

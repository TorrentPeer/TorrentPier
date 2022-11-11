/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

/**
 * Menu
 */
var Menu = {
  hideSpeed: 'fast',
  offsetCorrection_X: -4,
  offsetCorrection_Y: 2,

  activeMenuId: null,  //  currently opened menu (from previous click)
  clickedMenuId: null,  //  menu to show up
  $root: null,  //  root element for menu with "href = '#clickedMenuId'"
  $menu: null,  //  clicked menu
  positioningType: null,  //  reserved
  outsideClickWatch: false, //  prevent multiple $(document).click binding

  clicked: function ($root) {
    $root.blur();
    this.clickedMenuId = this.getMenuId($root);
    this.$menu = $(this.clickedMenuId);
    this.$root = $root;
    this.toggle();
  },

  hovered: function ($root) {
    if (this.activeMenuId && this.activeMenuId !== this.getMenuId($root)) {
      this.clicked($root);
    }
  },

  unhovered: function ($root) {
  },

  getMenuId: function ($el) {
    var href = $el.attr('href');
    return href.substr(href.indexOf('#'));
  },

  setLocation: function () {
    var CSS = this.$root.offset();
    CSS.top += this.$root.height() + this.offsetCorrection_Y;
    var curTop = parseInt(CSS.top);
    var tCorner = $(document).scrollTop() + $(window).height() - 20;
    var maxVisibleTop = Math.min(curTop, Math.max(0, tCorner - this.$menu.height()));
    if (curTop !== maxVisibleTop) {
      CSS.top = maxVisibleTop;
    }
    CSS.left += this.offsetCorrection_X;
    var curLeft = parseInt(CSS.left);
    var rCorner = $(document).scrollLeft() + $(window).width() - 6;
    var maxVisibleLeft = Math.min(curLeft, Math.max(0, rCorner - this.$menu.width()));
    if (curLeft !== maxVisibleLeft) {
      CSS.left = maxVisibleLeft;
    }
    this.$menu.css(CSS);
  },

  fixLocation: function () {
    var $menu = this.$menu;
    var curLeft = parseInt($menu.css('left'));
    var rCorner = $(document).scrollLeft() + $(window).width() - 6;
    var maxVisibleLeft = Math.min(curLeft, Math.max(0, rCorner - $menu.width()));
    if (curLeft !== maxVisibleLeft) {
      $menu.css('left', maxVisibleLeft);
    }
    var curTop = parseInt($menu.css('top'));
    var tCorner = $(document).scrollTop() + $(window).height() - 20;
    var maxVisibleTop = Math.min(curTop, Math.max(0, tCorner - $menu.height()));
    if (curTop !== maxVisibleTop) {
      $menu.css('top', maxVisibleTop);
    }
  },

  toggle: function () {
    if (this.activeMenuId && this.activeMenuId !== this.clickedMenuId) {
      $(this.activeMenuId).hide(this.hideSpeed);
    }
    // toggle clicked menu
    if (this.$menu.is(':visible')) {
      this.$menu.hide(this.hideSpeed);
      this.activeMenuId = null;
    } else {
      this.showClickedMenu();
      if (!this.outsideClickWatch) {
        $(document).one('mousedown', function (e) {
          Menu.hideClickWatcher(e);
        });
        this.outsideClickWatch = true;
      }
    }
  },

  showClickedMenu: function () {
    this.setLocation();
    this.$menu.css({display: 'block'});
    // this.fixLocation();
    this.activeMenuId = this.clickedMenuId;
  },

  // hide if clicked outside of menu
  hideClickWatcher: function (e) {
    this.outsideClickWatch = false;
    this.hide(e);
  },

  hide: function (e) {
    if (this.$menu) {
      this.$menu.hide(this.hideSpeed);
    }
    this.activeMenuId = this.clickedMenuId = this.$menu = null;
  }
};

/**
 * Prepare menus
 **/
$(document).ready(function () {
  // Menus
  $('body').append($('div.menu-sub'));
  $('a.menu-root')
    .click(
      function (e) {
        e.preventDefault();
        Menu.clicked($(this));
        return false;
      })
    .hover(
      function () {
        Menu.hovered($(this));
        return false;
      },
      function () {
        Menu.unhovered($(this));
        return false;
      }
    )
  ;
  $('div.menu-sub')
    .mousedown(function (e) {
      e.stopPropagation();
    })
    .find('a')
    .click(function (e) {
      Menu.hide(e);
    })
  ;
  // Input hints
  $('input')
    .filter('.hint').one('focus', function () {
    $(this).val('').removeClass('hint');
  })
    .end()
    .filter('.error').one('focus', function () {
    $(this).removeClass('error');
  })
  ;
});

/**
 * Autocomplete password
 **/
var array_for_rand_pass = ["a", "A", "b", "B", "c", "C", "d", "D", "e", "E", "f", "F", "g", "G", "h", "H", "i", "I", "j", "J", "k", "K", "l", "L", "m", "M", "n", "N", "o", "O", "p", "P", "q", "Q", "r", "R", "s", "S", "t", "T", "u", "U", "v", "V", "w", "W", "x", "X", "y", "Y", "z", "Z", 0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
var array_rand = function (array) {
  var array_length = array.length;
  var result = Math.random() * array_length;
  return Math.floor(result);
};

var autocomplete = function (noCenter, length) {
  var string_result = ""; // Empty string
  for (var i = 1; i <= length; i++) {
    string_result += array_for_rand_pass[array_rand(array_for_rand_pass)];
  }

  var _popup_left = (Math.ceil(window.screen.availWidth / 2) - 150);
  var _popup_top = (Math.ceil(window.screen.availHeight / 2) - 50);

  if (!noCenter) {
    $("div#autocomplete_popup").css({
      left: _popup_left + "px",
      top: _popup_top + "px"
    }).show(1000);
  } else {
    $("div#autocomplete_popup").show(1000);
  }

  $("input#pass, input#pass_confirm, div#autocomplete_popup input").each(function () {
    $(this).val(string_result);
  });
};

/**
 * Autocomplete popup
 **/
$(document).ready(function () {
  // перемещение окна
  var _X, _Y;
  var _bMoveble = false;

  $("div#autocomplete_popup div.title").mousedown(function (event) {
    _bMoveble = true;
    _X = event.clientX;
    _Y = event.clientY;
  });

  $("div#autocomplete_popup div.title").mousemove(function (event) {
    var jFrame = $("div#autocomplete_popup");
    var jFLeft = parseInt(jFrame.css("left"));
    var jFTop = parseInt(jFrame.css("top"));

    if (_bMoveble) {
      if (event.clientX < _X) {
        jFrame.css("left", jFLeft - (_X - event.clientX) + "px");
      } else {
        jFrame.css("left", (jFLeft + (event.clientX - _X)) + "px");
      }

      if (event.clientY < _Y) {
        jFrame.css("top", jFTop - (_Y - event.clientY) + "px");
      } else {
        jFrame.css("top", (jFTop + (event.clientY - _Y)) + "px");
      }

      _X = event.clientX;
      _Y = event.clientY;
    }
  });

  $("div#autocomplete_popup div.title").mouseup(function () {
    _bMoveble = false;
  }).mouseout(function () {
    _bMoveble = false;
  });
});

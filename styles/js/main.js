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
  hideSpeed: 'fast', offsetCorrection_X: -4, offsetCorrection_Y: 2,

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
    .click(function (e) {
      e.preventDefault();
      Menu.clicked($(this));
      return false;
    })
    .hover(function () {
      Menu.hovered($(this));
      return false;
    }, function () {
      Menu.unhovered($(this));
      return false;
    });
  $('div.menu-sub')
    .mousedown(function (e) {
      e.stopPropagation();
    })
    .find('a')
    .click(function (e) {
      Menu.hide(e);
    });
  // Input hints
  $('input')
    .filter('.hint').one('focus', function () {
    $(this).val('').removeClass('hint');
  })
    .end()
    .filter('.error').one('focus', function () {
    $(this).removeClass('error');
  });
});

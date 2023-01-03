/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

/**
 * Ajax
 **/

/**
 * Ajax method
 *
 * @param handlerURL
 * @param requestType
 * @param dataType
 * @constructor
 */
function Ajax(handlerURL, requestType, dataType) {
  this.url = handlerURL;
  this.type = requestType;
  this.dataType = dataType;
  this.errors = {};
}

Ajax.prototype = {
  init: {},  // init functions (run before submit, after triggering ajax event)
  callback: {},  // callback functions (response handlers)
  state: {},  // current action state
  request: {},  // request data
  params: {},  // action params, format: ajax.params[ElementID] = { param: "val" ... }
  form_token: '', hide_loading: null,

  exec: function (request, hide_loading = false) {
    this.request[request.action] = request;
    request['form_token'] = this.form_token;
    this.hide_loading = hide_loading;
    $.ajax({
      url: this.url, type: this.type, dataType: this.dataType, data: request, success: ajax.success, error: ajax.error
    });
  },

  success: function (response) {
    var action = response.action;
    // raw_output normally might contain only error messages (if php.ini.display_errors == 1)
    if (response.raw_output) {
      $('body').prepend(response.raw_output);
    }
    if (response.sql_log) {
      $('#sqlLog').prepend(response.sql_log + '<hr />');
      fixSqlLog();
    }
    if (response.update_ids) {
      for (id in response.update_ids) {
        $('#' + id).html(response.update_ids[id]);
      }
    }
    if (response.prompt_password) {
      var user_password = prompt('Для доступа к данной функции, пожалуйста, введите свой пароль', '');
      if (user_password) {
        var req = ajax.request[action];
        req.user_password = user_password;
        ajax.exec(req);
      } else {
        ajax.clearActionState(action);
        ajax.showErrorMsg('Введен неверный пароль');
      }
    } else if (response.prompt_confirm) {
      if (window.confirm(response.confirm_msg)) {
        var req = ajax.request[action];
        req.confirmed = 1;
        ajax.exec(req);
      } else {
        ajax.clearActionState(action);
      }
    } else if (response.error_code) {
      ajax.showErrorMsg(response.error_msg);
      $('.loading-1').removeClass('loading-1').html('error');
    } else {
      ajax.callback[action](response);
      ajax.clearActionState(action);
    }
  },

  error: function (xml, desc) {
  },

  clearActionState: function (action) {
    ajax.state[action] = ajax.request[action] = '';
  },

  showErrorMsg: function (msg) {
    alert(msg);
  },

  callInitFn: function (event) {
    event.stopPropagation();
    var params = ajax.params[$(this).attr('id')];
    var action = params.action;
    if (ajax.state[action] === 'readyToSubmit' || ajax.state[action] === 'error') {
      return false;
    } else {
      ajax.state[action] = 'readyToSubmit';
    }
    ajax.init[action](params);
  },

  setStatusBoxPosition: function ($el) {
    var newTop = $(document).scrollTop();
    var rCorner = $(document).scrollLeft() + $(window).width() - 8;
    var newLeft = Math.max(0, rCorner - $el.width());
    $el.css({top: newTop, left: newLeft});
  },

  makeEditable: function (rootElementId, editableType) {
    var $root = $('#' + rootElementId);
    var $editable = $('.editable', $root);
    var inputsHtml = $('#editable-tpl-' + editableType).html();
    $editable.hide().after(inputsHtml);
    var $inputs = $('.editable-inputs', $root);
    if (editableType === 'input' || editableType === 'textarea') {
      $('.editable-value', $inputs).val($.trim($editable.text()));
    }
    $('input.editable-submit', $inputs).click(function () {
      var params = ajax.params[rootElementId];
      var $val = $('.editable-value', '#' + rootElementId);
      params.value = ($val.size() === 1) ? $val.val() : $val.filter(':checked').val();
      params.submit = true;
      ajax.init[params.action](params);
    });
    $('input.editable-cancel', $inputs).click(function () {
      ajax.restoreEditable(rootElementId);
    });
    $inputs.show().find('.editable-value').focus();
    $root.removeClass('editable-container');
  },

  restoreEditable: function (rootElementId, newValue) {
    var $root = $('#' + rootElementId);
    var $editable = $('.editable', $root);
    $('.editable-inputs', $root).remove();
    if (newValue) {
      $editable.text(newValue);
    }
    $editable.show();
    ajax.clearActionState(ajax.params[rootElementId].action);
    ajax.params[rootElementId].submit = false;
    $root.addClass('editable-container');
  }
};

/**
 * Prepare ajax
 */
$(document).ready(function () {
  // Setup ajax-loading box
  $("#ajax-loading").ajaxStart(function () {
    if (ajax.hide_loading === false) {
      $("#ajax-error").hide();
      $(this).show();
      ajax.setStatusBoxPosition($(this));
    }
  });
  $("#ajax-loading").ajaxStop(function () {
    if (ajax.hide_loading === false) {
      $(this).hide();
    }
  });

  // Setup ajax-error box
  $("#ajax-error").ajaxError(function (req, xml) {
    var status = xml.status;
    var text = xml.statusText;
    if (status === 200) {
      status = '';
      text = 'неверный формат данных';
    }
    $(this).html("Ошибка в: <i>" + ajax.url + "</i><br /><b>" + status + " " + text + "</b>").show();
    ajax.setStatusBoxPosition($(this));
  });

  // Bind ajax events
  $('var.ajax-params').each(function () {
    var params = $.evalJSON($(this).html());
    params.event = params.event || 'dblclick';
    ajax.params[params.id] = params;
    $("#" + params.id).bind(params.event, ajax.callInitFn);
    if (params.event === 'click' || params.event === 'dblclick') {
      $("#" + params.id).addClass('editable-container');
    }
  });
});

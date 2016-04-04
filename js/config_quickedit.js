(function ($, Drupal, drupalSettings) {

  'use strict';

  var configQuickEditSelector = 'a[data-config-quick-edit-route]';
  var configQuickEditReplaceSelector = '#config-quickedit-replace';



  function handleClick(event) {
    // Middle click, cmd click, and ctrl click should open
    // links in a new tab as normal.
    if (event.which > 1 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
      return;
    }


    if (this.hasAttribute('data-config-quick-edit-path')) {
      var url = Drupal.url(this.getAttribute('data-config-quick-edit-path'));
    }
    else {
      var url = this.getAttribute('href');
    }
    event.preventDefault();
    // Create a Drupal.Ajax object without associating an element, a
    // progress indicator or a URL.
    var ajaxObject = Drupal.ajax({
      url: url,
      selector: '#config-quickedit-replace',
      //method: 'append',
      // @todo refreshless progress?
      progress: 'throbber',
      // @todo vastly improve this.
      //wrapper: drupalSettings.ajaxPageState.theme === 'bartik' ? 'block-bartik-content .content > *' : 'block-seven-content > *',
      //dialogType: 'refreshless'
    });
    ajaxObject.commands.insert = function (ajax, response, status) {
      if (response.method != 'prepend') {
        $(configQuickEditReplaceSelector).html(response.data);
        jQuery(configQuickEditReplaceSelector).removeClass('active');
        Drupal.attachBehaviors($(configQuickEditReplaceSelector));
      }

    };

    // @todo Is there a better way to open the toolbar tab.
    if (!jQuery('#toolbar-item-config-quickedit-tray').hasClass('is-active')) {
      jQuery('#toolbar-item-config-quickedit').click();
    }
    ajaxObject.execute();
  }

  jQuery('body').once('config_quickedit').on('click', configQuickEditSelector, handleClick);
  Drupal.Ajax.prototype.beforeSubmit = function (form_values, element, options) {
    if (jQuery('#config-quickedit-replace').has(element).length) {
      jQuery('#config-quickedit-replace').find('.use-ajax-submit').addClass('config-quickedit-trigger')
    }
  };
  $( document ).ajaxSuccess(function( event, xhr, settings ) {

    if ($(configQuickEditReplaceSelector).has(event.currentTarget.activeElement).length) {
      //alert('what');
      //if ($(event.currentTarget.activeElement)[0].id != 'config-quickedit-refresh') {
      if ($('.config-quickedit-trigger').length) {
        $('#config-quickedit-refresh').attr("href", window.location.pathname);
        //$(event.currentTarget.activeElement).addClass('refreshless-trigger');
        $('.config-quickedit-trigger').removeClass('config-quickedit-trigger')
        $('#config-quickedit-refresh').click();
      }
    }
  });

})(jQuery, Drupal, drupalSettings);

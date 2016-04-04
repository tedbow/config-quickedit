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
    var ajaxObject = Drupal.ajax({
      url: url,
      selector: '#config-quickedit-replace',
      //method: 'append',
      // @todo refreshless progress?
      progress: 'throbber',
    });
    ajaxObject.commands.insert = function (ajax, response, status) {
      if (response.method != 'prepend') {
        $(configQuickEditReplaceSelector).html(response.data);
        // Hitting an ajax that might be cause by active class
        // @see https://www.drupal.org/node/1979468
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
    // If the submit is coming from the Config QuickEdit section then add a marking class.
    if (jQuery('#config-quickedit-replace').has(element).length) {
      // @todo Check if the form was submitted or just a internal ajax change.
      if (this.event != 'change') {
        jQuery('#config-quickedit-replace').find('.use-ajax-submit').addClass('config-quickedit-trigger');
      }

    }
  };
  $( document ).ajaxSuccess(function( event, xhr, settings ) {

    if ($(configQuickEditReplaceSelector).has(event.currentTarget.activeElement).length) {

      // Check to see if our marking class exists to determine if reload is needed.
      if ($('.config-quickedit-trigger').length) {
        $('#config-quickedit-refresh').attr("href", window.location.pathname);
        // Remove the class before reload so we don't get in an endless loop.
        $('.config-quickedit-trigger').removeClass('config-quickedit-trigger')
        $('#config-quickedit-refresh').click();
      }
    }
  });

})(jQuery, Drupal, drupalSettings);

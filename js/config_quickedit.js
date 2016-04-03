(function ($, Drupal, drupalSettings) {

  'use strict';

  var configQuickEditSelector = 'a[data-config-quick-edit-route]';



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
        $('#config-quickedit-replace').html('wh-');
        $('#config-quickedit-replace').append(response.data);
      }

    };
    // @todo Is there a better way to open the toolbar tab.

    if (!jQuery('#toolbar-item-config-quickedit-tray').hasClass('is-active')) {
      jQuery('#toolbar-item-config-quickedit').click();
    }
    ajaxObject.execute();
    return;
    if (urlUsesDifferentTheme(url)) {
      return;
    }

    if (!event.isDefaultPrevented()) {
      event.preventDefault();

      var newPath = url.replace(drupalSettings.path.baseUrl + drupalSettings.path.pathPrefix, '');
      var librariesBefore = getLibraries();

      // Create a Drupal.Ajax object without associating an element, a
      // progress indicator or a URL.
      var ajaxObject = Drupal.ajax({
        url: url,
        base: false,
        element: false,
        // @todo refreshless progress?
        progress: false,
        // @todo vastly improve this.
        wrapper: drupalSettings.ajaxPageState.theme === 'bartik' ? 'block-bartik-content .content > *' : 'block-seven-content > *',
        dialogType: 'refreshless'
      });
      var ajaxInstanceIndex = Drupal.ajax.instances.length;

      // Use GET, not the default of POST.
      ajaxObject.options.type = 'GET';

      // As soon as the page's settings are updated, also update currentPath.
      // (None of the other settings under drupalSettings.path can ever change,
      // if they would, Refreshless would trigger a full reload.)
      var originalSettingsCommand = ajaxObject.commands.settings;
      ajaxObject.commands.settings= function (ajax, response, status) {
        originalSettingsCommand(ajax, response, status);
        drupalSettings.path.currentPath = newPath;
      };

      // The server responds with a 412 when the current page's theme doesn't
      // match the response's theme. This means we need to do a full reload
      // after all.
      ajaxObject.options.error = function (response, status, xmlhttprequest) {
        if (response.status === 412) {
          window.location = url;
        }
      };

      // When the Refreshless request receives a succesful response, update the
      // URL using the history.pushState() API.
      var originalSuccess = ajaxObject.options.success;
      ajaxObject.options.success = function (response, status, xmlhttprequest) {
        originalSuccess(response, status, xmlhttprequest);

        var state = {
          updateRegionCommands: response.filter(function (command) { return command.command === 'refreshlessUpdateRegion' }),
          librariesBefore: librariesBefore,
          newLibraries: getLibraries().filter(function (value) { return !librariesBefore.includes(value); })
        };
        history.pushState(state, '', url);

        // @todo trigger drupal:path:changed event, ensure contextual.js listens to this event.

        debug(url, state);

        // Set this to null and allow garbage collection to reclaim
        // the memory.
        Drupal.ajax.instances[ajaxInstanceIndex] = null;
      };

      // Pass Refreshless' page state, to allow the server to determine which
      // parts of the page need to be updated and which don't.
      ajaxObject.options.data.refreshless_page_state = drupalSettings.refreshlessPageState;

      ajaxObject.execute();
    }
  }

  jQuery('body').once('config_quickedit').on('click', configQuickEditSelector, handleClick);

})(jQuery, Drupal, drupalSettings);

'use strict';

(function ($, wp, jupiterx) {

  var api = wp.customize;

  /**
   * JupiterX main object.
   */
  var JupiterX = api.JupiterX || {};

  api.JupiterX = JupiterX;

  /**
   * Fonts object.
   *
   * @since 1.0.0
   */
  var Fonts = {
    /**
     * Font stack used in Customizer preview.
     *
     * @since 1.0.0
     */
    stack: [],

    /**
     * Storage for all printed font via Webfont.
     *
     * @since 1.0.0
     */
    printedStack: [],

    /**
     * Add to stack.
     *
     * @since 1.0.0
     *
     * @param {object} font - Font details.
     * @return {void}
     */
    addToStack: function addToStack(font) {
      if (!font.type && !font.name) {
        return;
      }

      if (!this.isFontExists(font.name)) {
        this.stack.push(font);
      }
    },

    /**
     * Add to stack.
     *
     * @since 1.0.0
     *
     * @param {object} name - Font details.
     * @return {void}
     */
    removeFromStack: function removeFromStack(name) {
      // Clear all settings that uses this font.
      this.clearFontSettings(name);

      // Remove from stack.
      this.stack = _.filter(this.stack, function (font) {
        return font.name !== name;
      });
    },

    /**
     * Check font existing to stack.
     *
     * @since 1.0.0
     *
     * @param {string} name - Font name.
     * @return {boolean}
     */
    isFontExists: function isFontExists(name) {
      return typeof _.findWhere(this.stack, { name: name }) !== 'undefined';
    },

    /**
     * Add to printed stack.
     *
     * @since 1.0.0
     *
     * @param {string} name - Font name.
     * @return {void}
     */
    addToPrinted: function addToPrinted(name) {
      if (!this.isPrinted(name)) {
        this.printedStack.push(name);
      }
    },

    /**
     * Check if font is printed in the window.
     *
     * @since 1.0.0
     *
     * @param {string} name - Font name.
     * @return {boolean}
     */
    isPrinted: function isPrinted(name) {
      return _.contains(this.printedStack, name);
    },

    /**
     * Get real font value.
     *
     * @since 1.0.0
     *
     * @param {string} name - Font name.
     * @return {string}
     */
    getFontValue: function getFontValue(name) {
      var value = '';

      _.each(this.stack, function (font) {
        if (font.name === name) {
          value = font.value ? font.value : name;
          return value;
        }
      });

      return value;
    },

    /**
     * Clear font from settings.
     *
     * @since 1.0.0
     *
     * @param {string} name - Font name.
     * @return {void}
     */
    clearFontSettings: function clearFontSettings(name) {
      var fontValue = this.getFontValue(name);

      _.each(wp.customize.settings.controls, function (control) {
        if (control.type === 'jupiterx-typography') {
          var oldValue = wp.customize(control.id).get(),
              value = _.extend({}, wp.customize(control.id).get()),
              devices = ['desktop', 'tablet', 'mobile'];

          // Responsive value.
          if (control.responsive && value) {
            _.each(devices, function (device) {
              if (_.isObject(value[device]) && value[device]['font_family'] === fontValue) {
                var temp = _.extend({}, value[device], { 'font_family': '' });
                value[device] = temp;
              }
            });
          }

          // Non responsive.
          if (!control.responsive && value) {
            if (_.isObject(value) && value['font_family'] === fontValue) {
              value['font_family'] = '';
            }
          }

          if (!_.isEqual(oldValue, value)) {
            wp.customize(control.id).set(value);
          }
        }
      });
    }
  };

  JupiterX.fonts = Fonts;

  /**
   * Class for Popup section.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.PopupSection
   *
   * @constructor
   */
  JupiterX.PopupSection = api.Section.extend({
    /**
     * @constructs wp.customize.JupiterX.PopupSection
     *
     * @since 1.0.0
     *
     * @param {string}         id - The ID for the section.
     * @param {object}         options - Options.
     * @param {string}         [options.title] - Title shown when section is collapsed and expanded.
     * @param {string}         [options.description] - Description shown at the top of the section.
     * @param {number}         [options.priority] - The sort priority for the section.
     * @param {string=default} [options.type] - The type of the section. See wp.customize.sectionConstructor.
     * @param {string}         [options.content] - The markup to be used for the section container. If empty, a JS template is used.
     * @param {boolean=true}   [options.active] - Whether the section is active or not.
     * @param {string}         [options.panel] - The ID for the panel this section is associated with.
     * @param {string}         [options.customizeAction] - Additional context information shown before the section title when expanded.
     * @param {object}         [options.tabs] - The tabs available inside the popup.
     * @param {object}         [options.popups] - The popups available inside the popup.
     * @returns {void}
     */
    initialize: function initialize(id, options) {
      this.containerParent = '#customize-jupiterx-popup-controls';
      this.containerPaneParent = '.customize-pane-parent';
      this.tabs = {};
      this.tabsButton = null;
      this.tabsPane = null;
      this.activeTab = null;
      this.popups = {};
      this.popupsContainer = null;
      this.popupsPane = null;
      api.Section.prototype.initialize.apply(this, arguments);
    },

    /**
     * Update UI to reflect expanded state.
     *
     * @since 1.0.0
     *
     * @param {boolean} expanded
     * @param {object}  args
     */
    onChangeExpanded: function onChangeExpanded(expanded, args) {
      var section = this,
          container = section.container,
          body = $(document.body),
          openClass = 'open-jupiterx-popup-content',
          params = this.params;

      // Silently remove the current expanded section.
      api.section.each(function (_section) {
        if ('kirki-popup' === _section.params.type && _section.id !== section.id && _section.container.hasClass('open')) {
          _section.expanded.set(false);
          _section.container.removeClass('open');
        }
      });

      body.toggleClass(openClass, expanded);
      container.toggleClass('open', expanded);
      container.removeClass('busy');

      if (expanded && params.preview) {
        var _args = _.isObject(params.preview) ? params.preview : {};
        this.redirectSectionPreview(params.id, _args);
      }
    },

    /**
     * Redirect Customizer preview based on expanded section.
     *
     * @since 1.0.0
     *
     * @param {string} sectionId
     * @param {object} args
     */
    redirectSectionPreview: function redirectSectionPreview(sectionId, args) {
      if (!window.jupiterxCustomizer) {
        return;
      }

      wp.ajax.post('jupiterx_core_customizer_preview_redirect_url', {
        _ajax_nonce: window.jupiterxCustomizer.customizer_preview_redirect_url_nonce,
        section: sectionId,
        options: JSON.stringify(args)
      }).done(function (response) {
        if (!response.redirectUrl) {
          return;
        }

        api.previewer.previewUrl.set(response.redirectUrl);
      }).fail(function () {
        api.previewer.refresh();
      });
    },

    /**
     * Attach events.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    attachEvents: function attachEvents() {
      var self = this,
          params = self.params,
          toggleSection = void 0;

      toggleSection = function toggleSection() {
        return self.expanded() ? self.collapse() : self.expand();
      };

      self.container.find('.accordion-section-title, .jupiterx-popup-close').on('click keydown', function (event) {
        if (api.utils.isKeydownButNotEnterEvent(event)) {
          return;
        }

        event.preventDefault();
        toggleSection();
      });

      if (!_.isEmpty(params.tabs)) {
        self.tabsEvents();
      }

      if (!_.isEmpty(params.popups)) {
        self.childPopupEvents();
      }
    },

    /**
     * Tabs events.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    tabsEvents: function tabsEvents() {
      var self = this,
          tabsId = Object.keys(self.params.tabs);

      // Store tabs pane.
      self.tabsPane = self.container.find('.jupiterx-tabs-pane');

      // Create tabs button event.
      self.tabsButton = self.container.find('.jupiterx-tabs-button').each(function (i, button) {
        $(button).on('click', function (event) {
          event.preventDefault();
          self.openTab(tabsId[i]);
        });
      });

      // Set open tab on popup initial open.
      self.expanded.bind(function () {
        if (self.expanded() && !self.activeTab) {
          self.openTab(tabsId[0]);
        }
      });

      // Declaratively store tabs.
      _.each(tabsId, function (tabId, i) {
        self.tabs[tabId] = {
          button: self.tabsButton[i],
          pane: self.tabsPane[i]
        };
      });
    },

    /**
     * Open a tab base on its id.
     *
     * @since 1.0.0
     *
     * @param {string} tabId - The tab id.
     * @returns {void}
     */
    openTab: function openTab(tabId) {
      if (_.isUndefined(this.tabs[tabId])) {
        return;
      }

      var button = $(this.tabs[tabId].button),
          pane = $(this.tabs[tabId].pane);

      if (!this.expanded()) {
        this.expand();
      }

      this.activeTab = tabId;
      this.hideTabs();

      button.addClass('active');
      pane.removeClass('hidden');
      pane.trigger('expanded');
    },

    /**
     * Hide all open tabs.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    hideTabs: function hideTabs() {
      var buttons = this.tabsButton.filter('.active'),
          panes = this.tabsPane.filter(':not(.hidden)');

      buttons.removeClass('active');
      panes.addClass('hidden');
    },

    /**
     * Child popup events.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    childPopupEvents: function childPopupEvents() {
      var self = this,
          popupsId = Object.keys(self.params.popups);

      // Store popup child container.
      self.popupsContainer = self.container.find('.jupiterx-popup-child');

      // Popup close button event.
      self.popupsContainer.find('.jupiterx-child-popup-close').on('click', function (event) {
        event.preventDefault();
        self.hideChildPopups();
      });

      // Store popups pane.
      self.popupsPane = self.container.find('.jupiterx-child-popup');

      // Declaratively store popups.
      _.each(popupsId, function (popupId, i) {
        self.popups[popupId] = {
          pane: self.popupsPane[i]
        };
      });
    },

    /**
     * Open a child popup base on its id.
     *
     * @since 1.0.0
     *
     * @param {string} popupId - The child popup id.
     * @returns {void}
     */
    openChildPopup: function openChildPopup(popupId) {
      if (_.isUndefined(this.popups[popupId])) {
        return;
      }

      var pane = $(this.popups[popupId].pane);

      if (!this.expanded()) {
        this.expand();
      }

      this.hideChildPopups();
      this.popupsContainer.toggleClass('open', true);

      pane.addClass('active');
      pane.trigger('expanded');
    },

    /**
     * Hide opened child popups.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    hideChildPopups: function hideChildPopups() {
      this.popupsContainer.removeClass('open');
      this.popupsPane.filter('.active').removeClass('active');
    },

    /**
     * Return whether this panel has any active sections.
     *
     * @since 1.0.0
     *
     * @returns {boolean} Whether contextually active.
     */
    isContextuallyActive: function isContextuallyActive() {
      var self = this,
          sections = [],
          controls = self.controls(),
          activeCount = 0;

      api.section.each(function (section) {
        if (section.params.popup && section.params.popup === self.id) {
          if (section.active() && section.isContextuallyActive()) {
            activeCount += 1;
          }
        }
      });

      controls.forEach(function (control) {
        if (control.active()) {
          activeCount += 1;
        }
      });

      return activeCount !== 0;
    },

    /**
     * Find content element which is displayed when the section is expanded.
     *
     * @since 1.0.0
     *
     * @returns {jQuery} Detached content element.
     */
    getContent: function getContent() {
      var container = this.container,
          content = container.find('.jupiterx-popup-section:first'),
          contentID = 'sub-' + container.attr('id'),
          ariaOwns = contentID,
          hasAriaOwns = container.attr('aria-owns'),
          sectionClass = 'accordion-section';

      if (hasAriaOwns) {
        ariaOwns = ariaOwns + ' ' + hasAriaOwns;
      }

      container.attr('aria-owns', ariaOwns);
      content.detach().attr({
        'id': contentID,
        'class': content.attr('class') + ' ' + container.attr('class')
      }).removeClass(sectionClass);

      return content;
    }
  });

  /**
   * Class for Pane section.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.PaneSection
   *
   * @constructor
   */
  JupiterX.PaneSection = api.Section.extend({
    default: {
      popup: '',
      pane: []
    },

    /**
     * @constructs wp.customize.JupiterX.PaneSection
     *
     * @since 1.0.0
     *
     * @param {string}         id - The ID for the section.
     * @param {object}         options - Options.
     * @param {string=default} [options.type] - The type of the section. See wp.customize.sectionConstructor.
     * @param {string}         [options.content] - The markup to be used for the section container. If empty, a JS template is used.
     * @param {string}         [options.popup] - The popup section id where to render the pane.
     * @param {object}         [options.pane] - The settings of the pane.
     * @param {string}         [options.pane.id] - The pane id to inject the controls.
     * @param {string}         [options.pane.type] - The type of the pane, it can be tab, popup, and etc.
     * @returns {void}
     */
    initialize: function initialize(id, options) {
      var params = options.params,
          popup = params.popup,
          pane = params.pane,
          containerParent = void 0;

      // Don't initialize when either of these two important params is empty.
      if (!popup || _.isEmpty(pane)) {
        return;
      }

      this.containerParent = '#' + pane.type + '-' + pane.id + '-' + popup;
      api.Section.prototype.initialize.apply(this, arguments);
    },

    /**
     * Embed the container in the DOM when any parent panel is ready.
     *
     * @since 1.0.0
     */
    embed: function embed() {
      var self = this,
          popup = self.params.popup,
          pane = self.params.pane,
          inject = void 0;

      inject = function inject(popupId) {
        api.section(popupId, function (section) {
          // Block embedding on other type of section.
          if (section.params.type !== 'kirki-popup') {
            return;
          }

          section.deferred.embedded.done(function () {
            var appendContainer = void 0;

            // Create container where to append the section.
            self.containerParent = api.ensure(self.containerParent);
            appendContainer = pane.type === 'popup' ? self.containerParent.find('.jupiterx-child-popup-content') : self.containerParent;
            appendContainer.append(self.contentContainer);

            // Trigger embeddded.
            self.deferred.embedded.resolve();
          });
        });
      };

      inject(popup);
    },

    /**
     * Update UI to reflect expanded state.
     *
     * @since 1.0.0
     *
     * @param {boolean} expanded
     * @param {object}  args
     */
    onChangeExpanded: function onChangeExpanded() {
      // No events on expanded toggle.
    },

    /**
     * Attach events.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    attachEvents: function attachEvents() {
      var self = this;

      // Triggered from popup section.
      self.containerParent.on('expanded', function () {
        if (!self.expanded()) {
          self.expand();
        }
      });
    },

    /**
     * Find content element which is displayed when the section is expanded.
     *
     * @since 1.0.0
     *
     * @returns {jQuery} Detached content element.
     */
    getContent: function getContent() {
      return this.container.find('.jupiterx-controls').detach();
    }
  });

  /**
   * Class for Link section.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.LinkSection
   *
   * @constructor
   */
  JupiterX.LinkSection = api.Section.extend({
    attachEvents: function attachEvents() {}
  });

  /**
   * Control components handle js plugins, events, and etc. Must code the 'ready' script properly in able for the
   * component to initialized correctly in a single or group control. It is important for a control that stores array or object
   * format values to have a hidden data container that binds in {{{ data.link }}}.
   *
   * @since 1.0.0
   */
  var Components = {
    /**
     * Color control component.
     */
    'jupiterx-color': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-color-control'),
            params = _.defaults(this.params, {
          opacity: true
        });

        _.each(controls, function (control) {
          var container = $(control),
              field = container.find('.jupiterx-color-control-field'),
              correctColor = void 0,
              setColor = void 0;

          correctColor = function correctColor(color) {
            return color.getAlpha() < 1 ? color.toRgbString() : color.toHexString();
          };

          setColor = function setColor(color) {
            field.val(!_.isNull(color) ? correctColor(color) : '').trigger('change');
          };

          container.find('.jupiterx-color-control-field').spectrum({
            containerClassName: 'jupiterx-spectrum-container',
            replacerClassName: 'jupiterx-spectrum-replacer',
            preferredFormat: "hex6",
            showButtons: false,
            showInitial: true,
            showInput: true,
            showAlpha: params.opacity,
            allowEmpty: true,
            change: setColor,
            move: setColor
          });
        });
      }
    },

    /**
     * Image control component.
     */
    'jupiterx-image': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-image-upload-control');

        _.each(controls, function (control) {
          var container = $(control),
              field = container.find('input[type=hidden]'),
              previewer = container.find('.jupiterx-image-upload-control-preview'),
              remover = container.find('.jupiterx-image-upload-control-remove'),
              frame = void 0;

          container.on('click', function () {
            if (frame) {
              frame.open();
              return;
            }

            frame = wp.media({
              title: 'Insert Media',
              multiple: false
            });

            frame.on('select', function () {
              var attachment = frame.state().get('selection').first().toJSON();

              container.addClass('has-image');
              previewer.prop('src', attachment.url);
              field.val(attachment.url);
              field.trigger('change');
            });

            frame.open();
          });

          remover.on('click', function (e) {
            e.stopPropagation();
            container.removeClass('has-image');
            previewer.prop('src', '');
            field.val('');
            field.trigger('change');
          });
        });
      }
    },

    /**
     * RadioImage control component.
     */
    'jupiterx-radio-image': {
      /**
       * Initialize behaviors.
       *
       * @since 1.3.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-radio-image-control');

        _.each(controls, function (control) {
          var container = $(control);

          container.find('.pro-preview').on('click', function () {
            event.preventDefault();

            var $this = $(this),
                template = wp.template('customize-jupiterx-pro-preview-lightbox');

            $.featherlight({
              otherClose: '.jupiterx-pro-preview-back',
              variant: 'jupiterx-pro-preview-lightbox',
              html: template({
                preview: $this.data('preview')
              })
            });
          });
        });
      }
    },

    /**
     * Multicheck control component.
     */
    'jupiterx-multicheck': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-multicheck-control');

        _.each(controls, function (control) {
          var container = $(control),
              inputElements = container.find('input[type=checkbox]'),
              hiddenField = container.find('input[type=hidden]');

          container.find('input[type=checkbox]').on('change', function () {
            var value = [];

            // Create array values.
            inputElements.filter(':checked').each(function (i, field) {
              value[i] = $(field).val();
            });

            // Store to hidden data container.
            hiddenField.val(value).trigger('change');
          });
        });
      },
      /**
       * Filter the data before save.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      filterData: function filterData(value) {
        if (!_.isString(value)) {
          return value;
        }

        return value.split(',');
      }
    },

    /**
     * Choose control component.
     */
    'jupiterx-choose': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        if (!this.params.multiple) {
          return;
        }

        var controls = this.container.find('.jupiterx-choose-control');

        _.each(controls, function (control) {
          var container = $(control),
              inputElements = container.find('input[type=checkbox]'),
              hiddenField = container.find('input[type=hidden]');

          container.find('input[type=checkbox]').on('change', function () {
            var value = [];

            // Create array values.
            inputElements.filter(':checked').each(function (i, field) {
              value[i] = $(field).val();
            });

            // Store to hidden data container.
            hiddenField.val(value).trigger('change');
          });
        });
      },
      /**
       * Filter the data before save.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      filterData: function filterData(value) {
        return this.params.multiple && _.isString(value) ? value.split(',') : value;
      }
    },

    /**
     * Input control component.
     */
    'jupiterx-input': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-input-control');

        _.each(controls, function (control) {
          var container = $(control),
              input = container.find('input.jupiterx-input-control-input'),
              hidden = container.find('input[type=hidden]');

          input.on('keyup blur change', function () {
            hidden.trigger('change');
          });
        });
      }
    },

    /**
     * Font control component.
     */
    'jupiterx-font': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-font-control');

        _.each(controls, function (control) {
          var container = $(control);
          container.find('.jupiterx-select-field').select2({
            minimumResultsForSearch: -1,
            placeholder: 'Default',
            allowClear: true,
            containerCssClass: 'jupiterx-select2-container',
            dropdownCssClass: 'jupiterx-select2-dropdown jupiterx-select2-dropdown-wrapped',
            dropdownAutoWidth: true
          });
        });
      }
    },

    /**
     * Template selector component.
     */
    'jupiterx-template': {
      /**
       * Initialize behaviors.
       *
       * @since 1.1.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var control = this,
            params = control.params,
            templateType = params.templateType,
            container = control.container,
            elementor = jupiterx.elementor,
            $control = container.find('.jupiterx-control'),
            $select = container.find('select'),
            $edit = container.find('.jupiterx-edit'),
            $add = container.find('.jupiterx-add');
        var value = params.value;

        var updateSelect = function updateSelect() {
          if (typeof jupiterx.elementor === 'undefined') {
            return;
          }
          elementor.getTemplates({
            data: {
              type: templateType
            },
            beforeSend: function beforeSend() {
              $select.empty();
              $control.addClass('jupiterx-loading');
              $select.append('<option value selected>Loading...</option>');
            },
            success: function success(templates) {
              $select.empty();
              $control.removeClass('jupiterx-loading');
              $control.toggleClass('jupiterx-has-value', value ? true : false);
              $select.append('<option value selected>' + params.placeholder + '</option>');

              _.each(templates, function (name, id) {
                var selected = parseInt(value) === parseInt(id) ? 'selected' : '';
                $select.append('<option ' + selected + ' value="' + id + '">' + name + '</option>');
              });

              $select.trigger('change');
            }
          });
        };

        var hasSelected = function hasSelected() {
          var hasValue = value ? true : false;
          $control.toggleClass('jupiterx-has-value', hasValue);
        };

        // On change template.
        $select.on('change', function () {
          value = $select.val();
          hasSelected();
        });

        if (window.jupiterxPremium) {
          // Template edit button event.
          $edit.on('click', function (event) {
            event.preventDefault();
            if (typeof jupiterx.elementor === 'undefined') {
              return;
            }
            // Prevent editing.
            if (!$select.val()) {
              return;
            }

            jupiterx.elementor.openEditor({
              action: 'edit',
              post: $select.val(),
              beforeClose: function beforeClose(contentWindow) {
                var status = contentWindow.elementor.channels.editor.request('status');

                if (status === false) {
                  wp.customize.previewer.refresh();
                } else if (status === true && !confirm('The changes you made will be lost if you leave this page.')) {
                  return false;
                }
              }
            });
          });

          // Template add button event.
          $add.on('click', function (event) {
            event.preventDefault();
            if (typeof jupiterx.elementor === 'undefined') {
              return;
            }
            jupiterx.elementor.openEditor({
              action: 'new',
              type: params.templateType,
              beforeClose: function beforeClose(contentWindow) {
                value = contentWindow.elementor.config.document.id;
                $select.val(contentWindow.elementor.config.document.id);
                updateSelect();
              }
            });
          });
        }

        updateSelect();
      }
    },

    /**
     * Select control component.
     */
    'jupiterx-select': {
      /**
       * Initialize behaviors.
       *
       * @since 1.20.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var control = this,
            params = control.params,
            $control = control.container,
            $select = $control.find('select');

        if (_.isEmpty(params.jupiterx) || _.isEmpty(params.jupiterx.select2)) {
          return;
        }

        // Get selected value label.
        if (params.value.length) {
          jQuery.ajax({
            url: ajaxurl,
            method: 'GET',
            data: {
              _ajax_nonce: window.jupiterxCustomizer.nonce,
              action: 'jupiterx_core_customizer_get_post_title',
              id: params.value
            }
          }).done(function (response) {
            var selectedOption = new Option(response.data, params.value, true, true);

            $select.append(selectedOption).trigger('change');
          }).fail(function () {
            console.log('error');
          });
        }

        // Select2
        $select.select2({
          minimumInputLength: 2,
          placeholder: params.placeholder,
          allowClear: true,
          containerCssClass: 'jupiterx-select2-secondary',
          dropdownCssClass: 'jupiterx-select2-secondary',
          ajax: {
            url: ajaxurl,
            delay: 250,
            data: function data(select2Params) {
              var query = {
                _ajax_nonce: window.jupiterxCustomizer.nonce,
                action: params.jupiterx.select2.action,
                post_type: params.jupiterx.select2.post_type,
                s: select2Params.term
              };

              return query;
            },
            processResults: function processResults(data) {
              return {
                results: data.data
              };
            },
            cache: true
          }
        });
      }
    }
  };

  JupiterX.components = Components;

  /**
   * Get control components.
   *
   * @since 1.0.0
   *
   * @param {string} component
   * @returns {mixed}
   */
  var getControlComponents = function getControlComponents(component) {
    if (_.isUndefined(Components[component])) {
      return;
    }

    return Components[component];
  };

  /**
   * Handles events and data control uniquely.
   *
   * @since 1.0.0
   */
  var uniqueComponents = {
    /**
     * Child popup control component.
     */
    'jupiterx-child-popup': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var control = this,
            params = control.params,
            initialItems = void 0,
            updateItemsView = void 0;

        if (!_.isEmpty(params.bindItems)) {
          updateItemsView = function updateItemsView(to) {
            if (!_.isArray(to)) {
              return;
            }

            var display = to,
                items = control.container.find('.jupiterx-child-popup-control-item');

            items.each(function (i, element) {
              var item = $(element),
                  value = item.data('value'),
                  hidden = !_.contains(display, value);

              item.toggleClass('hidden', hidden);
            });
          };

          initialItems = wp.customize(params.bindItems).get();
          wp.customize(params.bindItems).bind(updateItemsView);
          updateItemsView(initialItems);
        }

        if (params.sortable) {
          control.container.find('.jupiterx-child-popup-control-items').sortable({
            stop: function stop() {
              var setting = [];

              control.container.find('.jupiterx-child-popup-control-item').each(function (i, item) {
                setting.push($(item).data('value'));
              });

              control.setting.set(setting);
            }
          });
        }

        if (!_.isEmpty(params.target)) {
          control.container.find('.jupiterx-button').on('click', function (event) {
            event.preventDefault();

            var button = $(this),
                childPopupId = button.data('id');

            wp.customize.section(params.target, function (target) {
              target.openChildPopup(childPopupId);
            });
          });
        }
      }
    },

    /**
     * Child popup control component.
     */
    'jupiterx-popup': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var control = this,
            params = control.params;

        control.container.find('.jupiterx-popup-control-button').on('click', function (event) {
          event.preventDefault();

          wp.customize.section(params.target, function (section) {
            section.expand();
          });
        });
      }
    },

    /**
     * Fonts selector component.
     */
    'jupiterx-fonts': {
      /**
       * Initialize.
       *
       * @since 1.0.0
       *
       * @param {string} id      Unique identifier for the control instance.
       * @param {object} options Options hash for the control instance.
       * @returns {void}
       */
      initialize: function initialize(id, options) {
        var control = this,
            value = void 0;

        options.params.subsets = {
          'latin': 'latin',
          'latin-ext': 'latin-ext',
          'cyrillic-ext': 'cyrillic-ext',
          'greek-ext': 'greek-ext',
          'greek': 'greek',
          'vietnamese': 'vietnamese',
          'cyrillic': 'cyrillic'
        };

        JupiterX.Control.prototype.initialize.call(control, id, options);

        value = control.setting.get();
        _.each(value, function (font) {
          control.loadWebFont(font, null, control);
          Fonts.addToStack(font);
        });
      },

      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var control = this,
            value = void 0;

        control.renderFontSelector();
        value = control.setting.get();
        _.each(value, control.renderFontPreview.bind(control));
      },

      /**
       * Register new font to stack.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      renderFontPreview: function renderFontPreview(font) {
        var control = this,
            container = void 0,
            template = void 0;

        container = control.container.find('.jupiterx-fonts-control');
        template = wp.template('customize-jupiterx-fonts-control-preview');
        container.append(template(font));
      },

      /**
       * Create font selector.
       *
       * @since 1.0.0
       *
       * @return {void}
       */
      renderFontSelector: function renderFontSelector() {
        var control = this,
            container = void 0,
            template = void 0;

        container = control.container.find('.jupiterx-fonts-control');
        template = wp.template('customize-jupiterx-fonts-control-selector');
        container.prepend(template(control.params));
      },

      /**
       * Load font.
       *
       * @since 1.0.0
       *
       * @return {void}
       */
      loadWebFont: function loadWebFont(font, callback, control) {
        var config = {},
            previewer = void 0,
            fontWeights = void 0;

        if (!font.type && !font.name) {
          return;
        }

        if (Fonts.isPrinted(font.name)) {
          if (_.isFunction(callback)) {
            callback();
          }
          return;
        }

        fontWeights = '100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';

        if (font.type === 'google') {
          config.google = {
            families: [font.name + ':' + fontWeights]
          };

          if (font.subsets) {
            config.google.families[0] += ':' + font.subsets.join(',');
          }
        }

        if (font.type === 'adobe' && control) {
          var adobeId = control.params.apiSource.adobe;

          if (adobeId) {
            config.typekit = {
              id: adobeId
            };
          }
        }

        previewer = $('#customize-preview iframe');
        if (previewer.attr('name')) {
          WebFont.load(_.extend(_.clone(config), {
            context: frames[previewer.attr('name')]
          }));
        }

        if (callback) {
          config.active = callback;
          config.inactive = callback;
          config.fontactive = callback;
          config.fontinactive = callback;
        }

        WebFont.load(config);
        Fonts.addToPrinted(font.name);
      },

      /**
       * Additional behaviors.
       *
       * Runs after the controls is embedded.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      actuallyReady: function actuallyReady() {
        this.previewEvents();
        this.fontSelectorEvents();
      },

      /**
       * Preview events.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      previewEvents: function previewEvents() {
        var control = this,
            container = control.container,
            previewElements = {};

        control.previewElements = {
          $register: container.find('.jupiterx-fonts-control-register')
        };

        previewElements = control.previewElements;

        // Preview remover.
        container.on('click', '.jupiterx-fonts-control-preview-remove', function (event) {
          event.preventDefault();

          if (!confirm('Are you sure you want to remove this font?')) {
            return;
          }

          var $button = $(event.currentTarget),
              $wrapper = $button.parent('.jupiterx-fonts-control-preview'),
              fontFamily = void 0,
              newFonts = void 0,
              fonts = void 0;

          fontFamily = $wrapper.data('fontFamily');
          fonts = _.clone(control.setting.get()), newFonts = _.filter(fonts, function (font) {
            return font.name !== fontFamily;
          });
          control.setting.set(newFonts);
          Fonts.removeFromStack(fontFamily);

          $wrapper.remove();
        });

        // Register font.
        previewElements.$register.on('click', function (event) {
          event.preventDefault();
          control.selectorElements.$wrapper.addClass('open');
          control.selectorElements.$fontFamilies.val(null).trigger('change');
          control.selectorElements.$textSample.removeProp('style');
        });
      },

      /**
       * Font selector behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      fontSelectorEvents: function fontSelectorEvents() {
        var control = this,
            container = control.container,
            selectorElements = {},
            resetSelector = void 0;

        control.selectorElements = {
          $wrapper: container.find('.jupiterx-fonts-control-popup'),
          $textSample: container.find('.jupiterx-fonts-control-selector-sample'),
          $fontFamiliesWrapper: container.find('.jupiterx-fonts-control-selector-families'),
          $fontFamilies: container.find('.jupiterx-fonts-control-selector-families select'),
          $filtersWrapper: container.find('.jupiterx-fonts-control-selector-filters'),
          $filters: container.find('.jupiterx-fonts-control-selector-filters select'),
          $subsets: container.find('.jupiterx-fonts-control-selector-subsets')
        };

        selectorElements = control.selectorElements;

        resetSelector = function resetSelector() {
          selectorElements.$wrapper.removeClass('open');
          selectorElements.$fontFamilies.val(null).trigger('change');
          selectorElements.$textSample.removeProp('style');

          _.each(control.params.subsets, function (value, key) {
            $('#jupiterx_fonts_subset_' + key).prop('checked', false);
          });

          selectorElements.$subsets.hide();
        };

        // Select2 Decorator | Source: https://stackoverflow.com/a/31600521
        $.fn.select2.amd.require(['select2/selection/multiple', 'select2/selection/search', 'select2/selection/eventRelay', 'select2/dropdown', 'select2/dropdown/attachBody', 'select2/dropdown/closeOnSelect', 'select2/compat/dropdownCss', 'select2/compat/containerCss', 'select2/utils'], function (MultipleSelection, Search, EventRelay, Dropdown, AttachBody, CloseOnSelect, DropdownCSS, ContainerCss, Utils) {
          var SelectionAdapter = void 0,
              DropdownAdapter = void 0;

          SelectionAdapter = Utils.Decorate(MultipleSelection, Search);
          SelectionAdapter = Utils.Decorate(SelectionAdapter, EventRelay);
          DropdownAdapter = Utils.Decorate(Dropdown, DropdownCSS);
          DropdownAdapter = Utils.Decorate(DropdownAdapter, CloseOnSelect);
          DropdownAdapter = Utils.Decorate(DropdownAdapter, AttachBody);

          selectorElements.$fontFamilies.select2({
            maximumResultsForSearch: 5,
            containerCssClass: 'jupiterx-select2-container jupiterx-select2-autocomplete',
            selectionAdapter: Utils.Decorate(SelectionAdapter, ContainerCss),
            dropdownCssClass: 'jupiterx-select2-dropdown jupiterx-select2-autocomplete',
            dropdownParent: selectorElements.$fontFamiliesWrapper.find('.jupiterx-select-control'),
            dropdownAdapter: DropdownAdapter,
            width: '100%'
          });
        });

        selectorElements.$fontFamilies.on('select2:select', function (event) {
          var data = event.params.data,
              font = {
            name: data.text,
            type: data.element.dataset.type,
            value: data.element.value
          },
              addStyle = void 0;

          if (font.type === 'google') {
            selectorElements.$subsets.show();
          } else {
            selectorElements.$subsets.hide();
          }

          selectorElements.$textSample.css('opacity', '0.65');

          addStyle = function addStyle() {
            selectorElements.$textSample.css({
              opacity: 1,
              fontFamily: font.value
            });
          };

          if (font.type !== 'system') {
            control.loadWebFont(font, addStyle, control);
          } else {
            addStyle();
          }
        });

        // Filter option.
        selectorElements.$filters.select2({
          minimumResultsForSearch: Infinity,
          containerCssClass: 'jupiterx-select2-container',
          dropdownCssClass: 'jupiterx-select2-dropdown jupiterx-select2-dropdown-wrapped',
          dropdownAutoWidth: true,
          width: '100%',
          templateResult: function templateResult(data) {
            return $('<span>' + data.text + '</span>');
          }
        });

        selectorElements.$filters.on('change', function (event) {
          var $this = $(event.target),
              selected = $this.find(':selected').val(),
              fontFamilySelected = selectorElements.$fontFamilies.select2('data');

          if (fontFamilySelected && fontFamilySelected.length > 0 && (!selected || selected === 'google')) {
            selectorElements.$subsets.show();
          } else {
            selectorElements.$subsets.hide();
          }

          selectorElements.$fontFamilies.html(null);

          _.each(control.params.fontFamilies, function (props, name) {
            var type = props.type || props,
                value = props.value || name;

            if (type === selected || selected === '') {
              var option = $('<option></option>', {
                'data-type': type,
                html: name,
                value: value
              });
              selectorElements.$fontFamilies.append(option);
            }
          });

          selectorElements.$fontFamilies.val(null).trigger('change');
        });

        // Submit button.
        selectorElements.$wrapper.on('click', '.jupiterx-fonts-control-selector-submit', function (event) {
          event.preventDefault();

          var data = void 0,
              newFont = void 0,
              value = void 0,
              filteredFonts = void 0;

          data = selectorElements.$fontFamilies.select2('data');

          if (_.isEmpty(data) || !data[0].text) {
            resetSelector();
            return;
          }

          newFont = {
            name: data[0].text,
            type: data[0].element.dataset.type,
            value: data[0].element.value
          };

          if (newFont.type === 'google') {
            var subsets = [];

            _.each(control.params.subsets, function (value, key) {
              if ($('#jupiterx_fonts_subset_' + key).prop('checked')) {
                subsets.push(value);
              }
            });

            newFont.subsets = subsets;
          }

          value = _.clone(control.setting.get());
          filteredFonts = _.filter(value, function (font) {
            return font.name === newFont.name;
          });

          if (_.isEmpty(filteredFonts)) {
            value.push(newFont);
            control.renderFontPreview(newFont);
            control.setting.set(value);
            Fonts.addToStack(newFont);
          }

          resetSelector();
        });

        // Cancel button.
        selectorElements.$wrapper.on('click', '.jupiterx-fonts-control-selector-cancel', function (event) {
          event.preventDefault();
          resetSelector();
        });

        resetSelector();
      }
    }

    /**
     * Get unique components.
     *
     * @since 1.0.0
     *
     * @param {string} component
     * @returns {mixed}
     */
  };var getUniqueComponents = function getUniqueComponents(component) {
    if (_.isUndefined(uniqueComponents[component])) {
      return;
    }

    return uniqueComponents[component];
  };

  /**
   * Class for control's base.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.Control
   *
   * @constructor
   */
  JupiterX.Control = api.Control.extend({
    types: {
      JUPITERX_SELECT: 'jupiterx-select',
      JUPITERX_EXCEPTIONS: 'jupiterx-exceptions',
      JUPITERX_TEMPLATE: 'jupiterx-template'
    },

    defaultActiveArguments: { duration: 0, completeCallback: $.noop },

    /**
     * Initialize.
     *
     * @since 1.0.0
     *
     * @param {string} id      Unique identifier for the control instance.
     * @param {object} options Options hash for the control instance.
     * @returns {void}
     */
    initialize: function initialize(id, options) {
      var control = this;

      api.Control.prototype.initialize.call(control, id, options);

      // After the control is embedded on the page, invoke this method.
      control.deferred.embedded.done(function () {
        control.responsiveEvents();
        control.previewRedirectionEvents();
        control.unitsEvents();
        control.unitInputValidate();
        control.stepizeInputs();
        control.actuallyReady();
      });
    },

    /**
     * Embed the control into the container document.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    embed: function embed() {
      var control = this,
          sectionId = control.section();

      if (!sectionId) {
        return;
      }

      wp.customize.section(sectionId, function (section) {
        section.expanded.bind(function (expanded) {
          if (expanded) {
            control.actuallyEmbed();
          }
        });
      });
    },

    /**
     * Actually embed to delay control render.
     *
     * This function is called in Section.onChangeExpanded() so the control
     * will only get embedded when the Section is expanded.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    actuallyEmbed: function actuallyEmbed() {
      if ('resolved' === this.deferred.embedded.state()) {
        return;
      }

      this.renderContent();
      this.deferred.embedded.resolve();
    },

    /**
     * Link elements between settings and inputs.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    linkElements: function linkElements() {
      var control = this,
          nodes = void 0,
          radios = {},
          element = void 0;

      nodes = control.container.find('[data-customize-setting-link], [data-setting-property-link]');

      nodes.each(function () {
        var node = $(this),
            property = void 0,
            viewport = void 0,
            name = void 0;

        if (node.data('customizeSettingLinked')) {
          return;
        }

        node.data('customizeSettingLinked', true);

        if (node.is(':radio')) {
          name = node.prop('name');

          if (radios[name]) {
            return;
          }

          radios[name] = true;
          node = nodes.filter('[name="' + name + '"]');
        }

        property = node.data('settingPropertyLink');

        if (property) {
          element = new api.Element(node);
          element.bind(function (to, from) {
            return control.savePropertyValue(to, from, node);
          });
          control.elements[property] = [];
          control.elements[property].push(element);
          return;
        }

        element = new api.Element(node);
        element.bind(function (to, from) {
          return control.saveValue(to, from, node);
        });
        control.elements.push(element);
      });
    },

    /**
     * Attach responsive events.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    responsiveEvents: function responsiveEvents() {
      var control = this;

      control.container.find('.jupiterx-responsive-switcher a').on('click', function (event) {
        api.previewedDevice.set($(event.currentTarget).data('device'));
      });
    },

    /**
     * Events to trigger preview redirection.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    previewRedirectionEvents: function previewRedirectionEvents() {
      var control = this;

      $(document).on('change', control.selector + ' .jupiterx-renew-preview', function (e) {
        var options = {};

        options[e.target.getAttribute('data-customize-setting-link')] = e.target.value;

        if (control.params.type === control.types.JUPITERX_EXCEPTIONS) {
          options[control.id] = control.setting();
        }

        control.redirectOptionPreview(control.params.section, options);
      });
    },

    /**
     * Change preview URL to a new one based on option.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    redirectOptionPreview: function redirectOptionPreview(sectionId, options) {
      if (!window.jupiterxCustomizer) {
        return;
      }

      wp.ajax.post('jupiterx_core_customizer_preview_redirect_url', {
        _ajax_nonce: window.jupiterxCustomizer.customizer_preview_redirect_url_nonce,
        section: sectionId,
        options: JSON.stringify(options)
      }).done(function (response) {
        if (!response.redirectUrl) {
          return;
        }

        if (api.previewer.previewUrl.get() === response.redirectUrl) {
          api.previewer.refresh();

          return;
        }

        api.previewer.previewUrl.set(response.redirectUrl);
      }).fail(function () {
        api.previewer.refresh();
      });
    },

    /**
     * Attach unit selector events.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    unitsEvents: function unitsEvents() {
      var controls = this.container.find('.jupiterx-control-units-container');

      $(document.body).on('click', function (e) {
        if (!e.target.closest('.jupiterx-control-unit-selector')) {
          controls.find('.jupiterx-control-unit-selector').removeClass('open');
        }
      });

      _.each(controls, function (control) {
        var container = $(control),
            field = container.find('input[type=hidden]'),
            unitSelector = container.find('.jupiterx-control-unit-selector'),
            selectedUnit = unitSelector.find('.selected-unit');

        if (selectedUnit[0].classList.contains('disabled')) {
          return;
        }

        unitSelector.on('click', 'li', function (e) {
          unitSelector.toggleClass('open');

          if (e.target.classList.contains('selected-unit')) {
            return;
          }

          var unit = e.target.innerText.toLowerCase();
          selectedUnit.text(unit);
          field.val(unit).trigger('change');

          var $inputs = container.parents('.jupiterx-control').find(unitSelector.data('inputs'));
          'px' === unit ? $inputs.attr('step', 1) : $inputs.attr('step', .1);
          $inputs.trigger('stepper.destroy', [unit]);
          $inputs.stepper({ decimals: 1, min: 0, max: 1000 });
        });
      });
    },

    /**
     * Change unit to none if input is not a numeric value.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    unitInputValidate: function unitInputValidate() {
      var controls = this.container.find('.jupiterx-input-control:not(.jupiterx-text-control)');

      _.each(controls, function (control) {
        var container = $(control),
            field = container.find('input[type=text]'),
            unitsContainer = container.find('.jupiterx-control-units-container'),
            selectedUnit = unitsContainer.find('.selected-unit'),
            initialUnit = selectedUnit.text();

        field.on('keyup focus blur', function () {
          var val = field.val();
          if (!isNaN(parseFloat(val)) && isFinite(val) || _.isEmpty(val)) {
            if ('-' === selectedUnit.text()) {
              selectedUnit.text(initialUnit);
            }
            return;
          }
          selectedUnit.text('-');
        });
      });
    },

    /**
     * Add Stepper to inputs
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    stepizeInputs: function stepizeInputs() {
      var controls = [];
      controls.push(this.container.find('input.jupiterx-input-control-input'));
      controls.push(this.container.filter('.customize-control-jupiterx-box-model').find('input.jupiterx-box-model-control-input'));

      _.each(controls, function (control) {
        control.stepper({
          decimals: 1,
          min: 0,
          max: 1000
        });
      });
    },

    /**
     * Save value generically.
     *
     * @since 1.0.0
     *
     * @param {mixed} to    New value of the control.
     * @param {mixed} from  Old value of the control.
     * @param {object} node Element that holds the value.
     * @returns {void}
     */
    saveValue: function saveValue(to, from, node) {
      var control = this,
          viewport = void 0,
          value = void 0,
          setting = void 0;

      if (to === from) {
        return;
      }

      if (!_.isUndefined(control.filterData)) {
        to = control.filterData(to);
      }

      viewport = node.data('settingViewportLink');
      value = to;

      if (viewport) {
        setting = control.setting.get();
        value = _.extend({}, setting);
        value[viewport] = to;
        control.setting.set(value);
        return;
      }

      control.setting.set(value);
    },

    /**
     * Save value as property from object.
     *
     * @since 1.0.0
     *
     * @param {mixed} to    New value of the control.
     * @param {mixed} from  Old value of the control.
     * @param {object} node Element that holds the value.
     * @returns {void}
     */
    savePropertyValue: function savePropertyValue(to, from, node) {
      var control = this,
          viewport = void 0,
          property = void 0,
          setting = void 0,
          value = void 0;

      if (to === from) {
        return;
      }

      if (!_.isUndefined(control.filterData)) {
        to = control.filterData(to);
      }

      viewport = node.data('settingViewportLink');
      property = node.data('settingPropertyLink');
      setting = control.setting.get();
      value = _.extend({}, setting);

      if (viewport) {
        value[viewport] = _.extend({}, setting[viewport]);
        value[viewport][property] = to;
        control.setting.set(value);
        return;
      }

      value[property] = to;
      control.setting.set(value);
    },

    /**
     * Additional behaviors.
     *
     * Runs after the controls is embedded.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    actuallyReady: function actuallyReady() {}
  });

  JupiterX.TemplateControl = JupiterX.Control.extend({
    /**
     * Add control template.
     *
     * The field param must have property `id` and `property` to work properly.
     *
     * @since 1.0.0
     *
     * @param {object} field     Field control arguments.
     * @param {jquery} container Container of the control where to append.
     * @returns {object}
     */
    addControl: function addControl(field, container) {
      var control = this,
          templateId = void 0,
          template = void 0,
          classes = void 0,
          content = void 0,
          component = void 0;

      // Format the template name.
      templateId = 'customize-control-' + field.type + '-content';

      // Nothing to do since template is not found.
      if (!document.getElementById('tmpl-' + templateId)) {
        return;
      }

      // Set control container.
      container = container || control.container.find('.jupiterx-group-controls');

      // Create field defaults args.
      field = _.defaults(field, {
        column: 12,
        cssClass: ''
      });

      // Create data link.
      field.link = 'data-customize-setting-link="' + field.id + '" ' + (field.link ? field.link : '');

      // Create string of input attributes.
      if (_.isObject(field.inputAttrs)) {
        field.inputAttrs = _.map(field.inputAttrs, function (value, attr) {
          return attr + '="' + value + '"';
        }).join(' ');
      }

      // Create string of control attributes.
      if (_.isObject(field.controlAttrs)) {
        field.controlAttrs = _.map(field.controlAttrs, function (value, attr) {
          return attr + '="' + value + '"';
        }).join(' ');
      }

      // Control classes.
      classes = _.compact(['jupiterx-col-' + field.column, 'customize-control customize-control-' + field.type, field.cssClass, field.property ? control.params.type + '-control-' + field.property.replace(/_/g, '-') : '', field.responsive ? 'customize-control-responsive' : '']);

      // Create template wrapper.
      content = $('<li></li>', {
        class: classes.join(' ')
      });

      // Append template to control container.
      template = wp.template(templateId);
      content.append(template(field));
      container.append(content);

      // Link elements by content.
      control.linkElements(field, content);

      // Create control events.
      component = Components[field.type];
      if (component && component.ready) {
        component.ready.call({ container: content, params: field });
      }

      return content;
    },

    /**
     * Link elements between settings and inputs.
     *
     * @since 1.0.0
     *
     * @param {object} params  Field parameters.
     * @param {jquery} content Holds the control elements.
     * @returns {void}
     */
    linkElements: function linkElements(params, content) {
      if (_.isEmpty(params) || _.isEmpty(content)) {
        return;
      }

      var control = this,
          nodes = void 0,
          radios = {},
          element = void 0,
          property = void 0;

      nodes = content.find('[data-customize-setting-link], [data-setting-property-link]');
      property = params.property;
      control.elements[property] = [];

      nodes.each(function () {
        var node = $(this),
            name = void 0;

        if (node.data('customizeSettingLinked')) {
          return;
        }

        node.data('customizeSettingLinked', true);

        if (node.is(':radio')) {
          name = node.prop('name');

          if (radios[name]) {
            return;
          }

          radios[name] = true;
          node = nodes.filter('[name="' + name + '"]');
        }

        element = new api.Element(node);
        control.elements[property].push(element);
        element.bind(function (to, from) {
          return control.saveValue(to, from, property, node, params);
        });
      });
    },

    /**
     * Save value behavior.
     *
     * @since 1.0.0
     *
     * @param {mixed}  to       New value of the control.
     * @param {mixed}  from     Old value of the control.
     * @param {string} property Base property name.
     * @param {object} node     Element that holds the value.
     * @param {object} params   Field params.
     * @returns {void}
     */
    saveValue: function saveValue(to, from, property, node, params) {
      var control = this,
          component = Components[params.type],
          trail = property,
          responsive = control.params.responsive,
          propertyLink = void 0,
          viewportLink = void 0,
          value = void 0;

      /**
       * Recursively search keys and apply value at the last key name.
       *
       * @param {string} path  String keys path format.
       * @param {mixed}  value New value.
       * @param {object} ref   Object reference from existing value.
       */
      var getObj = function getObj(path, value, ref) {
        var keys = path.split('.');
        ref = _.extend({}, ref);

        if (keys.length === 1) {
          ref[keys[0]] = value;
          return ref;
        } else {
          var current = keys.shift();
          ref[current] = getObj(keys.join('.'), value, ref[current]);
          return ref;
        }
      };

      // Call component data filter before save.
      if (component && component.filterData) {
        to = component.filterData.call({ params: params }, to);
      }

      propertyLink = node.data('settingPropertyLink');

      if (propertyLink) {
        trail = trail + '.' + propertyLink;
      }

      viewportLink = node.data('settingViewportLink');

      // If control is responsive and viewport is empty then set it to desktop.
      viewportLink = responsive && !viewportLink ? 'desktop' : viewportLink;

      if (viewportLink) {
        trail = viewportLink + '.' + trail;
      }

      value = _.extend({}, control.setting.get());
      value = getObj(trail, to, value);
      control.setting.set(value);
    }
  });

  /**
   * Class for Group control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.GroupControl
   *
   * @constructor
   */
  JupiterX.GroupControl = JupiterX.TemplateControl.extend({
    /**
     * Initialize.
     *
     * @since 1.0.0
     *
     * @param {string} id      Unique identifier for the control instance.
     * @param {object} options Options hash for the control instance.
     * @returns {void}
     */
    initialize: function initialize(id, options) {
      JupiterX.Control.prototype.initialize.call(this, id, options);
    },

    /**
     * Initialize behaviors.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    ready: function ready() {
      var control = this,
          params = control.params;

      // Append control.
      _.each(params.fields, function (field) {
        control.addControl(field);
      });
    }
  });

  /**
   * Class for Color control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.ColorControl
   *
   * @constructor
   */
  JupiterX.ColorControl = JupiterX.Control.extend(getControlComponents('jupiterx-color'));

  /**
   * Class for Image control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.ImageControl
   *
   * @constructor
   */
  JupiterX.ImageControl = JupiterX.Control.extend(getControlComponents('jupiterx-image'));

  /**
   * Class for RadioImage control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.RadioImageControl
   *
   * @constructor
   */
  JupiterX.RadioImageControl = JupiterX.Control.extend(getControlComponents('jupiterx-radio-image'));

  /**
   * Class for Multicheck control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.MulticheckControl
   *
   * @constructor
   */
  JupiterX.MulticheckControl = JupiterX.Control.extend(getControlComponents('jupiterx-multicheck'));

  /**
   * Class for Choose control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.ChooseControl
   *
   * @constructor
   */
  JupiterX.ChooseControl = JupiterX.Control.extend(getControlComponents('jupiterx-choose'));

  /**
   * Class for Input control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.InputControl
   *
   * @constructor
   */
  JupiterX.InputControl = JupiterX.Control.extend(getControlComponents('jupiterx-input'));

  /**
   * Class for Font control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.FontControl
   *
   * @constructor
   */
  JupiterX.FontControl = JupiterX.Control.extend(getControlComponents('jupiterx-font'));

  /**
   * Class for Child Popup control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.ChildPopupControl
   *
   * @constructor
   */
  JupiterX.ChildPopupControl = JupiterX.Control.extend(getUniqueComponents('jupiterx-child-popup'));

  /**
   * Class for Popup control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.PopupControl
   *
   * @constructor
   */
  JupiterX.PopupControl = JupiterX.Control.extend(getUniqueComponents('jupiterx-popup'));

  /**
   * Class for Fonts control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.FontsControl
   *
   * @constructor
   */
  JupiterX.FontsControl = JupiterX.Control.extend(getUniqueComponents('jupiterx-fonts'));

  /**
   * Class for Template control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.Template
   *
   * @constructor
   */
  JupiterX.Template = JupiterX.Control.extend(getControlComponents('jupiterx-template'));

  /**
   * Class for Select control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.SelectControl
   *
   * @constructor
   */
  JupiterX.SelectControl = JupiterX.Control.extend(getControlComponents('jupiterx-select'));

  /**
   * Class for Exceptions control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.ExceptionsControl
   *
   * @constructor
   */
  JupiterX.ExceptionsControl = JupiterX.TemplateControl.extend({
    /**
     * Initialize.
     *
     * @since 1.0.0
     *
     * @param {string} id      Unique identifier for the control instance.
     * @param {object} options Options hash for the control instance.
     * @returns {void}
     */
    initialize: function initialize(id, options) {
      this.controls = {};
      JupiterX.Control.prototype.initialize.call(this, id, options);
    },

    /**
     * Initialize behaviors.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    ready: function ready() {
      var control = this,
          params = control.params,
          value = control.setting.get();

      // Render current exceptions.
      _.each(value, function (data, id) {
        if (params.fields[id]) {
          control.addException({
            id: id,
            text: params.fields[id].label,
            value: data
          });
        }
      });
    },

    /**
     * Additional behaviors.
     *
     * Runs after the controls is embedded.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    actuallyReady: function actuallyReady() {
      var control = this,
          choices = _.keys(control.params.fields),
          fields = control.params.fields,
          container = control.container,
          addWrapper = container.find('.jupiterx-exceptions-control-add'),
          addSelect = addWrapper.find('.jupiterx-select-control-field'),
          removeOption = void 0,
          addOption = void 0,
          toggleOptions = void 0;

      removeOption = function removeOption(id) {
        addSelect.find('option[value=' + id + ']').remove();
        toggleOptions();
      };

      addOption = function addOption(id) {
        if (fields[id]) {
          addSelect.append(new Option(fields[id].label, id, false, false));
          toggleOptions();
        }
      };

      toggleOptions = function toggleOptions() {
        var options = addSelect.find('option');
        addWrapper.toggle(options.length > 0);
      };

      _.each(_.keys(control.setting.get()), function (key) {
        removeOption(key);
      });

      addSelect.select2({
        minimumResultsForSearch: -1,
        placeholder: 'Add New Exception',
        allowClear: true,
        containerCssClass: 'jupiterx-select2-container',
        dropdownCssClass: 'jupiterx-select2-dropdown',
        // dropdownParent: addWrapper,
        width: '100%'
      }).on('select2:select', function (event) {
        event.preventDefault();

        var data = event.params.data,
            value = control.setting.get();

        // Make sure key not exists yet.
        if (data && data.id && !value[data.id]) {
          control.exceptionInitialData(data);
          control.addException(data);
          removeOption(data.id);
        }

        // Reset to placeholder name.
        addSelect.val('').change();
      });

      addWrapper.find('.jupiterx-button').click(function (event) {
        event.preventDefault();
        addSelect.select2('open');
      });

      container.on('click', '.jupiterx-exceptions-control-remove', function (event) {
        event.preventDefault();
        if (!confirm('Are you sure you want to remove this exception?')) {
          return;
        }

        var element = $(event.currentTarget),
            id = element.data('id'),
            value = control.setting.get();

        if (id && value[id]) {
          control.removeException(id);
          element.closest('.jupiterx-exceptions-control-group').remove();
          addOption(id);
        }

        addSelect.val('').change();
      });

      addSelect.val('').change();

      control.setting.bind('change', function (to, from) {
        to = to || {};
        from = from || {};

        if (Object.keys(to).length === Object.keys(from).length) {
          return;
        }

        var options = {};
        options[control.id] = JSON.stringify(Object.keys(to));

        control.redirectOptionPreview(control.params.section, options);
      });
    },

    addException: function addException(data) {
      var control = this,
          params = control.params,
          container = control.container.find('.jupiterx-exceptions-control-items'),
          template = wp.template('customize-jupiterx-exceptions-control-group'),
          content = $(template(data)),
          controls = content.find('.jupiterx-group-controls');

      control.controls[data.id] = {};

      // Add each control.
      _.each(params.fields[data.id].options, function (field, property) {
        field.property = property;
        field.value = data.value ? data.value[property] : field.default;
        field.id = control.id + '_' + data.id + '_' + property;
        field.link = 'data-setting-property-link="' + data.id + '.' + property + '"';

        // Add control.
        control.controls[data.id][property] = control.addControl(field, controls);
      });

      if (control.id === 'jupiterx_title_bar_exceptions') {
        control.titleBarEvents(data.id);
        control.titleBarToggleFields(data.id);
      }

      // Finally append the controls container.
      container.append(content);
    },

    exceptionInitialData: function exceptionInitialData(data) {
      var control = this,
          params = control.params,
          id = data.id,
          value = void 0;


      value = _.extend({}, control.setting.get());
      value[id] = {};
      _.each(params.fields[id].options, function (field, property) {
        value[id][property] = field.default;
      });
      control.setting.set(value);
    },

    removeException: function removeException(id) {
      var control = this,
          value = void 0;

      value = _.extend({}, control.setting.get());
      delete value[id];
      control.setting.set(value);
    },

    titleBarEvents: function titleBarEvents(id) {
      var control = this,
          controls = control.controls;

      if (!controls[id]) {
        return;
      }

      if (controls[id].type) {
        controls[id].type.find('input[type=radio]').on('change', function () {
          control.titleBarToggleFields(id);
        });
      }
    },

    titleBarToggleFields: function titleBarToggleFields(id) {
      var control = this,
          value = control.setting.get();

      if (!value[id]) {
        return;
      }

      var controls = control.controls[id],
          isCustom = value[id].type === '_custom';

      var toggle = function toggle(property, _toggle) {
        if (controls[property]) {
          controls[property].toggleClass('hidden', _toggle);
        }
      };

      toggle('full_width', isCustom);
      toggle('elements', isCustom);
      toggle('title_tag', isCustom);
      toggle('pro_box', !isCustom);
      toggle('template', !isCustom);
    },

    /**
     * Save value behavior.
     *
     * @since 1.0.0
     *
     * @param {mixed}  to       New value of the control.
     * @param {mixed}  from     Old value of the control.
     * @param {string} property Base property name.
     * @param {object} node     Element that holds the value.
     * @param {object} params   Field params.
     * @returns {void}
     */
    saveValue: function saveValue(to, from, property, node, params) {
      var control = this,
          component = Components[params.type],
          propertyMap = void 0,
          setting = void 0,
          value = void 0;

      // Create control events.
      if (component && component.filterData) {
        to = component.filterData.call({ params: params }, to);
      }

      /**
       * Recursively search keys and apply value at the last key name.
       *
       * @param {string} map   String keys path format.
       * @param {mixed}  value New value.
       * @param {object} ref   Object reference from existing value.
       */
      var setObj = function setObj(map, value, ref) {
        var keys = map.split('.');
        ref = _.extend({}, ref);

        if (keys.length === 1) {
          ref[keys[0]] = value;
          return ref;
        } else {
          var current = keys.shift();
          ref[current] = setObj(keys.join('.'), value, ref[current]);
          return ref;
        }
      };

      propertyMap = node.data('settingPropertyLink');
      setting = control.setting.get();
      value = setObj(propertyMap, to, setting);
      control.setting.set(value);

      if (params.type !== control.types.JUPITERX_SELECT) {
        var options = {};
        options[params.id] = to;
        options[control.id] = control.setting();

        control.redirectOptionPreview(control.params.section, options);
      }
    }
  });

  /**
   * Class for Background control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.BackgroundGroupControl
   *
   * @constructor
   */
  JupiterX.BackgroundGroupControl = JupiterX.GroupControl.extend({
    /**
     * Additional behaviors.
     *
     * Runs after the controls is embedded.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    actuallyReady: function actuallyReady() {
      var control = this,
          setting = control.setting.get(),
          classicFields = control.container.find('.for-classic'),
          gradientFields = control.container.find('.for-gradient'),
          videoFields = control.container.find('.for-video'),
          initialType = !_.isUndefined(setting.type) ? setting.type : 'classic',
          toggleFields = void 0;

      if (control.params.responsive) {
        initialType = !_.isUndefined(setting.desktop) && !_.isUndefined(setting.desktop.type) ? setting.desktop.type : 'classic';
      }

      toggleFields = function toggleFields(type) {
        if (type) {
          classicFields.toggleClass('hidden', type !== 'classic' ? true : false);
          gradientFields.toggleClass('hidden', type !== 'gradient' ? true : false);
          videoFields.toggleClass('hidden', type !== 'video' ? true : false);
        }
      };

      _.each(control.elements.type, function (element) {
        element.bind(toggleFields);
      });

      toggleFields(initialType);
    }
  });

  // Add new sections to wp.customize.sectionConstructor.
  $.extend(api.sectionConstructor, {
    'kirki-popup': JupiterX.PopupSection,
    'kirki-pane': JupiterX.PaneSection,
    'kirki-jupiterx-link': JupiterX.LinkSection
  });

  // Add new controls to wp.customize.controlConstructor
  $.extend(api.controlConstructor, {
    'jupiterx-text': JupiterX.Control,
    'jupiterx-input': JupiterX.InputControl,
    'jupiterx-textarea': JupiterX.Control,
    'jupiterx-select': JupiterX.SelectControl,
    'jupiterx-toggle': JupiterX.Control,
    'jupiterx-choose': JupiterX.ChooseControl,
    'jupiterx-divider': JupiterX.Control,
    'jupiterx-label': JupiterX.Control,
    'jupiterx-position': JupiterX.Control,
    'jupiterx-radio-image': JupiterX.RadioImageControl,
    'jupiterx-multicheck': JupiterX.MulticheckControl,
    'jupiterx-color': JupiterX.ColorControl,
    'jupiterx-image': JupiterX.ImageControl,
    'jupiterx-child-popup': JupiterX.ChildPopupControl,
    'jupiterx-popup': JupiterX.PopupControl,
    'jupiterx-box-model': JupiterX.Control,
    'jupiterx-fonts': JupiterX.FontsControl,
    'jupiterx-font': JupiterX.FontControl,
    'jupiterx-exceptions': JupiterX.ExceptionsControl,
    'jupiterx-template': JupiterX.Template,
    'jupiterx-pro-box': JupiterX.Control
  });

  // Add new group controls to wp.customize.controlConstructor
  $.extend(api.controlConstructor, {
    'jupiterx-box-shadow': JupiterX.GroupControl,
    'jupiterx-typography': JupiterX.GroupControl,
    'jupiterx-border': JupiterX.GroupControl,
    'jupiterx-background': JupiterX.BackgroundGroupControl
  });

  /**
   * Make custom css very high priority.
   */
  api.bind('ready', function () {
    if (api.panel('woocommerce')) {
      api.panel('woocommerce').priority(285);
    }
    api.section('custom_css').priority(1000);
  });

  // Do actions after contents reflowed.
  api.bind('pane-contents-reflowed', function () {
    // Force removal of section head container or accordion button.
    api.section.each(function (section) {
      if (section.params.type === 'kirki-pane' || section.params.type === 'kirki-popup' && section.params.hidden) {
        section.headContainer.remove();
      }
    });
  });

  // Append popup template.
  var customizeControls = $('#customize-controls'),
      popupContent = wp.template('customize-jupiterx-popup-content');

  customizeControls.append(popupContent());

  // Create draggable for popups.
  var popups = $('.jupiterx-popup');

  popups.draggable({
    containment: 'body',
    handle: '.jupiterx-popup-header, .jupiterx-child-popup-header'
  });
})(jQuery, wp, jupiterx);
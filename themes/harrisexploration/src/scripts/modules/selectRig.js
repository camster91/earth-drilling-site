/* global mainParams */
import $ from 'jquery';
import qs from 'qs';

const selectRig = {
  init() {
    if (window.gform && typeof window.gform !== 'undefined') {
      this.activate();

      $(document).on('gform_post_render', () => {
        this.addEventHandlers();
      });
    }
  },
  addEventHandlers() {
    this.rigInfoButtons = $('.js-get-rig-info');
    this.rigInfoModal = $('.js-rig-info-modal');
    this.selectRigButtons = $('button[data-rig]');

    this.rigInfoButtons.off('click').on('click', (e) => {
      e.preventDefault();
      const rig = $(e.currentTarget).data('rig');

      if (rig) {
        $('body').addClass('is-loading');
        $.ajax(mainParams.ajaxUrl, {
          data: {
            action: 'hny_get_rig_info',
            rig,
          },
        })
          .done((response) => {
            this.rigInfoModal.html(response.data).foundation('open');
            $('body').removeClass('is-loading');
          });
      }
    });

    this.selectRigButtons.off('click').on('click', (e) => {
      const el = $(e.currentTarget);
      const rig = el.data('rig');
      const title = el.data('rigTitle');

      if (rig && title) {
        this.rig = rig;
        this.title = title;
      }
    });
  },
  activate() {
    $(document).ajaxSuccess((event, xhr, settings) => {
      const { data } = settings;
      const params = qs.parse(data);

      if (params && Object.keys(params).length && params.action && params.action === 'gpnf_refresh_markup') {
        const { gpnf_nested_form_field_id: fieldId, gpnf_parent_form_id: parentFormId } = params;

        this.refreshView();

        if (fieldId && parentFormId) {
          const nestedField = $(`#field_${parentFormId}_${fieldId} label`);

          if (nestedField && nestedField.length) {
            $('html, body').stop().animate({
              scrollTop: nestedField.offset().top,
            }, 1000);
          }
        }
      }
    });

    $(document).on('gform_post_render gpnf_post_render', () => {
      this.refreshView();
    });

    window.gform.addAction('gpnf_init_nested_form', (nestedFormId) => {
      const nestedForm = $(`#gform_${nestedFormId}`);
      const select = $('.js-select-rig select', nestedForm);

      if (this.title) {
        nestedForm.parents('.gpnf-nested-form').siblings().find('span.ui-dialog-title')
          .html(`Add Rig: ${this.title}`);
      }

      nestedForm.parents('.gpnf-edit-form').siblings().find('span.ui-dialog-title')
        .html(`Edit Dates: ${select.find('option:selected').text()}`);

      if (this.rig) {
        select.val(this.rig);
      }
    });
  },
  refreshView() {
    const tableEls = $('.gpnf-nested-entries').find('td[data-heading="Rig"]');
    const buttons = $('button[data-rig-title]');

    buttons.removeAttr('disabled');

    tableEls.each((index, el) => {
      const row = $(el);
      const title = row.data('value');
      const button = $(`button[data-rig-title="${title}"]`);

      if (button) {
        button.attr('disabled', 'disabled');
      }
    });

    $('.datepicker').attr('autocomplete', 'off');
  },
};

export default selectRig;

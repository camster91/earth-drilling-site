import $ from 'jquery';

const forms = {
  init() {
    $(document).on('gform_post_render', (event, id) => {
      const form = $(`form[id="gform_${id}"]`);
      form.removeClass('is-loading');
      this.addSubmitListener();
    });

    this.addSubmitListener();
  },

  addSubmitListener() {
    $('form[id^="gform_"] button[type="submit"]').on('click', (e) => {
      const form = $(e.currentTarget).parents('form');
      form.addClass('is-loading');
    });
  },
};

export default forms;

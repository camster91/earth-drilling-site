import $ from 'jquery';
import 'jquery-clickout';

export default {
  keycodes: {
    esc: 27,
  },
  els: {
    search: '.js-search-form',
    toggler: '.js-search-toggle',
  },

  body: $('body'),

  init() {
    this.search = $(this.els.search);
    this.toggler = $(this.els.toggler);
    this.input = $('input[type="text"]', this.els.search);
    this.outside = $(`${this.els.toggler}, ${this.els.search}`);

    $(document).on('keyup', (e) => {
      if (this.isOpen() && e.keyCode === this.keycodes.esc) {
        this.close();
      }
    });

    this.toggler.on('click', (e) => this.toggle(e));
  },

  toggle(e) {
    e.preventDefault();

    if (!this.isOpen()) {
      this.open();
    } else {
      this.close(e);
    }
  },

  open() {
    this.input.focus();

    if (!this.body.hasClass('search')) {
      this.body.addClass('is-search-open');

      this.outside.on('clickout', () => {
        this.close();
      });
    }
  },

  close() {
    this.body.removeClass('is-search-open');
    this.outside.off('clickout');
    this.input.blur();
  },

  isOpen() {
    return !!this.body.hasClass('is-search-open');
  },
};

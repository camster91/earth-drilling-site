import $ from 'jquery';

export default {
  dropdown: '.js-year-select',
  init() {
    this.dropdown = $(this.dropdown);
    this.dropdown.change((e) => {
      const { value } = e.currentTarget;
      window.location.href = value;
    });
  },
};

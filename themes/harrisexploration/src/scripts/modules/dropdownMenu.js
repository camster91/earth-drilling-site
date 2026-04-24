/* global Foundation */
import $ from 'jquery';

export default {
  options: {
    disableHover: true,
    clickOpen: true,
    closeOnClickInside: false,
  },

  init(el) {
    return new Foundation.DropdownMenu($(el), this.options);
  },
};

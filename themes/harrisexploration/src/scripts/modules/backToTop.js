import $ from 'jquery';
import scrollTo from '../util/scrollTo';

export default {
  el: '.js-back-to-top',
  threshold: 100,

  checkScroll() {
    const top = $(window).scrollTop();
    return top > this.threshold
      ? this.el.addClass('is-visible')
      : this.el.removeClass('is-visible');
  },

  init() {
    this.el = $(this.el);
    this.el.on('click', () => scrollTo(0));
    $(window).scroll(() => this.checkScroll());
    this.checkScroll();
  },
};

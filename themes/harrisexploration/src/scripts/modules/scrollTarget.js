import $ from 'jquery';
import wpadminbar from '../util/wpadminbar';

const doScroll = (el, extraOffset = 0) => {
  if (el && el.length) {
    $('html,body').stop().animate(
      {
        scrollTop: Math.floor(el.offset().top - extraOffset - wpadminbar()),
      }, 500,
    );
  }

  return false;
};

const scrollTarget = {
  init: () => {
    if (window.location.hash) {
      const hash = window.location.hash.replace('#', '');

      if (hash) {
        window.scrollTo(0, 0);

        $(document).ready(() => {
          doScroll($(`#${hash}`), $(`[data-scroll-target="${hash}"]`).outerHeight());
        });
      }
    }

    $('[data-scroll-target]').on(
      'click', (e) => {
        e.preventDefault();
        const target = $(e.currentTarget).data('scroll-target');
        doScroll($(`#${target}`), $(e.currentTarget).outerHeight());
      },
    );
  },
};

export default scrollTarget;

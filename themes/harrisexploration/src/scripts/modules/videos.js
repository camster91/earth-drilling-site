import $ from 'jquery';

const videos = {
  els: 'iframe[src*="vimeo.com"], iframe[src*="youtube.com"]',

  init() {
    this.els = $(this.els);

    this.els.each((index, item) => {
      const el = $(item);
      this.addWrapper(el);
    });
  },

  addWrapper(el) {
    el.wrap('<div class="responsive-embed widescreen"/>');
  },
};

export default videos;

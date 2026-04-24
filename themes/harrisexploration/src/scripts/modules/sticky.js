/* eslint-disable new-cap */
import $ from 'jquery';
import hcSticky from 'hc-sticky';
import waypoints from './waypoints';
import wpadminbar from '../util/wpadminbar';

const sticky = {
  items: [],

  init() {
    const els = $('.js-sticky');

    if (els.length !== 0) {
      els.each((index) => {
        const item = els.eq(index);
        const extraOffset = 0;
        const options = {
          top: extraOffset + wpadminbar(),
        };

        this.items.push(new hcSticky(item.get(0), options));
      });
    }
  },

  resize() {
    this.items.forEach((el) => {
      const options = el.options();
      el.update($.extend(options, { top: wpadminbar() }));
      el.refresh();
    });
  },
};

$(document).ready(() => {
  sticky.init();
  waypoints.init();
});

$(window).on('resize', () => {
  sticky.resize();
});

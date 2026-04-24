import $ from 'jquery';
import wpadminbar from '../util/wpadminbar';

const waypoints = {
  links: $('.js-waypoint-link'),
  linksArray: [],

  init() {
    this.bindScrollEvents();
    this.setWaypoints();
    $(window).trigger('scroll');
  },

  bindScrollEvents() {
    $(window).scroll(() => this.scrollHandler());
  },

  setWaypoints() {
    this.linksArray = this.links.map((index) => $(this.links[index]).data('scroll-target'));
  },

  scrollHandler() {
    const windowPos = $(window).scrollTop();

    $.each(this.linksArray, (index) => {
      const el = this.linksArray[index];
      const waypoint = $(`#${el}`);

      if (waypoint && waypoint.length) {
        const anchor = $(`[data-scroll-target="${el}"]`);
        const waypointPosition = Math.floor(
          waypoint.offset().top - wpadminbar() - anchor.outerHeight(),
        );
        const waypointHeight = Math.floor(waypoint.outerHeight());

        if (windowPos >= waypointPosition && windowPos < (waypointPosition + waypointHeight)) {
          const parent = anchor.parents().find('.secondary-nav__scroll');

          if (parent && parent.length && !anchor.hasClass('is-active')) {
            parent.stop().animate(
              {
                scrollLeft: Math.floor(anchor.offset().left),
              }, 500,
            );
          }

          anchor.addClass('is-active');
        } else {
          anchor.removeClass('is-active');
        }
      }
    });

    const active = this.linksArray.filter((index) => this.links.eq(index).hasClass('is-active'));

    if (!active.length) {
      this.links.eq(0).addClass('is-active');
    }
  },
};

export default waypoints;

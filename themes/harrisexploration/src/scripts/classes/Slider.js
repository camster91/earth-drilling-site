import $ from 'jquery';
import 'slick-carousel';

const nextArrow = '<button type="button" class="slick-next slick-arrow" role="button"><svg viewBox="0 0 18 18" role="img" aria-label="Next" focusable="false" style="display: block;"><path fill-rule="evenodd" d="M4.293 1.707A1 1 0 1 1 5.708.293l7.995 8a1 1 0 0 1 0 1.414l-7.995 8a1 1 0 1 1-1.415-1.414L11.583 9l-7.29-7.293z"></path></svg></button>';
const prevArrow = '<button type="button" class="slick-prev slick-arrow" role="button"><svg viewBox="0 0 18 18" role="img" aria-label="Previous" focusable="false" style="display: block;"><path fill-rule="evenodd" d="M13.703 16.293a1 1 0 1 1-1.415 1.414l-7.995-8a1 1 0 0 1 0-1.414l7.995-8a1 1 0 1 1 1.415 1.414L6.413 9l7.29 7.293z"></path></svg></button>';
const defaults = {
  speed: 600,
  dots: false,
  arrows: false,
  slidesToShow: 1,
  infinite: true,
  rows: 0,
};

class Slider {
  constructor(config = {}) {
    this.config = $.extend({}, defaults, config) || defaults;
    this.animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
  }

  init(el) {
    this.el = $(el);

    if (this.config.arrows) {
      const arrows = { nextArrow, prevArrow };
      this.config = $.extend(this.config, arrows);
    }

    this.el.on('init', () => {
      if (this.config.animate) {
        const elements = this.el.find('.slick-slide:first-child [data-animation]');
        this.doAnimations(elements);

        this.el.on('beforeChange', (e, slider, currentSlide, nextSlide) => {
          const slides = this.el.find(`[data-slick-index="${nextSlide}"] [data-animation]`);
          this.doAnimations(slides);
        });
      }
    }).slick(this.config);
  }

  doAnimations(elements) {
    elements.each((index, item) => {
      const $this = $(item);
      const $animationDelay = $this.data('delay');
      const $animationType = `animated ${$this.data('animation')}`;

      $this.css({
        'animation-delay': $animationDelay,
        '-webkit-animation-delay': $animationDelay,
      });

      $this.addClass($animationType).one(this.animationEndEvents, () => {
        $this.removeClass($animationType);
        $this.addClass('is-animated');
      });
    });
  }
}

export default Slider;

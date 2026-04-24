import $ from 'jquery';
import Slider from './classes/Slider';

const sliders = {
  init: () => {
    const els = {
      rigPhotos: {
        el: '.js-rig-photos',
        config: {
          dots: false,
          infinite: true,
          speed: 500,
          fade: false,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 6000,
          arrows: true,
          animate: false,
          rows: 0,
        },
      },
      heroCaptions: {
        el: '.js-hero-captions',
        config: {
          dots: false,
          infinite: true,
          speed: 500,
          fade: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 6000,
          arrows: false,
          animate: true,
          rows: 0,
          asNavFor: '.js-hero-backgrounds',
        },
      },
      heroPhotos: {
        el: '.js-hero-backgrounds',
        config: {
          dots: false,
          infinite: true,
          speed: 500,
          fade: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 6000,
          arrows: true,
          animate: false,
          rows: 0,
          asNavFor: '.js-hero-captions',
        },
      },
    };
    $.each(els, (index, slider) => {
      new Slider(slider.config).init(slider.el);
    });
  },
};

$(document).ready(() => {
  sliders.init();
  let slide = 0;

  $('button[data-open="team-modal"]').on('click', (e) => {
    const target = $(e.currentTarget);
    slide = parseInt(target.data('slide'), 10);
  });

  const teamModal = $('.js-team-modal');

  teamModal.on('open.zf.reveal', () => {
    const slider = $('.js-team-modal-slider');

    slider.slick({
      slidesToShow: true,
      slidesToScroll: true,
      dots: false,
      arrows: true,
      autoplay: false,
      fade: true,
      draggable: true,
      infinite: true,
      slide: '.team-slide',
      nextArrow: '.team-modal__next',
      prevArrow: '.team-modal__prev',
      rows: 0,
    });

    slider.slick('slickGoTo', slide, false);
  });

  teamModal.on('closed.zf.reveal', () => {
    $('.js-team-modal-slider.slick-initialized').slick('unslick');
  });
});

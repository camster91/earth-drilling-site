import $ from 'jquery';

const scrollTo = (offset) => {
  const body = $('html, body');
  body.on('scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove', () => {
    body.stop();
  });

  body.clearQueue().animate({
    scrollTop: offset,
    easing: 'easeInOutExpo',
  }, 1000, () => {
    body.off('scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove');
  });
};

export default scrollTo;

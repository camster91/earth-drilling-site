/* eslint-disable max-len, no-console */
import $ from 'jquery';
import inView from 'in-view';
import './autoload/autoload';
import backToTop from './modules/backToTop';
import dropdownMenu from './modules/dropdownMenu';
import scrollTarget from './modules/scrollTarget';
import videos from './modules/videos';
import galleryModal from './modules/galleryModal';
import './modules/sticky';
import search from './modules/search';
import selectRig from './modules/selectRig';
import forms from './modules/forms';
import yearSelect from './modules/yearSelect';

console.log(
  '%cWebsite Design by',
  'font: 200 16px -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Fira Sans\', \'Droid Sans\', \'Helvetica Neue\', Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\';color:#CCC',
);
console.log(
  '%cHoneycomb Creative',
  'font: 200 28px -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Fira Sans\', \'Droid Sans\', \'Helvetica Neue\', Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\';color:#ffcc05',
);
console.log(
  '%chttps://www.honeycombcreative.com',
  'font: 200 12px -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Fira Sans\', \'Droid Sans\', \'Helvetica Neue\', Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\';color:#CCC',
);

inView('.js-in-view').on('enter', (el) => {
  el.classList.add('in-view');
});

$(document).ready(() => {
  backToTop.init();
  galleryModal.init();
  dropdownMenu.init('.js-primary-nav');
  dropdownMenu.init('.js-header-utility');
  forms.init();
  yearSelect.init();
  search.init();
  videos.init();
});

selectRig.init();
scrollTarget.init();

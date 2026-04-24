import $ from 'jquery';

const wpadminbar = () => {
  const body = $('body');
  const adminBar = $('#wpadminbar');

  return body.hasClass('admin-bar') && adminBar.length ? adminBar.outerHeight() : 0;
};

export default wpadminbar;

/* global jQuery */

(function ($) {
  const blocks = () => {
    const selectLayout = $(
      '[data-name="block_layout"] > .acf-input > .acf-radio-list li input[type="radio"]',
    );

    selectLayout.each((index) => {
      const selected = $(selectLayout[index]);
      if (selected.val() && selected.is(':checked')) {
        const row = selected.parents('.acf-row');
        row.attr('data-block-layout', selected.val());
      }
    });

    selectLayout.on('change', (e) => {
      const selected = $(e.target);
      const row = selected.parents('.acf-row');
      if (selected.val() && selected.is(':checked')) {
        row.attr('data-block-layout', selected.val());
      }
    });
  };

  if (window.acf) {
    window.acf.addAction('load', blocks);
    window.acf.addAction('append', blocks);
  }
}(jQuery));

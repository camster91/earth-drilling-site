import $ from 'jquery';

const galleryModal = {
  body: $('body'),
  els: $('a[data-photo], .gallery-item a'),
  modal: $('.js-gallery-modal'),

  init() {
    this.content = $('div[data-photo]', this.modal);
    this.prevArrow = $('button[data-prev]', this.modal);
    this.nextArrow = $('button[data-next]', this.modal);

    this.els.each((index) => {
      this.addClickHandler(index);
    });

    this.modal.on('open.zf.reveal', () => {
      this.body.removeClass('is-loading');
    });

    this.modal.on('closed.zf.reveal', () => {
      this.resetModal();
      this.body.removeClass('is-loading');
    });

    this.prevArrow.on('click', () => {
      this.updatePhoto(this.prevIndex);
    });

    this.nextArrow.on('click', () => {
      this.updatePhoto(this.nextIndex);
    });
  },

  updatePhoto(index) {
    this.body.addClass('is-loading');
    const el = this.els.eq(index);
    const src = el.attr('href');

    this.resetModal();
    this.body.addClass('is-loading');
    const title = el.attr('title') ? `<strong>${el.attr('title')}</strong> - ` : '';
    this.content.append(
      `<img src=${src} alt="" /><span class="count">${title}Image ${index + 1} of ${this.els.length}</span>`,
    );

    this.content.imagesLoaded(() => {
      this.setIndexes(index);

      if (!this.modal.is(':visible')) {
        this.modal.foundation('open');
      }

      this.body.removeClass('is-loading');
    });
  },

  setIndexes(index) {
    const hasPrev = !!(index > 0);
    const hasNext = !!(index < this.els.length - 1);
    this.prevIndex = hasPrev ? index - 1 : this.els.length - 1;
    this.nextIndex = hasNext ? index + 1 : 0;
  },

  addClickHandler(index) {
    const el = this.els.eq(index);

    el.on('click', (e) => {
      e.preventDefault();
      this.updatePhoto(index);
    });
  },

  resetModal() {
    this.content.html('');
  },
};

export default galleryModal;

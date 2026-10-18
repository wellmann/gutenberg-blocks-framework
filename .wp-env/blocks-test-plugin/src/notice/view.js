document.querySelectorAll('.block-notice').forEach((notice) => {
  const content = notice.querySelector('.block-notice__content');

  notice.querySelector('.block-notice__label')?.addEventListener('click', () => {
    content.hidden = !content.hidden;
  });
});

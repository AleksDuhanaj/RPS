document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.nav');
  if (toggle && nav) toggle.addEventListener('click', () => {
    const open = nav.classList.toggle('nav-open');
    toggle.setAttribute('aria-expanded', String(open));
  });

  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', event => {
      if (!window.confirm(form.dataset.confirm || 'Ali si prepričan?')) event.preventDefault();
    });
  });

  document.querySelectorAll('[data-remove]').forEach(button => {
    button.addEventListener('click', () => {
      if (!window.confirm('Odstranim izdelek iz košarice?')) return;
      document.getElementById('remove-id').value = button.dataset.remove;
      document.getElementById('remove-form').submit();
    });
  });

  document.querySelectorAll('form[data-validate]').forEach(form => {
    form.addEventListener('submit', event => {
      const password = form.querySelector('[name="password"]');
      const confirm = form.querySelector('[name="confirm"]');
      if (password && confirm && password.value !== confirm.value) {
        event.preventDefault();
        confirm.setCustomValidity('Gesli se ne ujemata.');
        confirm.reportValidity();
      }
    });
    const confirm = form.querySelector('[name="confirm"]');
    if (confirm) confirm.addEventListener('input', () => confirm.setCustomValidity(''));
  });
});

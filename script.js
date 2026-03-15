document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('regForm');
  const hasReportRadios = form.querySelectorAll('input[name="has_report"]');
  const reportWrapper = document.getElementById('report-title-wrapper');
  const reportTitle = document.getElementById('report_title');

  function toggleReportField() {
    const hasReport = document.querySelector('input[name="has_report"]:checked').value === '1';
    reportWrapper.style.display = hasReport ? 'block' : 'none';
    reportTitle.toggleAttribute('required', hasReport);
  }

  hasReportRadios.forEach(r => r.addEventListener('change', toggleReportField));
  // начальное состояние
  toggleReportField();

  // базовая визуальная валидация (дополняет HTML5)
  form.addEventListener('submit', e => {
    let valid = true;

    // можно добавить дополнительные проверки, если хочется
    // например, проверить длину ФИО, запрет трёх пробелов подряд и т.д.

    if (!valid) {
      e.preventDefault();
      alert('Проверьте правильность заполнения формы');
    }
  });
});

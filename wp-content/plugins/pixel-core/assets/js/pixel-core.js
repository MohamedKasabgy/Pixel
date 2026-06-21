(function () {
  const fileInputs = document.querySelectorAll('input[type="file"]');
  fileInputs.forEach(function (input) {
    input.addEventListener('change', function () {
      const maxSize = Number(input.dataset.maxSize || 0);
      if (input.files && input.files[0] && maxSize > 0 && input.files[0].size > maxSize) {
        const maxSizeMb = Math.max(1, Math.floor(maxSize / (1024 * 1024)));
        alert('This file exceeds the ' + maxSizeMb + 'MB artwork upload limit.');
        input.value = '';
      }
    });
  });
})();

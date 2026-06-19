(function () {
  const fileInputs = document.querySelectorAll('input[type="file"]');
  fileInputs.forEach(function (input) {
    input.addEventListener('change', function () {
      if (input.files && input.files[0] && input.files[0].size > 100 * 1024 * 1024) {
        alert('This MVP allows files up to 100MB. Please contact support for larger artwork files.');
        input.value = '';
      }
    });
  });
})();

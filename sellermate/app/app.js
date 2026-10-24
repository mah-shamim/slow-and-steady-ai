(function () {
  'use strict';
  var form = document.getElementById('listing-form');
  var button = document.getElementById('generate-btn');
  var status = document.getElementById('status');
  var errorBox = document.getElementById('error');
  var placeholder = document.getElementById('placeholder');
  var result = document.getElementById('result');
  var fields = {title:'out-title', short_description:'out-short', full_description:'out-long', meta_description:'out-meta', social_caption:'out-social', keywords:'out-keywords'};

  function setStatus(text, className) { status.textContent = text; status.className = 'status' + (className ? ' ' + className : ''); }
  function showError(message) { errorBox.textContent = message; errorBox.classList.remove('d-none'); }
  function hideError() { errorBox.textContent = ''; errorBox.classList.add('d-none'); }
  function setOutput(data) {
    Object.keys(fields).forEach(function (key) {
      document.getElementById(fields[key]).textContent = data[key] || '';
    });
    placeholder.classList.add('d-none'); result.classList.remove('d-none');
  }
  form.addEventListener('submit', function (event) {
    event.preventDefault(); hideError(); button.disabled = true; button.textContent = 'Generating locally…'; setStatus('Working', 'busy');
    var payload = {
      product_name: document.getElementById('product_name').value.trim(),
      category: document.getElementById('category').value.trim(),
      price: document.getElementById('price').value.trim(),
      features: document.getElementById('features').value.trim(),
      keywords: document.getElementById('keywords').value.trim(),
      language: document.getElementById('language').value,
      tone: document.getElementById('tone').value
    };
    fetch('/sellermate-api/generate', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload)})
      .then(function (response) { return response.json().then(function (data) { if (!response.ok) { throw new Error(data.detail || 'Generation failed.'); } return data; }); })
      .then(function (data) { setOutput(data); setStatus('Generated', 'ready'); })
      .catch(function (error) { showError(error.message + ' Make sure Docker is running and the local model has been downloaded.'); setStatus('Needs attention', ''); })
      .then(function () { button.disabled = false; button.innerHTML = 'Generate my listing <span aria-hidden="true">→</span>'; });
  });
  document.querySelectorAll('[data-copy]').forEach(function (button) {
    button.addEventListener('click', function () {
      var target = document.getElementById(button.getAttribute('data-copy'));
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(target.textContent).then(function () { button.textContent = 'Copied'; setTimeout(function () { button.textContent = 'Copy'; }, 1200); });
      } else { showError('Clipboard access is unavailable in this browser. Select and copy the text manually.'); }
    });
  });
  document.getElementById('copy-all').addEventListener('click', function () {
    var text = Object.keys(fields).map(function (key) { return key.replace(/_/g, ' ').toUpperCase() + '\n' + document.getElementById(fields[key]).textContent; }).join('\n\n');
    if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(text).then(function () { document.getElementById('copy-all').textContent = 'Copied all fields'; }); }
    else { showError('Clipboard access is unavailable in this browser. Select and copy the text manually.'); }
  });
}());

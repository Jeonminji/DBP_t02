var formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 0,
  });
  
  function formatMoney(v) {
    return formatter.format(v);
  }
  
  function deformatMoney(v) {
    return parseFloat(v.replace(/[^0-9-.]/g, ''));
  }
  
  function removeDollarSign(v) {
    return v.replace(/(\.|\,|\$)+/g, '');
  }
  
  function showMessage(message, classes) {
    var alert = deque.createAlert(message, classes);
    alertRegion.appendChild(alert);
    createAriaLiveContainer(message);
  }
  
  function createAriaLiveContainer(message) {
    var liveregion_assertive = document.querySelector('#liveregion_assertive');
    if(!liveregion_assertive) {
      var parentDequeSliderContainer = document.querySelector('.deque-slider-multirange');
      var liveRegionElement = document.createElement('div');
      liveRegionElement.id = 'liveregion_assertive';
      liveRegionElement.setAttribute('role', 'alert');
      liveRegionElement.setAttribute('aria-live', 'assertive');
      liveRegionElement.setAttribute('aria-atomic', 'true');
      liveRegionElement.style.height = '0px';
      liveRegionElement.style.overflow = 'hidden';
      parentDequeSliderContainer.appendChild(liveRegionElement);
    }
    document.getElementById('liveregion_assertive').innerText = message;
  }
  
  var multirangeSlider = document.querySelector(".deque-slider-multirange");
  
  var startThumb = multirangeSlider.querySelector('.minPrice');
  var stopThumb = multirangeSlider.querySelector('.maxPrice');
  
  var thumbs = [{
    label: startThumb.getAttribute('aria-label'),
    labelFromValue: formatMoney,
    stepSize: startThumb.getAttribute('data-increment'),
    initialValue: startThumb.getAttribute('aria-valuenow'),
    classes: [],
    textParser: deformatMoney
  },{
    label: stopThumb.getAttribute('aria-label'),
    labelFromValue: formatMoney,
    stepSize: stopThumb.getAttribute('data-increment'),
    initialValue: stopThumb.getAttribute('aria-valuenow'),
    classes: [],
    textParser: deformatMoney
  }]
  
  var minValue = startThumb.getAttribute('aria-valuemin');
  minValue = parseInt(minValue);
  var maxValue = startThumb.getAttribute('aria-valuemax');
  maxValue = parseInt(maxValue);
  var orientation = startThumb.getAttribute('aria-orientation');
  
  deque.createMultirange(multirangeSlider, thumbs, minValue, maxValue, orientation);
  
  var alertRegion = multirangeSlider.querySelector('#alertRegion');
  
  var startThumb = multirangeSlider.querySelector('.minPrice');
  var stopThumb = multirangeSlider.querySelector('.maxPrice');
  var multirangeLabel = multirangeSlider.querySelector('#label');
  
  var startInput = multirangeSlider.querySelector('input:first-child');
  var stopInput = multirangeSlider.querySelector('label:nth-of-type(2) input');
  
  
  startInput.addEventListener('blur', validateInputs);
  startInput.addEventListener('keydown', onEnter);
  stopInput.addEventListener('blur', validateInputs);
  stopInput.addEventListener('keydown', onEnter);
  
  startInput.addEventListener('keyup', triggerEmptyMessage);
  stopInput.addEventListener('keyup', triggerEmptyMessage);
  
  function triggerEmptyMessage(e) {
    if(e.currentTarget.value.length != 0) {
      createAriaLiveContainer('');
    }
  }
  
  startThumb.addEventListener('click', function(e) {
    e.currentTarget.focus();
  });
  
  stopThumb.addEventListener('click', function(e) {
    e.currentTarget.focus();
  });
  
  function onEnter(e) {
    if(e.which === 13) {
      validateInputs(e);
    }
  }
  
  function validateInputs(e) {
    alertRegion.innerHTML = '';
    if(!e.currentTarget.value) {
      e.currentTarget.focus();
      e.target.classList.add('invalid');
      showMessage('Value can not be empty', ['error']);
      e.target.setAttribute('aria-invalid', true);
      return false;
    } else {
      createAriaLiveContainer('');
      e.target.classList.remove('invalid');
      e.target.removeAttribute('aria-invalid');
    }
  
    var newVal = deformatMoney(e.target.value);
    var maxVal = startThumb.getAttribute('aria-valuemax');
    var minVal = startThumb.getAttribute('aria-valuemin');
    var maxValNow = stopThumb.getAttribute('aria-valuenow');
    var minValNow = startThumb.getAttribute('aria-valuenow');
  
  
  
    if (newVal > maxVal || newVal < minVal) {
      e.target.classList.add('invalid');
      showMessage('Value must be between $150,000 and $450,000', ['error']);
      e.target.setAttribute('aria-invalid', true);
      e.currentTarget.focus();
    } else if (isNaN(removeDollarSign(e.target.value))) {
      e.target.classList.add('invalid');
      showMessage('Please enter a valid dollar amount', ['error']);
      e.target.setAttribute('aria-invalid', true);
      e.currentTarget.focus();
    } else if (minValNow > maxValNow){
      e.target.classList.add('invalid');
      showMessage('The minimum value must be less than the maximum value', ['error']);
      e.target.setAttribute('aria-invalid', true);
      e.currentTarget.focus();
    } else {
      e.target.classList.remove('invalid');
      e.target.removeAttribute('aria-invalid');
    }
  }
  
  multirangeSlider.addEventListener('change', setMultirangeSliderLabel);
  multirangeSlider.addEventListener('change', validateSlider);
  
  function validateSlider(e){
    alertRegion.innerHTML = '';
    var maxValNow = stopThumb.getAttribute('aria-valuenow');
    var minValNow = startThumb.getAttribute('aria-valuenow');
    if (minValNow > maxValNow){
      e.target.classList.add('invalid');
      showMessage('The minimum value must be less than the maximum value', ['error']);
      e.target.setAttribute('aria-invalid', true);
    } else {
      createAriaLiveContainer('');
      e.target.classList.remove('invalid');
      e.target.removeAttribute('aria-invalid');
    }
    e.target.focus();
  }
  
  function setMultirangeSliderLabel() {
    var label = 'Between ' + startThumb.getAttribute('aria-valuetext');
    label += ' and ' + stopThumb.getAttribute('aria-valuetext');
  
    multirangeLabel.innerText = label;
  }
  
  setMultirangeSliderLabel();
document.addEventListener("DOMContentLoaded", function(event) {   
  document.querySelector('body').classList.add('ivp_active')
  function inViewport(elem, callback) {
    return new IntersectionObserver(entries => {
        entries.forEach(entry => callback(entry))
    }).observe(elem)
  }
  function changeClass(element) {
    inViewport(element, callbackElement => {
      if (callbackElement.isIntersecting) { element.classList.add('wivp','iivp') } else { element.classList.remove('iivp') }
    })
  }
  document.querySelectorAll('.ivp').forEach(element => { changeClass(element) })
});
document.addEventListener('DOMContentLoaded', e => {
  document.querySelector('body').classList.add('ivp_active')
})

function inViewport(elem, margin_top, margin_bottom, callback) {
  return new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        callback(entry)
      })
    }, 
    { rootMargin: margin_bottom + ' 0px ' + margin_top + ' 0px' }
  ).observe(elem)
}

function changeClass(element) {
  inViewport(element, '0px', '0px', observedElement => {
    
    belowOrAbove(element, observedElement)

    if (observedElement.isIntersecting) { 
      element.classList.add('wivp','iivp') 
    } else { 
      element.classList.remove('iivp') 
    }
  })
  inViewport(element, '-50%', '-50%', observedElement => {
     if (observedElement.isIntersecting) { 
       element.classList.add('over_half_ivp') 
      } else { 
       element.classList.remove('over_half_ivp') 
     }
  })
}

function belowOrAbove(element, observedElement) {
  if (observedElement.boundingClientRect.top >= 0) {
    element.classList.add('below_viewport')
    element.classList.remove('above_viewport')
  } else {
    element.classList.add('above_viewport')
    element.classList.remove('below_viewport')
  }
}

document.querySelectorAll('.ivp').forEach(element => { changeClass(element) })

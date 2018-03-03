/**
 * Wordpress theme for Gumbo Millennium
 *
 * @author Sven Boekelder
 * @author Thomas Hop
 * @author Stefan Petter
 * @author Roelof Roos
 * @author Arjen Stens
 *
 * @license GPL-2.0
 * @source https://github.com/gumbo-millennium/themosis/tree/develop/theme
 */

const jquery = require('jquery')
const popper = require('popper.js')

// Inform the user when the document is ready
document.addEventListener('DOMContentReady', () => {
  console.log('Holy shit, It\'s alive!')

  console.log(`Loaded jQuery version ${jquery.fn.jquery.split(' ')[0]}`)
})

window.Popper = popper
window.jQuery = window.$ = jquery

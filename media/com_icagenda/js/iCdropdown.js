/* ===========================================================================
 * iCdropdown.js v1.0.0 2021-11-28
 * ===========================================================================
 * Copyright 2021 Cyril Rezé
 * Licensed under MIT
 * =========================================================================== */

(document => {

  document.addEventListener('DOMContentLoaded', () => {
    const menuItems = document.querySelectorAll('.ic-dropdown');

    const keys = {
      tab:    9,
      enter:  13,
      esc:    27,
      space:  32,
      left:   37,
      up:     38,
      right:  39,
      down:   40
    };

    let subIndex = -1;

    const gotoSubIndex = function(dropDown, idx) {
      if (dropDown.querySelector('.ic-dropdown-menu.open')) {
        const items = dropDown.querySelectorAll('li a');
          if (idx == items.length) {
            idx = 0;
          } else if (idx < 0) {
            idx = items.length - 1;
          }
        items[idx].focus();
        subIndex = idx;
      }
    }

    function hideOnClickOutside(dropDown, toggler, dropdownMenu) {
      const outsideClickListener = event => {
        if (!dropDown.contains(event.target)) {
          dropdownMenu.className = 'ic-dropdown-menu';
          toggler.classList.remove('show');
          toggler.setAttribute('aria-expanded', 'false');
          removeClickListener();
        }
      }

      const removeClickListener = () => {
        document.removeEventListener('click', outsideClickListener)
      }

      document.addEventListener('click', outsideClickListener)
    }

    Array.prototype.forEach.call(menuItems, function(el, i) {
      const dropDown = el;
      const dropdownMenu = el.querySelector('.ic-dropdown-menu');
      const isVisible = dropdownMenu => !!dropdownMenu && !!( dropdownMenu.offsetWidth || dropdownMenu.offsetHeight || dropdownMenu.getClientRects().length );
      const toggler = el.querySelector('button.ic-dropdown-toggle');

      document.addEventListener('click', function(event){
        if (isVisible(dropdownMenu)) {
          hideOnClickOutside(dropDown, toggler, dropdownMenu);
          subIndex = -1;
        }
      });

      el.querySelector('button.ic-dropdown-toggle').addEventListener('click',  function(event) {
        if (this.getAttribute('aria-expanded') == 'false' || this.getAttribute('aria-expanded') ==  null) {
          this.parentNode.querySelector('.ic-dropdown-menu').classList.add('open');
          this.classList.add('show');
          this.setAttribute('aria-expanded', 'true');
          this.focus();
          if (toggler.offsetWidth > dropdownMenu.offsetWidth) {
            dropdownMenu.setAttribute('style', 'min-width:'+toggler.offsetWidth+'px; border-top-right-radius:0;');
          }
        } else {
          this.parentNode.querySelector('.ic-dropdown-menu').classList.remove('open');
          this.classList.remove('show');
          this.setAttribute('aria-expanded', 'false');
          subIndex = -1;
        }
        event.preventDefault();
      });

      el.addEventListener('keydown', function(event) {
        let prevdef = false;
        switch (event.keyCode) {
          case keys.down:
            toggler.setAttribute('aria-expanded', 'true');
            toggler.classList.add('show');
            dropdownMenu.classList.add('open');
            gotoSubIndex(dropDown, subIndex + 1);
            prevdef = true;
            break;
          case keys.up:
            gotoSubIndex(dropDown, subIndex - 1);
            prevdef = true;
            break;
          case keys.esc:
            toggler.setAttribute('aria-expanded', 'false');
            toggler.classList.remove('show');
            dropdownMenu.classList.remove('open');
            prevdef = true;
            break;
        }
        if (prevdef) {
          event.preventDefault();
        }
      });
    });
  });
})(document);

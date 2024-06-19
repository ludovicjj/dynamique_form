import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    menu = null;

    connect() {
        this.menu = this.element;
        const url = this.menu.dataset.url

        this.menu.addEventListener('click', async (e) => {
            if (!e.target.classList.contains('menu')) {
                return
            }
            const isOpen = this.menu.classList.contains('open')
            const menuState = !isOpen

            if (this.menu.classList.contains('open')) {
                this.menu.classList.remove('open')
                this.menu.classList.add('close')
            } else {
                this.menu.classList.add('open')
                this.menu.classList.remove('close')
            }

            await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ open: menuState })
            })
        });
    }
}
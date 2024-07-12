import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['table', 'tableBody'];
    observer = null;

    connect() {
        if (this.observer) {
            return
        }

        console.log(this.urlValue)
        this.observer = new MutationObserver(() => {
            const shouldReload = !this.hasTableBodyTarget;

            if (shouldReload) {
                this.element.src = this.getCurrentURL()
            }
        });
        this.observer.observe(this.element, {
            childList: true,
            characterData: true,
            subtree: true
        });
    }
    disconnect() {
        if (this.observer) {
            this.observer.disconnect();
        }
    }

    fetchLoading() {
        if (this.hasTableTarget) {
            this.tableTarget.classList.add('opacity-50')
        }
    }

    fetchRender() {
        if (this.hasTableTarget) {
            this.tableTarget.classList.remove('opacity-50')
        }
    }

    onRender(e) {
        //this.element.removeAttribute('src');
        console.log('CLEAR ATTR SRC FROM ELEMENT')
    }


    getCurrentURL () {
        return window.location.href
    }

    _getUrl() {
        let url = new URL(this.urlValue, window.location.origin);
        let currentPage = parseInt(url.searchParams.get('page'), 10);
        if (currentPage > 1) {
            url.searchParams.set('page', (currentPage - 1).toString());
        }

        return url
    }
}
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['table', 'tableBody'];
    observer = null;

    disconnect() {
        if (this.observer) {
            this.observer.disconnect();
        }
    }

    fetchLoading() {
        if (this.hasTableTarget) {
            console.log('frame-loading')
            this.tableTarget.classList.add('opacity-50')
        }
    }

    fetchRender() {
        if (this.hasTableTarget) {
            console.log('frame-render')
            this.tableTarget.classList.remove('opacity-50')
        }
    }

    onRender(e) {
        if (this.observer) {
            console.log('observer already initialized...')
            return
        }

        this.observer = new MutationObserver(() => {
            const shouldReload = !this.hasTableBodyTarget;
            if (shouldReload) {
                this.element.reload()
            }
        });
        this.observer.observe(this.element, {
            childList: true,
            characterData: true,
            subtree: true
        });
    }
}
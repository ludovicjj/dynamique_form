import { Controller } from '@hotwired/stimulus';
import { Modal } from 'bootstrap';

export default class extends Controller {
    static targets = ['dynamicContent', 'loadingContent', 'title'];
    modal = null
    observer = null;

    connect() {
        this.modal = Modal.getOrCreateInstance(this.element);

        if (this.hasDynamicContentTarget) {
            // when the content changes, call this.open()
            this.observer = new MutationObserver(() => {
                const shouldOpen = this.dynamicContentTarget.innerHTML.trim().length > 0;
                if (shouldOpen && !this.modal._isShown) {
                    this.open();
                } else if (!shouldOpen && this.modal._isShown) {
                    this.close();
                }
            });
            this.observer.observe(this.dynamicContentTarget, {
                childList: true,
                characterData: true,
                subtree: true
            });
        }

        this.element.addEventListener('hidden.bs.modal', () => {
            this.resetModalTitle()
        })
    }

    disconnect() {
        if (this.observer) {
            this.observer.disconnect();
        }

        if (this.modal._isShown) {
            this.close()
        }
    }

    showLoading() {
        // do nothing if the modal is already open
        if (this.modal._isShown) {
            return
        }

        this.dynamicContentTarget.innerHTML = this.loadingContentTarget.innerHTML;
    }

    open() {
        this.modal.show()
    }

    close() {
        this.modal.hide()
    }

    resetModalTitle() {
        this.titleTarget.classList.remove('fs-5')
        this.titleTarget.classList.add('fs-6', 'fw-normal')
        this.titleTarget.textContent = 'Chargement...'
    }
}
import { Controller } from '@hotwired/stimulus';
export default class extends Controller {
    static targets = ['dialog', 'dynamicContent', 'loadingContent', 'dialogClose'];

    observer = null;

    connect() {
        if (this.hasDynamicContentTarget) {
            // when the content changes, call this.open()
            this.observer = new MutationObserver(() => {
                const shouldOpen = this.dynamicContentTarget.innerHTML.trim().length > 0;
                if (shouldOpen && !this.dialogTarget.open) {
                    this.open();
                } else if (!shouldOpen && this.dialogTarget.open) {
                    this.close();
                }
            });
            this.observer.observe(this.dynamicContentTarget, {
                childList: true,
                characterData: true,
                subtree: true
            });
        }
    }

    disconnect() {
        if (this.observer) {
            this.observer.disconnect();
        }
        document.body.classList.remove('overflow-hidden');
    }

    showLoading() {
        if (!this.hasLoadingContentTarget) {
            return
        }

        if (this.dialogTarget.open) {
            return;
        }

        this.dynamicContentTarget.innerHTML = this.loadingContentTarget.innerHTML;
    }

    clickOutside(event) {
        if (event.target === this.dialogTarget) {
            this.close()
        }
    }

    open() {
        this.dialogTarget.showModal();
        document.body.classList.add('overflow-hidden');
    }

    close() {
        this.dialogTarget.classList.add('close');


        setTimeout(() => {
            this.dialogTarget.close();
            this.dialogTarget.classList.remove('close'); // Réinitialiser la classe
            document.body.classList.remove('overflow-hidden');
        }, 500);
    }
}
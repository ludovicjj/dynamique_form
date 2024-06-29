import { Controller } from '@hotwired/stimulus';
import { Modal } from 'bootstrap';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    modal = null;

    connect() {
        // get modal instance
        this.modal = Modal.getOrCreateInstance(this.element);

        // disabled keyboard / backdrop (press escape / click outside modal)
        this.modal._config.keyboard = false
        this.modal._config.backdrop = false

        document.addEventListener('modal:close', () => this.modal.hide());
    }
}
import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["inputName", "inputFile", "dropArea", "dropAreaInfo"]

    connect() {
        this.counter = 0;
        this.dropArea = this.dropAreaTarget
        this.inputFile = this.inputFileTarget
        this.dropAreaInfo = this.dropAreaInfoTarget
        this.defaultMessage = this.dropAreaInfo.textContent

        this.dropArea.addEventListener('click', () => this.inputFile.click())
        this.dropArea.addEventListener('dragenter', this.handleDragEnter.bind(this))
        this.dropArea.addEventListener('dragleave', this.handleDragLeave.bind(this))
        this.dropArea.addEventListener('dragover', this.handleDragOver)
        this.dropArea.addEventListener('drop', this.handleDrop.bind(this))
        this.inputFile.addEventListener('change', this.onUpload.bind(this))
    }

    onUpload(e) {
        console.log(e.currentTarget.files)
        const count = e.currentTarget.files.length
        if (count > 1) {
            this.inputNameTarget.setAttribute('disabled', 'disabled')
        } else {
            this.inputNameTarget.removeAttribute('disabled')
        }

        if (count === 0) {
            this.dropAreaInfo.textContent = this.defaultMessage
        }
        if (count === 1) {
            this.dropAreaInfo.textContent = e.currentTarget.files[0]?.name || '1 fichier'
        }
        if (count > 1) {
            this.dropAreaInfo.textContent = `${count} fichier${count > 1 ? 's' : ''}`
        }
    }

    /**
     * Change drop area style when user drag enter area
     */
    handleDragEnter() {
        this.counter++;
        this.dropArea.classList.add('active');
    }

    /**
     * Reset drop area style when user drag out area
     */
    handleDragLeave () {
        this.counter--;
        if (this.counter === 0) {
            this.dropArea.classList.remove('active')
        }
    }

    /**
     * Required to enabling drop event
     * @param {DragEvent} e
     */
    handleDragOver (e) {
        e.preventDefault();
    }

    handleDrop(e) {
        e.preventDefault();

        this.counter = 0;
        this.dropArea.classList.remove('active');

        const files = Array.from(e.dataTransfer.files);

        const dataTransfer = new DataTransfer();
        files.forEach(file => {
            dataTransfer.items.add(file);
        });

        this.inputFile.files = dataTransfer.files;

        const event = new Event('change', { bubbles: true });
        this.inputFile.dispatchEvent(event);
    }
}
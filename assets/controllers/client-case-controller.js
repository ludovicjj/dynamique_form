import { Controller } from '@hotwired/stimulus';
import { getComponent } from '@symfony/ux-live-component';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["partner", "partnerContact", 'partnerContactAlert']

    /**
     * @type {string|null}
     */
    message = '';

    async initialize() {
        this.component = await getComponent(this.element);

        // do something after the component re-renders
        this.component.on('render:finished', () => {
            if (this.message) {
                this.showAlert(this.message)
            }
        });
    }

    onChangePartner(e) {
        // Fetch selected Partner
        const selectedPartnerId = e.currentTarget.value || null

        // Check if Placeholder is selected
        if (!selectedPartnerId) {
            this.message = "Vous devez selectionner un partenaire."
            this.showAlert(this.message)
            return
        }

        // Update Partner Contact DOM for given Partner
        let count = 0
        this.partnerContactTarget.querySelectorAll([`input[data-id]`]).forEach(input => {
            const dataId = input.getAttribute('data-id');
            const formCheck = input.closest('.form-check');

            if (dataId === selectedPartnerId) {
                count++
                formCheck.classList.remove('d-none');
            } else {
                formCheck.classList.add('d-none');
            }
        })

        // Check if given Partner has PartnerContacts
        if (count === 0) {
            this.message = "Aucun contact associé à ce partenaire n'est disponible.";
            this.showAlert(this.message)
            return
        }

        this.showPartnerContacts()
    }

    /**
     * Display alert with given message
     * Hide PartnerContact Field
     *
     * @param {string} message
     */
    showAlert(message) {
        this.partnerContactAlertTarget.classList.remove('d-none')
        this.partnerContactAlertTarget.innerHTML = message

        this.partnerContactTarget.classList.add('d-none')
    }

    /**
     * Display PartnerContact Field
     * Hide Alert and reset message
     */
    showPartnerContacts() {
        this.message = null
        this.partnerContactAlertTarget.classList.add('d-none')
        this.partnerContactTarget.classList.remove('d-none')
    }
}
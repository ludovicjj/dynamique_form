import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["partner", "partnerContact", 'alert']
    static values = {
        message: {type: String, default: "Vous devez selectionner un partenaire YOLO." }
    }

    count = 0

    connect() {
        // if (this.hasPartnerTarget) {
        //     this.partnerTarget.addEventListener('change', (e) => {
        //         // Fetch selected Partner
        //         const selectedPartnerId = e.currentTarget.value || null
        //
        //         // User select placeholder
        //         if (!selectedPartnerId) {
        //             this.showAlert("Vous devez selectionner un partenaire.")
        //             return
        //         }
        //
        //         // Update Partner Contact DOM for given Partner
        //         this.partnerContactTarget.querySelectorAll([`input[data-id]`]).forEach(input => {
        //             const dataId = input.getAttribute('data-id');
        //             const formCheck = input.closest('.form-check');
        //
        //             if (dataId === selectedPartnerId) {
        //                 this.count++
        //                 formCheck.classList.remove('d-none');
        //             } else {
        //                 formCheck.classList.add('d-none');
        //             }
        //         })
        //
        //
        //         // Check if given Partner has PartnerContact
        //         if (this.count === 0) {
        //             this.showAlert("Aucun contact associé à ce partenaire n'est disponible.")
        //             return
        //         }
        //
        //         this.hideAlert()
        //     })
        // }
    }

    onChange(e) {
        // Fetch selected Partner
        const selectedPartnerId = e.currentTarget.value || null

        this.showAlert(this.messageValue)


        console.log(this.count)
    }

    /**
     * Display alert with given message and Hide PartnerContact Field
     *
     * @param {string} message
     */
    showAlert(message) {
        this.alertTarget.classList.remove('d-none')
        this.alertTarget.innerHTML = message

        this.partnerContactTarget.classList.add('d-none')
    }

    /**
     * Hide Alert and Display PartnerContact Field
     */
    hideAlert() {
        this.alertTarget.classList.add('d-none')
        this.partnerContactTarget.classList.remove('d-none')
    }
}
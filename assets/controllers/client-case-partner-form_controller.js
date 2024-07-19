import { Controller } from '@hotwired/stimulus';
import { getComponent } from '@symfony/ux-live-component';
import { useDebounce } from 'stimulus-use'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["partner", "partnerContact", 'partnerContactAlert', 'search']
    static debounces = [
        'input',
        {
            name: 'onSearchPartner',
            wait: 600
        }
    ];

    /**
     * @type {string|null}
     */
    message = '';

    /**
     * @type {number}
     */
    count = 0;

    /**
     * @typedef {Object} Contact
     * @property {string} label - Le label du contact en minuscules.
     * @property {string} id - L'ID du contact.
     */

    /**
     * @type {Map<Contact, HTMLDivElement>}
     */
    contactMap = new Map()

    connect() {
        // Build map
        this.partnerContactTarget.querySelectorAll([`input[data-id]`]).forEach(input => {
            const formCheck = input.closest('.form-check');
            const label = formCheck.querySelector('label')
            const contact = {
                label: label.textContent.toLowerCase(),
                id: input.getAttribute('data-id')
            }

            this.contactMap.set(contact, formCheck)
        })
        useDebounce(this)
    }

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
        const selectedPartner = e.currentTarget.value || null
        const searchValue = this.searchTarget.value || null

        // Check if Placeholder is selected
        if (!selectedPartner && !searchValue) {
            this.message = "Vous devez selectionner un partenaire."
            this.showAlert(this.message)
            return
        }

        this.filter()
    }

    async onSearchPartner() {
        const searchValue = this.searchTarget.value
        const selectedPartner = this.partnerTarget.value || null

        if (!searchValue && !selectedPartner) {
            this.message = "Aucun contact trouvé pour cette recherche.";
            this.showAlert(this.message)
            return
        }

        this.filter()
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


    filter() {
        const selectedPartner = this.partnerTarget.value || null
        const searchValue = this.searchTarget.value || null


        if (!searchValue && selectedPartner) {
            this.filterByPartner(selectedPartner)
        }

        if (searchValue && !selectedPartner) {
            this.filterBySearchValue(searchValue)
        }

        if (searchValue && selectedPartner) {
            this.filterByPartnerAndSearchValue(selectedPartner, searchValue)
        }

        if (this.count === 0) {
            if (searchValue) {
                this.message = "Aucun contact trouvé pour cette recherche."
            } else {
                this.message = "Aucun contact associé à ce partenaire n'est disponible."
            }
            this.showAlert(this.message)
        } else {
            this.count = 0
            this.showPartnerContacts()
        }

    }

    /**
     * Search contacts bind to this partner
     * @param {string} selectedPartner
     */
    filterByPartner(
        selectedPartner
    ) {
        for (let [key, formCheck] of this.contactMap.entries()) {
            if (key.id === selectedPartner) {
                this.count++
                formCheck.classList.remove('d-none')
            } else {
                formCheck.classList.add('d-none');
            }
        }
    }

    /**
     * Search contacts bind to this partner
     * @param {string} searchValue
     */
    filterBySearchValue(
        searchValue
    ) {
        for (let [key, formCheck] of this.contactMap.entries()) {
            if (key.label.includes(searchValue)) {
                this.count++
                formCheck.classList.remove('d-none')
            } else {
                formCheck.classList.add('d-none');
            }
        }
    }

    /**
     * Search contact include search value AND bind to this partner
     * @param {string} selectedPartner
     * @param {string} searchValue
     */
    filterByPartnerAndSearchValue(
        selectedPartner,
        searchValue
    ) {
        for (let [key, formCheck] of this.contactMap.entries()) {
            if (key.label.includes(searchValue) && key.id === selectedPartner) {
                this.count++
                formCheck.classList.remove('d-none')
            } else {
                formCheck.classList.add('d-none');
            }
        }
    }
}
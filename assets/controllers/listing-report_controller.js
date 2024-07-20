import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static outlets = [ "dialog" ]

    connect() {

    }

    async onSubmit(e) {
        if(e.detail.success === false) {
            const response = await e.detail.fetchResponse.response.json()
            if (this.hasDialogOutlet) {
                this.dialogOutlet.defineConfirmUrl(response.url)
                this.dialogOutlet.open()
            }
        }

        if (e.detail.success === true) {
            const response = await e.detail.fetchResponse.response.json()
            location.href = response.url
        }
    }
}
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static outlets = [ "dialog" ]

    async onSubmit(e) {
        if(e.detail.success === false) {
            console.log(e.detail)
            const response = await e.detail.fetchResponse.response.json()

            if (this.hasDialogOutlet) {
                this.dialogOutlet.reportConfirmTarget.action = response.url
                this.dialogOutlet.open()
            }
        }


        if (e.detail.success === true) {
            if (this.hasDialogOutlet && this.dialogOutlet.dialogTarget.open) {
                this.dialogOutlet.close()
            }
        }
    }
}
import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ["input"]
    datepicker

    connect() {
        import('duck').then(module => {
            const duck = new module.Duck()
            duck.sayHello()
        })


        ClassicEditor
            .create( this.inputTarget , {
                toolbar: [
                    'bold', 'italic', 'underline',
                    '|' ,
                    'bulletedList', 'numberedList',
                    '|',
                    'uploadImage',
                    '|',
                    'undo', 'redo',
                    '|',
                    'blockQuote'
                ],
                simpleUpload: {
                    uploadUrl: "/debug/download",
                }
            } )
            .then(editor => {
            })
            .catch( /* ... */ );

    }

    disconnect() {

    }
}
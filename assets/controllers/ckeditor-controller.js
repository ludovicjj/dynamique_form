import { Controller } from '@hotwired/stimulus';
import { easepick } from '@easepick/core';


export default class extends Controller {
    static targets = ["input"]
    datepicker

    connect() {

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
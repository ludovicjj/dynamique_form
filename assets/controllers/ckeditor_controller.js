import { Controller } from '@hotwired/stimulus';
import '../styles/ckeditor.css';

export default class extends Controller {
    static targets = ["input"]

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
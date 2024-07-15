import { Controller } from '@hotwired/stimulus';
import { easepick } from '@easepick/core';
import { LockPlugin } from '@easepick/lock-plugin';

export default class extends Controller {
    static targets = ["input", "reset", "calendar"]
    datepicker

    connect() {
        // if (!this.hasInputTarget) {
        //     return
        // }
        //
        // // Init datepicker config
        // const pickerConfig = {
        //     element: this.inputTarget,
        //     css: [
        //         'https://cdn.jsdelivr.net/npm/@easepick/core@1.2.1/dist/index.css'
        //     ],
        //     lang: 'fr-FR',
        //     zIndex: 10,
        //     format: "DD-MM-YYYY",
        //     readonly: false,
        //     setup: (picker) => {
        //         // Dispatch change event when user select date from datepicker
        //         picker.on('select', () => {
        //             const event = new Event('change', { bubbles: true });
        //             this.inputTarget.dispatchEvent(event);
        //         });
        //         // Dispatch change event when user clear date from datepicker
        //         picker.on('clear', () => {
        //             const event = new Event('change', { bubbles: true });
        //             this.inputTarget.dispatchEvent(event);
        //         });
        //     }
        // }
        //
        // // Override datepicker config (LockPlugin)
        // if (this.inputTarget.classList.contains('max-today')) {
        //     pickerConfig.css.push('https://cdn.jsdelivr.net/npm/@easepick/lock-plugin@1.2.1/dist/index.css')
        //     pickerConfig.plugins = [LockPlugin]
        //     pickerConfig.LockPlugin  = {
        //         filter(date) {
        //             return date.getTime() > new Date().getTime();
        //         }
        //     }
        // }
        //
        // this.datepicker = new easepick.create(pickerConfig);
        //
        // // Reset datepicker
        // if (this.hasResetTarget) {
        //     this.resetTarget.addEventListener('click', () => {
        //         this.datepicker.clear()
        //     })
        // }
        //
        // // Show datepicker
        // if (this.hasCalendarTarget) {
        //     this.calendarTarget.addEventListener('click', () => {
        //         this.datepicker.show()
        //     })
        // }
    }

    disconnect() {
        if (this.datepicker) {
            this.datepicker.destroy()
            this.datepicker = null
        }
    }
}
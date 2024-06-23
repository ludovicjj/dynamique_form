import { Controller } from '@hotwired/stimulus';
import { easepick } from '@easepick/core';

export default class extends Controller {
    static targets = ["input", "reset", "calendar"]
    datepicker

    connect() {
        this.datepicker = new easepick.create({
            element: this.inputTarget,
            css: [
                'https://cdn.jsdelivr.net/npm/@easepick/core@1.2.1/dist/index.css',
            ],
            lang: 'fr-FR',
            zIndex: 10,
            format: "DD-MM-YYYY",
            readonly: false,
            setup: (picker) => {
                // Dispatch change event when user select date from datepicker
                picker.on('select', () => {
                    const event = new Event('change', { bubbles: true });
                    this.inputTarget.dispatchEvent(event);
                });
                // Dispatch change event when user clear date from datepicker
                picker.on('clear', () => {
                    const event = new Event('change', { bubbles: true });
                    this.inputTarget.dispatchEvent(event);
                });
            }
        });

        if (this.resetTarget) {
            // Reset date
            this.resetTarget.addEventListener('click', () => {
                this.datepicker.clear()
            })
        }

        if (this.calendarTarget) {
            this.calendarTarget.addEventListener('click', () => {
                this.datepicker.show()
            })
        }
    }

    disconnect() {
        if (this.datepicker) {
            this.datepicker.destroy()
            this.datepicker = null
        }
    }
}
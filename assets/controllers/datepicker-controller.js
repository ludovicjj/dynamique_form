import { Controller } from '@hotwired/stimulus';
import { easepick } from '@easepick/core';
import { LockPlugin } from '@easepick/lock-plugin';

export default class extends Controller {
    static targets = ["input", "reset", "calendar"]
    datepicker

    connect() {
        if (!this.hasInputTarget) {
            return
        }

        this.datepicker = new easepick.create({
            element: this.inputTarget,
            css: [
                'https://cdn.jsdelivr.net/npm/@easepick/core@1.2.1/dist/index.css',
            ],
            lang: 'fr-FR',
            zIndex: 10,
            format: "DD-MM-YYYY",
            readonly: false,
            plugins: [LockPlugin],
            LockPlugin: {
                maxDate: new Date(),
            },
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

        if (this.hasResetTarget) {
            // Reset date
            this.resetTarget.addEventListener('click', () => {
                this.datepicker.clear()
            })
        }

        if (this.hasCalendarTarget) {
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
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        console.log('Stimulus controller connected');
        this.refreshArticles = this.refreshArticles.bind(this);
        document.addEventListener("visibilitychange", this.handleVisibilityChange.bind(this));
    }

    disconnect() {
        console.log('Stimulus controller disconnected');
        document.removeEventListener("turbo:load", this.refreshArticles);
    }

    handleVisibilityChange() {
        if (document.visibilityState === 'visible') {
            console.log('Stimulus need refresh articles !!')
            this.refreshArticles();
        }
    }

    refreshArticles() {
        console.log('Refreshing articles');
        fetch('/articles', {
            headers: {
                'Accept': 'text/vnd.turbo-stream.html'
            }
        })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const articlesList = doc.getElementById('articles-list');
                if (articlesList) {
                    document.getElementById('articles-list').innerHTML = articlesList.innerHTML;
                }
            });
    }
}
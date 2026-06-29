class TopLoaderService {
    constructor() {
        this.progress = 0;
        this.timer = null;
        this.element = null;
        this.activeRequests = 0; // Track active ajax requests

        if (document.body) {
            this.init();
        } else {
            document.addEventListener('DOMContentLoaded', () => this.init());
        }
    }

    init() {
        // Prevent duplicate initialization
        if (document.getElementById('top-loader')) return;

        // Create styles
        const style = document.createElement('style');
        style.innerHTML = `
            #top-loader {
                position: fixed;
                top: 0;
                left: 0;
                height: 3.5px;
                width: 0%;
                background: linear-gradient(90deg, #10b981, #06b6d4, #2563eb, #10b981);
                background-size: 200% auto;
                animation: top-loader-gradient 2s linear infinite;
                z-index: 9999999;
                opacity: 0;
                pointer-events: none;
                transition: width 0.3s ease-out, opacity 0.3s ease-in-out;
                box-shadow: 0 0 10px rgba(6, 182, 212, 0.7), 0 0 5px rgba(37, 99, 235, 0.5);
            }
            #top-loader::after {
                content: '';
                position: absolute;
                right: 0;
                width: 100px;
                height: 100%;
                background: linear-gradient(90deg, transparent, #2563eb);
                box-shadow: 0 0 10px #2563eb, 0 0 5px #10b981;
                transform: rotate(3deg) translate(0px, -4px);
                opacity: 1;
            }
            @keyframes top-loader-gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
        `;
        document.head.appendChild(style);

        // Create element
        this.element = document.createElement('div');
        this.element.id = 'top-loader';
        document.body.appendChild(this.element);

        this.setupInterceptors();
    }

    start() {
        // If already loading, just keep running
        if (this.timer) return;

        this.progress = 0;
        this.element.style.width = '0%';
        this.element.style.opacity = '1';

        // Initial bump
        setTimeout(() => {
            if (this.progress === 0) {
                this.progress = 15;
                this.element.style.width = '15%';
            }
        }, 50);

        // Trickle up slowly
        this.timer = setInterval(() => {
            if (this.progress < 85) {
                const diff = (85 - this.progress) * 0.08;
                this.progress += Math.max(0.5, Math.min(diff, 4));
                this.element.style.width = `${this.progress}%`;
            }
        }, 150);
    }

    done() {
        if (!this.timer && this.progress === 0) return;

        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }

        this.progress = 100;
        this.element.style.width = '100%';

        setTimeout(() => {
            this.element.style.opacity = '0';
            setTimeout(() => {
                this.element.style.width = '0%';
                this.progress = 0;
            }, 300);
        }, 200);
    }

    ajaxStart() {
        this.activeRequests++;
        this.start();
    }

    ajaxEnd() {
        this.activeRequests = Math.max(0, this.activeRequests - 1);
        if (this.activeRequests === 0) {
            this.done();
        }
    }

    setupInterceptors() {
        // Intercept Link Clicks
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (!link) return;

            const href = link.getAttribute('href');
            const target = link.getAttribute('target');

            if (
                href &&
                !href.startsWith('#') &&
                !href.startsWith('javascript:') &&
                !href.startsWith('tel:') &&
                !href.startsWith('mailto:') &&
                (!target || target === '_self') &&
                !link.hasAttribute('download') &&
                !e.metaKey && !e.ctrlKey && !e.shiftKey && !e.altKey &&
                e.button === 0
            ) {
                this.start();
            }
        });

        // Intercept Form Submissions
        document.addEventListener('submit', (e) => {
            const form = e.target;
            const target = form.getAttribute('target');
            if (!target || target === '_self') {
                setTimeout(() => {
                    if (!e.defaultPrevented) {
                        this.start();
                    }
                }, 0);
            }
        });

        // Intercept Programmatic Form Submissions
        const originalSubmit = HTMLFormElement.prototype.submit;
        const self = this;
        HTMLFormElement.prototype.submit = function() {
            self.start();
            originalSubmit.apply(this);
        };

        // Window load / beforeunload
        window.addEventListener('load', () => this.done());
        window.addEventListener('beforeunload', () => this.start());
        window.addEventListener('pageshow', (e) => {
            if (e.persisted) {
                this.done();
            }
        });

        // Intercept Fetch requests
        const originalFetch = window.fetch;
        if (originalFetch) {
            window.fetch = async (...args) => {
                this.ajaxStart();
                try {
                    const response = await originalFetch(...args);
                    this.ajaxEnd();
                    return response;
                } catch (error) {
                    this.ajaxEnd();
                    throw error;
                }
            };
        }

        // Intercept Axios (if loaded later or defined globally)
        const setupAxiosInterceptor = () => {
            if (window.axios && !window.axios._loaderInterceptorAdded) {
                window.axios.interceptors.request.use(config => {
                    this.ajaxStart();
                    return config;
                }, error => {
                    this.ajaxEnd();
                    return Promise.reject(error);
                });

                window.axios.interceptors.response.use(response => {
                    this.ajaxEnd();
                    return response;
                }, error => {
                    this.ajaxEnd();
                    return Promise.reject(error);
                });

                window.axios._loaderInterceptorAdded = true;
            }
        };

        // Run immediately and try again when window is fully loaded
        setupAxiosInterceptor();
        window.addEventListener('load', setupAxiosInterceptor);
    }
}

// Instantiate globally
window.topLoader = new TopLoaderService();
export default window.topLoader;

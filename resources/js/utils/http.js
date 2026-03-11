import NProgress from "nprogress";

let activeRequests = 0;

export async function http(url, options = {}) {

    const token = document.querySelector('meta[name="csrf-token"]').content;

    options.headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest',
        ...(options.headers || {})
    };

    options.credentials = 'same-origin';

    // start loader
    if (activeRequests === 0) {
        NProgress.start();
    }

    activeRequests++;

    try {
        const res = await fetch(url, options);
        return res;
    } finally {

        activeRequests--;

        if (activeRequests === 0) {
            NProgress.done();
        }
    }
}
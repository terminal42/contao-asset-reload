import { request as popsicle, plugins } from 'popsicle';

window.addEventListener('load', () => {
    "use strict";

    popsicle('app_dev.php/_wdt/manifest_json').use(plugins.parse('json')).then((response) => {
        if (response.status === 204) {
            return;
        }

        const watch = {};

        Object.entries(response.body).forEach(([key, value]) => {
            let link = document.querySelector('link[href="'+value+'"]');

            if (!link) {
                link = document.querySelector('link[href="'+value.substr(1)+'"]');
            }

            if (link) {
                watch[key] = link;
            }
        });

        if (Object.keys(watch).length === 0) {
            return;
        }

        let request;

        const poll = (response) => {
            request = popsicle({
                url: 'app_dev.php/_wdt/manifest_json' ,
                headers: {
                    'If-Modified-Since': response.headers['last-modified'],
                },
            }).use(plugins.parse('json')).then((response) => {
                Object.keys(watch).forEach((key) => {
                    console.log(key, response.body[key]);
                    watch[key].setAttribute('href', response.body[key]+'?'+Date.now());
                });
                poll(response);
            }).catch(() => {
                poll(response);
            });
        };

        poll(response);

        window.addEventListener('unload', () => {
            if (request) {
                request.abort();
            }
        });
    });
});

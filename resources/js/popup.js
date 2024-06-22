//Show popup when hover on .ip element
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // optional for styling

//Export IpPopup class
!function (root) {
    class IpPopup {
        constructor(options) {
            this.selector = options.selector;
            this.endpoint = options.endpoint || options.api || options.url;
            this.token = options.token || '';
            this.delay = options.delay || 500;
            this.fetchCache = {};
            this.flagUrl = options.flagUrl || 'https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/flags/4x3/';
            this.setEvents();
        }

        async fetchIPInfo(ip) {
            if (!this.fetchCache[ip]) {
                const response = await fetch(`${this.endpoint}/${ip}?token=${this.token}`);
                this.fetchCache[ip] = await response.json();
            }
            return this.fetchCache[ip];
        }

        setEvents() {
            let self = this;
            let tooltipInstance = null;

            document.body.addEventListener('mouseover', async (event) => {
                const target = event.target;
                if (target.matches(self.selector)) {
                    tooltipInstance = target._tippy;
                    if (!tooltipInstance) {
                        let ipAddress = target.textContent;
                        ipAddress=ipAddress.trim();
                        if(ipAddress.includes(':')){
                            if(ipAddress.includes('.')){
                                //IPv4
                                ipAddress=ipAddress.split(':')[0];
                            }
                        }


                        const ipInfos = await self.fetchIPInfo(ipAddress);
                        //ipinfo is object with provider => {ipinfo object}
                        //We loop through the object to get the first key
                        let content;
                        let ipInfo = ipInfos['all'];
                        if(ipInfo) {
                            let as = ipInfo.as || '';
                            let country = ipInfo.country || '';
                            let countryCode = (ipInfo.countryCode || '').toLocaleLowerCase();
                            let flag = `${self.flagUrl}${countryCode}.svg`;
                            if (!as && !country && !countryCode) return false;
                            content = `<div class="ip-popup">${as} <img class="country-flag" style="height: 1em" src="${flag}" alt="${country}" title="${country}"></div>`;
                        }else{
                            content = `<div class="ip-popup">No data found</div>`;
                        }
                        //Break the loop
                        tooltipInstance = tippy(target, {
                            content: content,
                            allowHTML: true,
                            placement: 'auto',
                            arrow: true,
                            interactive: true,
                            duration: [500, 500],
                        });
                    }
                    tooltipInstance.show();
                }
            });

        }
    }

    root.IpPopup = IpPopup;
}(window)

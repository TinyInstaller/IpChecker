//Show popup when hover on .ip element
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // optional for styling

//Export IpPopup class
!function(root){
    class IpPopup {
        constructor(options) {
            this.selector = options.selector;
            this.endpoint = options.endpoint || options.api || options.url;
            this.token = options.token || '';
            this.delay = options.delay || 500;
            this.fetchCache = {};
            this.setEvents();
        }
        async fetchIPInfo(ip) {
            if (!this.fetchCache[ip]) {
                const response = await fetch(`${this.endpoint}/${ip}?token=${this.token}`);
                this.fetchCache[ip] = await response.json();
            }
            return this.fetchCache[ip];
        }
        setEvents(){
            let self=this;
            let tooltipInstance = null;

            document.body.addEventListener('mouseover', async (event) => {
                const target = event.target;
                if (target.matches(self.selector)) {
                    const ipAddress = target.textContent;
                    self.currentIp=ipAddress;
                    const ipInfos = await self.fetchIPInfo(ipAddress);
                    //ipinfo is object with provider => {ipinfo object}
                    //We loop through the object to get the first key
                    let content;
                    Object.keys(ipInfos).forEach((key) => {
                        let ipInfo = ipInfos[key];
                         content = `As: ${ipInfo.as}<br>Country: ${ipInfo.country}`;
                        //Break the loop
                        return content;
                    });


                    tooltipInstance = tippy(target, {
                        content: content,
                        allowHTML: true,
                        placement: 'top',
                        arrow: true,
                        interactive: true,
                        duration: [200, 200],
                    });
                    tooltipInstance.show();
                }
            });

        }
    }
    root.IpPopup = IpPopup;
}(window)

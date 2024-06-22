//Show popup when hover on .ip element
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // optional for styling

//Export IpPopup class
!function(root){
    class IpPopup {
        constructor(options) {
            this.selector = options.selector;
            this.endpoint = options.endpoint || options.api || options.url;
            this.token = options.token;
            this.setEvents();
        }
        async fetchIPInfo(ip) {
            const response = await fetch(`${this.endpoint}/${ip}?token=${this.token}`);
            const data = await response.json();
            return data;
        }
        setEvents(){
            let self=this;
            document.addEventListener('DOMContentLoaded', () => {
                const ips = document.querySelectorAll(self.selector);

                ips.forEach(ip => {
                    ip.addEventListener('mouseenter', async (event) => {
                        const ipAddress = event.target.textContent;
                        const ipInfo = await self.fetchIPInfo(ipAddress);
                        const content = `City: ${ipInfo.city}, Country: ${ipInfo.country}`;

                        tippy(event.target, {
                            content: content,
                            placement: 'top',
                            arrow: true,
                            duration: [200, 200],
                        }).show();
                    });
                });
            });
        }
    }
    root.IpPopup = IpPopup;
}(window)

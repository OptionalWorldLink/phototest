export default class Modal {
    /**
     * @param {HTMLElement|null} element
     */
    constructor(element) {
        if (element === null) {
            return
        }
        this.container = element.querySelector('.js-modal-container')
        this.loader = element.querySelector('.js-modal-loader')
        this.header = element.querySelector('.js-modal-header')
        this.title = element.querySelector('.js-modal-title')
        this.bindTrigger()
    }

    bindTrigger () {
        const self = this
        document.querySelectorAll('.js-modal-trigger').forEach(function (trigger) {
            trigger.addEventListener('click', e => {
                e.preventDefault();
                self.loader.style.display = 'block'
                self.container.innerHTML = ''

                const style = trigger.hasAttribute('data-style')
                    ? 'bg-' + trigger.getAttribute('data-style')
                    : 'bg-primary';

                const title = trigger.hasAttribute('data-title')
                    ? trigger.getAttribute('data-title')
                    : 'Avertissement';

                const route = trigger.hasAttribute('data-route')
                    ? trigger.getAttribute('data-route')
                    : null;

                self.setTemplate(style, title)
                self.getTemplate(route)
            })
        })
    }

    cleanTemplate()
    {
        this.header.className = 'modal-header'
    }

    setTemplate (style, title) {
        this.cleanTemplate();
        this.header.classList.add(style)
        this.title.innerHTML = title
    }

    getTemplate (route) {
        if (route !== null) {
            axios
                .get(route)
                .then((response)  =>  {
                    this.loader.style.display = 'none'
                    this.container.innerHTML = response.data
                }, (error)  =>  {
                    console.error(error)
                })
        }
    }
}

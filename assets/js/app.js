import Modal from '../components/Modal'

const axios = require('axios').default;
axios.defaults.headers['X-Requested-With'] = 'XMLHttpRequest';
window.axios = axios;

let modal = document.querySelector('#modal');
if (modal !== null) { new Modal(modal) }

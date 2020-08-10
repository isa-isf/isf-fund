import axios from 'axios'
import { Notyf } from 'notyf'
import 'alpinejs'

window.notyf = new Notyf()
window.axios = axios

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
    console.error('CSRF token not found')
}

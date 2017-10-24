import axios from 'axios'
import { apiUrl } from 'config/base'

export const http = axios.create({
    baseURL: apiUrl
})

http.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
}

http.interceptors.response.use(function (response) {
    return response
}, function (error) {
    const { response } = error
    if ([401].indexOf(response.status) >= 0) {
        if (response.status == 401 && response.data.error.message != 'Unauthorized') {
            return Promise.reject(response)
        }
        window.location = '/login'
    }
    return Promise.reject(error)
})

export default function install(Vue) {
    Object.defineProperty(Vue.prototype, '$axios', {
        get() {
            return http
        }
    })
}
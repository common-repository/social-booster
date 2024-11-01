import axios from 'axios'
const rsf_obj = window.rx_sb_obj;
export default() => {
    return axios.create({
        baseURL: rsf_obj.api_url,
        withCredentials: true,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-WP-Nonce': window.rx_sb_obj.api_nonce,
        }
    })
}

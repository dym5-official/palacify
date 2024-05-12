import axios from "redaxios";

const query = (params) => {
    params._wpnonce = palacify._wpnonce;

    return Object.keys(params).map((key) => {
        return encodeURIComponent(key) + '=' + encodeURIComponent(params[key]);
    }).join('&');
}

const url = (endpoint) => {
    let params = query(endpoint[2] || {});
    let apiurl = `${palacify.ajaxurl}?action=palacify&__scope__=${endpoint[0]}&__key__=${endpoint[1]}&${params}`;

    return apiurl;
}

const get = (endpoint) => {
    return axios.get(url(endpoint));
}

const post = (endpoint, data) => {
    return axios.post(url(endpoint), data);
}

const api = {
    get,
    post,
};

export default api;
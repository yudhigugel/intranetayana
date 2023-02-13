import axios from "axios";
import Swal from "sweetalert2";

const headers = {
    'Content-Type': 'application/json',
    'Authorization': 'Basic Yml6bmV0OmJpem5ldA==',
    'api-token': window.api_token
};

const config = {
    headers: headers
}

var rest = {
    /**
     * post data
     */
    post: (url, data) => {
        var request = axios.post(url, data, config);
        request.then((res) => {
            if (res.data.code == 401) {
                Swal.fire('Error', res.data.message, 'error');
            }
        });
        request.catch((err) => {
            if (err.response.status == 404) {
                Swal.fire('Error', "Request not Found", 'error');
            }else if(err.response.status == 500){
                Swal.fire('Error', "Internal Server Error", 'error');
            }
        });
        return request;
    },

    /**
     * post data
     */
    put: (url, data) => {
        var request = axios.put(url, data, config);
        request.then((res) => {
            if (res.data.code == 401) {
                Swal.fire('Error', res.data.message, 'error');
            }
        });
        request.catch((err) => {
            if (err.response.status == 404) {
                Swal.fire('Error', "Request not Found", 'error');
            }else if(err.response.status == 500){
                Swal.fire('Error', "Internal Server Error", 'error');
            }
        });
        return request;
    },

    /**
     * login to api
     */
    get: (url, data) => {
        var request = axios.get(url, config);
        request.then((res) => {
            if (res.data.code == 401) {
                Swal.fire('Error', res.data.message, 'error');
            }else if(res.data.code == -5001){
                Swal.fire('Error', res.data.message, 'error');
            }
               
        });
        request.catch((err) => {
            if (err.response.status == 404) {
                Swal.fire('Error', "Request not Found", 'error');
            }else if(err.response.status == 500){
                Swal.fire('Error', "Internal Server Error", 'error');
            }
        });
        return request;
    },
     /**
     * post data
     */
    delete: (url) => {
        var request = axios.delete(url,config);
        request.then((res) => {
            if (res.data.code == 401) {
                Swal.fire('Error', res.data.message, 'error');
            }
        });
        request.catch((err) => {
            if (err.response.status == 404) {
                Swal.fire('Error', "Request not Found", 'error');
            }else if(err.response.status == 500){
                Swal.fire('Error', "Internal Server Error", 'error');
            }
        });
        return request;
    },
};
export { headers, rest };
export default rest;

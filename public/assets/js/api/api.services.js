import { storageSetup, baseUrl } from '../config.js'


async function request(method, endpoint, data = null) {
    console.info('@api request', method, endpoint, data)
    const url = `${baseUrl}${endpoint}`;
    const headers = {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };

    const token = localStorage.getItem(storageSetup.localStorageKey);
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const options = {
        method,
        headers,
        body: data ? JSON.stringify(data) : null,
    };

    const response = await fetch(url, options);
    const rawResponse = await response.text(); // Lire la réponse comme texte brut

    try {
        const jsonResponse = JSON.parse(rawResponse); // Essayer de parser en JSON
        if (!response.ok) {
            throw new Error(jsonResponse.error || 'Something went wrong');
        }
        return jsonResponse;
    } catch (e) {
        console.error('Invalid JSON response:', rawResponse);
        throw new Error(`Invalid server response: ${rawResponse.substring(0, 100)}`);
    }
}


class ApiService {
    tokenKey = storageSetup.localStorageKey;

    constructor() {}

    async get(endpoint) {
        return request('GET', endpoint);
    }

    async patch(endpoint, data) {
        return request('PATCH', endpoint, data);
    }

    async post(endpoint, data) {
        return request('POST', endpoint, data);
    }

    async put(endpoint, data) {
        return request('PUT', endpoint, data);
    }

    async delete(endpoint) {
        return request('DELETE', endpoint);
    }
}

export const apiService = new ApiService();
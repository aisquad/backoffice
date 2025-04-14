import { storageSetup } from '/assets/js/config.js'

export class StorageManager {
    constructor() {
        this.config = storageSetup
    }

    // localStorage - per a dades no sensibles
    get localData() {
        return JSON.parse(localStorage.getItem(this.config.localStorageKey) || '{}')
    }

    getLocalData(key) {
        const data = this.localData
        return data[key];
    }

    setLocalData(key, value) {
        const data = this.localData
        data[key] = value
        localStorage.setItem(this.config.localStorageKey, JSON.stringify(data))
    }

    removeLocalData(key) {
        let data = this.localData
        delete data[key]
        localStorage.setItem(this.config.localStorageKey, JSON.stringify(data))
    }

    clearAllLocalData() {
        localStorage.removeItem(this.config.localStorageKey)
    }

    hasLocalData(key) {
        const data = this.localData
        return key in data
    }

    wait(millis, back){
        setTimeout(back, millis)
    }

    // Server methods - per a dades sensibles.
    async setServerData(key, value) {
        return this._sendRequest('set', { key, value });
    }

    async getServerData(key) {
        return this._sendRequest('get', { key });
    }

    async removeServerData(key) {
        return this._sendRequest('remove', { key });
    }

    async _sendRequest(action, data) {
        this.root = this.config.apiRoot
        try {
            // console.info(`Sending ${action} request`, data);
            const response = await fetch(this.root, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ action, ...data })
            });
            return await response.json();
        } catch (error) {
            console.error(`Error in ${action} request:`, error);
            throw error;
        }
    }
}
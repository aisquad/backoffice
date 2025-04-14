import { apiService } from './api.services.js';
import { storageSetup } from '../config.js'

class AuthService {
    tokenKey = storageSetup.localStorageKey;

    /**
     * Logs in the user and stores the JWT token.
     * @param {string} username - The username.
     * @param {string} password - The password.
     * @returns {Promise<Object>} The response from the server.
     * @throws {Error} If the login fails.
     */
    async login(data) {
        try {
            const response = await apiService.post('/auth/login', data)
            if (response.token) {
                const appData = {loggedInAt: Number((Date.now() / 1000).toFixed(0))}
                localStorage.setItem(this.tokenKey, JSON.stringify(appData))
                return response;
            }
            throw new Error('No token received')
        } catch (error) {
            throw new Error('Login failed: ' + error.message);
        }
    }

    get token() { return localStorage.getItem(this.tokenKey) } 

    /**
     * Logs out the user by removing the JWT token.
     */
    logout() {
        localStorage.removeItem(this.tokenKey); // Remove the JWT token
    }

    /**
     * Checks if the user is authenticated.
     * @param {boolean} fast - If true, uses client-side validation. If false, uses server-side validation.
     * @returns {Promise<boolean>} True if the user is authenticated, false otherwise.
     */
    async isAuthenticated(fast = true) {
        if (fast) {
            return this.isAuthenticatedByClient();
        } else {
            return await this.isAuthenticatedByServer();
        }
    }

    /**
     * Validates the JWT token on the server.
     * @returns {Promise<boolean>} True if the token is valid, false otherwise.
     */
    async isAuthenticatedByServer() {
        if (!this.token) return false;

        try {
            const response = await apiService.get('/auth/validate');
            return response.valid; // true or false
        } catch (error) {
            console.error('Error validating token:', error);
            return false;
        }
    }

    /**
     * Validates the JWT token on the client.
     * @returns {boolean} True if the token is valid, false otherwise.
     */
    isAuthenticatedByClient() {
        if (!this.token) return false;

        try {
            // Split the token into its parts
            const [header, payload, signature] = token.split('.');
            if (!header || !payload || !signature) {
                return false; // Invalid token structure
            }

            // Decode the payload (base64)
            const decodedPayload = JSON.parse(atob(payload));

            // Check if the token is expired
            const currentTime = Math.floor(Date.now() / 1000); // Current time in seconds
            if (decodedPayload.exp && decodedPayload.exp < currentTime) {
                return false; // Token is expired
            }

            return true; // Token is valid
        } catch (error) {
            console.error('Error validating token:', error);
            return false; // Token is invalid
        }
    }
}

export const authService = new AuthService();
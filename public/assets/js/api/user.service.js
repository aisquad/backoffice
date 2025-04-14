import { apiService } from './api.services.js';

export class UserService {
    async getUsers() {
        try {
            return await apiService.get('/users');
        } catch (error) {
            throw new Error('Failed to fetch users:', error.message);
        }
    }

    async getUserById(id) {
        try {
            return await apiService.get(`/users/${id}`);
        } catch (error) {
            throw new Error('Failed to fetch user:', error.message);
        }
    }

    async getUsernamesLike(data) {
        try {
            return await apiService.post(`/users/suggestions}`, data)
        } catch (error) {
            throw new Error('Failed to fetch usernames:', error.message);
        }
    }

    async login(username, password) {
        try {
            data = {username: username, password: password}
            return await apiService.post('login', data) 
        } catch (error) {
            throw new Error('Failed to login user')
        }
    }
}

// Export an instance of UserService
export const userService = new UserService();
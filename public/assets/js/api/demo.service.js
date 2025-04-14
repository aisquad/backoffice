import { apiService } from './api.services.js'

class DemoService {
    async getAllData() {
        try {
            return await apiService.get('/miscellaneous')
        } catch (error) {
            throw new Error('Failed to fetch miscellaneous:', error.message);
        }
    }
}

export const demoService = new DemoService()

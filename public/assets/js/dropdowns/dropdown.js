class Dropdown {
    list = []
    constructor() {
    }
    add(item) {
        if(!this.list.includes(item))
            this.list.push(item)
    }

    addAll(usernames) {
        usernames.forEach(item => {
            this.add(item)
        });
    }

    includes(username) {
        return this.list.includes(username)
    }
}

export class UsernameDropdown extends Dropdown {
    name = ''
    email = ''
    username = ''

    constructor(name='', email='', username='') {
        super()
        this.name = name
        this.email = email
        this.username = username
    }
}
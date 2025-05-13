// bossScriptorium-dropdown.js
function dropdown() {
    return {
        open: false,
        search: '',
        selected: null,
        options: [],
        initOptions(data, oldBossId) {
            this.options = data;
            // Initialize with the old value or the existing value
            if (oldBossId) {
                this.selected = this.options.find(user => user.id == oldBossId) || null;
            }
        },
        select(user) {
            this.selected = user;
            this.open = false;
            this.search = '';
        },
        get filteredOptions() {
            if (!this.search) return this.options;
            return this.options.filter(user =>
                user.full_name.toLowerCase().includes(this.search.toLowerCase()) ||
                (user.department && user.department.department_name.toLowerCase().includes(this.search.toLowerCase())) ||
                (user.position && user.position.toLowerCase().includes(this.search.toLowerCase()))
            );
        }
    }
}


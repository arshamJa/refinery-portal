function dropdown() {
    return {
        open: false,
        search: '',
        selected: null,
        options: [],
        initOptions(data, bossInfo) {
            this.options = data;
            if (bossInfo) {
                this.selected = {
                    id: bossInfo.id,
                    full_name: bossInfo.full_name,
                    department: bossInfo.department ? {department_name: bossInfo.department.department_name} : null,
                    position: bossInfo.position
                };
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

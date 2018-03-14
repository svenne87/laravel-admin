export default {  
    state: {  
        permissions: [],
    },  
    getters: {
        hasPermission: (state, getters) => (value) => {
            return state.permissions.find(permission => permission === value);
        }
    },
    mutations: {
        setPermissions: (state, permissions) => {
          state.permissions = permissions;
        }
    },    
    strict: true,
 };
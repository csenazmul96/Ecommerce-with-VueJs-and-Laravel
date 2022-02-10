import * as common from './commonHelper'
const currentUser = common.getLocalUser();
const token = currentUser ? currentUser.token : null;

export function initialize(router){
    //check user login info
    router.beforeEach((to, form, next) => {
        const requiresAuth = to.matched.some(record => record.meta.requiresAuth);

        if(requiresAuth && !currentUser) {
            // authenticate check
            router.push('/login');
        } else if(to.path == '/login' && currentUser) {
            // redirect if authenticated
            // window.location = '/dashboard';
            router.push('/dashboard');
        } else if(to.path == '/register' && currentUser) {
            // redirect if authenticated
            // window.location = '/dashboard';
            router.push('/dashboard');
        } else {
            next();
        }
    })

    // if jwt response problem
    axios.interceptors.response.use(null, (error) => {
        if(error.response.status == 401){
            common.localClear();
            // if(to.path != '/login' || to.path != '/') {
            //     router.push('/login');
            // }
        }
        return Promise.reject(error);
    })

    axios.interceptors.response.use(null, (error) => {
        if(error.response.status == 403){
            router.push('/');
        }
        return Promise.reject(error);
    })

    // set headers common
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

export function check(payload){
    return new Promise((resolve, reject) => {
        axios.post('/api/v1/buyer/me', payload)
            .then((response)=>{
                resolve(response.data);
                if(!response.data)
                    localStorage.removeItem('user')
            })
            .catch((error)=>{
                reject(error);
            })
    })
}

export function register(preload){
    return new Promise((resolve, reject) => {
        const guestId = localStorage.getItem('cart') ? localStorage.getItem('cart') : '';
        preload['guest_id'] = guestId
        axios.post('/api/v1/buyer/register', preload)
            .then((response) => {
                resolve(response.data);
            })
            .catch((error) => {
                reject(error);
            })
    })
}

export function login(credentials){
    return new Promise((resolve, reject) => {
        const guestId = localStorage.getItem('cart') ? localStorage.getItem('cart') : '';
        credentials['guest_id'] = guestId
        axios.post('/api/v1/buyer/login', credentials)
            .then((response) => {
                localStorage.setItem('cart', '');
                resolve(response.data);
            })
            .catch((error) => {
                reject(error);
            })
    })
}

export function socialLogin(credentials){
    return new Promise((resolve, reject) => {
        const guestId = localStorage.getItem('cart') ? localStorage.getItem('cart') : '';
        credentials['guest_id'] = guestId
        axios.post('/api/v1/buyer/social/login', credentials)
            .then((response) => {
                localStorage.setItem('cart', []);
                resolve(response.data);
            })
            .catch((error) => {
                reject(error);
            })
    })
}

export function logout(){
    return new Promise((resolve, reject) => {
        axios.post('/api/v1/buyer/logout')
            .then((response) => {
                resolve(response.data);
            })
            .catch((error) => {
                reject('Something Wrong.');
            })
    })
}

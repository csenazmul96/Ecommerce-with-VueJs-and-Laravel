export function setLoginData(payload){
    var expires_time = new Date();
    expires_time.setSeconds(expires_time.getSeconds() + Number(payload.expires_in));
    var currentUser = Object.assign({}, payload.user, {token: payload.access_token}, {expires_in: payload.expires_in}, {expires_time: expires_time.getTime()});
    localStorage.setItem('user', JSON.stringify(currentUser));
}

export function localClear(){
    localStorage.removeItem('user');
}

export function getLocalUser(){
    const userStr = localStorage.getItem('user');

    if(!userStr){
        return null; 
    }

    return JSON.parse(userStr);
}
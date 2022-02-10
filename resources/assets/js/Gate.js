export default class Gate {
    constructor(user) {
        this.user = user; 
    }

    isBuyer() {
        if(this.user){ 
            return this.user.role ==3;
        }else{
            return false; 
        }
    } 
}
export default class Gate {
    constructor(user) {
        this.user = user;
    }

    auth() {
        return this.user
    }

    // is_admin() {
    //     if(this.user) {

    //     }
    // }

}

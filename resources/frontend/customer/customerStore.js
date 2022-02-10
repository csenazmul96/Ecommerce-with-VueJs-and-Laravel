import * as helper from "./helpers/authHelper";
import * as common from "./helpers/commonHelper";

const user = common.getLocalUser();

export default {
    namespaced: true,
    state: {
        loadingCounter: 0,
        loading: false,
        successMessage: 'Successfull',
        errorMessage: 'Something wrong',
        currentUser: user,
        isLoggedIn: !!user,
        addresses: [],
        orders: null,
        messages: null,
        singleOrder: null,
        addressesPaginate: null,
        defaultBilling: null,
        defaultShipping: null,
        userInformation: null,
        newsletter: null,
        formErrors: {},
        resetPasswordMessage: '',
    },
    mutations: {
        setLoadingState(state, payload = true) {
            state.loading = payload;
            if (payload == true) {
                state.loadingCounter = state.loadingCounter + 1
            } else {
                state.loadingCounter = state.loadingCounter - 1
            }
        },
        setSuccessMessage(state, message) {
            state.successMessage = message;
        },
        setResetPasswordMessage(state, message) {
            state.resetPasswordMessage = message;
        },
        setErrorMessage(state, message) {
            state.errorMessage = message;
        },
        setFormErrors(state, formErrors) {
            state.formErrors = formErrors;
        },
        setIsLoggedIn(state, payload = false) {
            state.isLoggedIn = payload;
        },
        setCurrentUser(state, currentUser) {
            state.currentUser = currentUser;
        },
        setNewsletter(state, newsletter) {
            state.newsletter = newsletter;
        },
        setAddresses(state, addresses) {
            state.addresses = addresses;
        },
        setAddressesPaginate(state, addressesPaginate) {
            state.addressesPaginate = addressesPaginate;
        },
        setDefaultBilling(state, address) {
            state.defaultBilling = address;
        },
        setDefaultShipping(state, address) {
            state.defaultShipping = address;
        },
        setUserInformation(state, userInformation) {
            state.userInformation = userInformation;
        },
        setOrders(state, orders) {
            state.orders = orders;
        },
        setMessages(state, messages) {
            state.messages = messages;
        },
        setSingleOrder(state, order) {
            state.singleOrder = order;
        },
    },
    actions: {
        checkCustomer(context, payload) {
            context.commit('setLoadingState', true);
            helper.check(payload)
                .then((response) => {
                    context.commit('setIsLoggedIn', response.logged);
                })
                .catch((error) => {
                    context.commit('setIsLoggedIn', false);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        tryRegister(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setFormErrors', {});
            helper.register(payload)
                .then((response) => {
                    context.commit('setIsLoggedIn', true);
                    context.commit('setSuccessMessage', 'Successfully logged in.');
                    let currentUser = Object.assign({}, response.user, { token: response.access_token });
                    context.commit('setCurrentUser', currentUser);
                    common.setLoginData(response);
                    window.location = '/dashboard';
                })
                .catch((error) => {
                    context.commit('setIsLoggedIn', false);
                    var formErrors = error.response.data.errors
                    context.commit('setFormErrors', formErrors);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        tryLogin(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setFormErrors', {});
            helper.login(payload)
                .then((response) => {
                    context.commit('setIsLoggedIn', true);
                    context.commit('setSuccessMessage', 'Successfully logged in.');
                    let currentUser = Object.assign({}, response.user, { token: response.access_token });
                    context.commit('setCurrentUser', currentUser);
                    common.setLoginData(response);
                    window.location = '/dashboard';
                })
                .catch((error) => {
                    context.commit('setIsLoggedIn', false);
                    var formErrors = error.response.data.errors
                    context.commit('setFormErrors', formErrors);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        trySocialLogin(context, payload) {
            context.commit('setFormErrors', {});
            helper.socialLogin(payload)
                .then((response) => {
                    context.commit('setIsLoggedIn', true);
                    context.commit('setSuccessMessage', 'Successfully logged in.');
                    let currentUser = Object.assign({}, response.user, { token: response.access_token });
                    context.commit('setCurrentUser', currentUser);
                    common.setLoginData(response);
                    window.location = '/dashboard';
                })
                .catch((error) => {
                    context.commit('setIsLoggedIn', false);
                    var formErrors = error.response.data.errors
                    context.commit('setFormErrors', formErrors);
                })
                .finally(() => {
                });
        },
        tryLogout(context, payload) {
            context.commit('setLoadingState', true);
            helper.logout()
                .then((response) => {
                    context.commit('setCurrentUser', null);
                    context.commit('setIsLoggedIn', false);
                    context.commit('setSuccessMessage', 'Successfully logged out.');
                    common.localClear()
                    window.location = '/';
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        sendResetPassword(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setFormErrors', {});
            axios.post('/api/v1/buyer/reset-password', payload)
                .then((response) => {
                    context.commit('setResetPasswordMessage', 'Reset Password link send to your email. please check.');
                })
                .catch((error) => {
                    context.commit('setIsLoggedIn', false);
                    var formErrors = error.response.data.errors
                    context.commit('setFormErrors', formErrors);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        sendNewPassword(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setFormErrors', {});
            return axios.post('/api/v1/buyer/new-password', payload)
                .then((response) => {
                    context.commit('setResetPasswordMessage', response.data.message);
                    return true;
                })
                .catch((error) => {
                    context.commit('setIsLoggedIn', false);
                    var formErrors = error.response.data.errors
                    context.commit('setFormErrors', formErrors);
                    return false;
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        profile(context) {
            context.commit('setLoadingState', true);
            axios.get('/api/v1/profile')
                .then((response) => {
                    context.commit('setDefaultBilling', response.data.defaultBilling);
                    context.commit('setDefaultShipping', response.data.defaultShipping);
                    context.commit('setUserInformation', response.data.user);
                    context.commit('setNewsletter', response.data.newsletter);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        newsletterUpdate(context) {
            context.commit('setLoadingState', true);
            axios.post('/api/v1/newsletter-update')
                .then((response) => {
                    context.commit('setSuccessMessage', response.data.message);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        updateCustomer(context, payload) {
            context.commit('setLoadingState', true);
            axios.post('/api/v1/buyer/profile-update', payload)
                .then((response) => {
                    context.dispatch('profile');
                    window.location = '/dashboard';
                    context.commit('setSuccessMessage', 'Successfully updated.');
                })
                .catch((error) => {
                    context.commit('setErrorMessage', 'Something wrong.');
                    var formErrors = error.response.data.errors
                    context.commit('setFormErrors', formErrors);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        changePassword(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setFormErrors', {});
            axios.post('/api/v1/buyer/change-password', payload)
                .then((response) => {
                    window.location = '/dashboard';
                    context.commit('setSuccessMessage', 'Successfully updated.');
                })
                .catch((error) => {
                    context.commit('setErrorMessage', 'Something wrong.');
                    var formErrors = error.response.data.errors
                    context.commit('setFormErrors', formErrors);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        addresses(context, page = 1) {
            context.commit('setLoadingState', true);
            let payload = {
                manage: 'customer',
                page: page,
            }
            axios.post('/api/v1/address', payload)
                .then((response) => {
                    context.commit('setAddresses', response.data.addresses.data);
                    context.commit('setAddressesPaginate', response.data.addresses);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        orders(context, page = 1) {
            context.commit('setLoadingState', true);
            let payload = {
                page: page,
            }
            axios.get('/api/v1/buyer/orders?page=' + page, payload)
                .then((response) => {
                    context.commit('setOrders', response.data.orders);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        messages(context, page = 1) {
            context.commit('setLoadingState', true);
            let payload = {
                page: page,
            }
            axios.get('/api/v1/buyer/messages?page=' + page, payload)
                .then((response) => {
                    context.commit('setMessages', response.data.messages);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        setMessageStatus(context, payload) {
            axios.post('/api/v1/buyer/messages/status', payload)
                .then((response) => {
                    context.dispatch('customerStore/messages')
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        async messageReplaySend(context, payload) {
            let success = false;
            await axios.post('/api/v1/buyer/messages/replay/send', payload)
                .then((response) => {
                    context.commit('setSuccessMessage', 'Message Send Successfully.');
                    success = true;
                })
                .catch((error) => {
                    context.commit('setErrorMessage', 'Something wrong.');
                    var formErrors = error.response.data.errors
                    context.commit('setFormErrors', formErrors);
                })
                .finally(() => {
                    // context.commit('setLoadingState', true);
                });

            return success;
        },
        singleOrder(context, orderNumber) {
            context.commit('setLoadingState', true);
            let payload = {
                orderNumber: orderNumber,
            }
            axios.get('/api/v1/buyer/singleOrder?order_number=' + orderNumber, payload)
                .then((response) => {
                    context.commit('setSingleOrder', response.data.order);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        newAddress(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setFormErrors', {});
            axios.post('/api/v1/new-address', payload)
                .then((response) => {
                    context.dispatch('addresses');
                    context.commit('setSuccessMessage', 'New address stored Successfully.');
                })
                .catch((error) => {
                    context.commit('setErrorMessage', 'Something wrong.');
                    var formErrors = error.response.data.errors
                    context.commit('setFormErrors', formErrors);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        updateAddress(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setFormErrors', {});
            axios.post('/api/v1/update-address', payload)
                .then((response) => {
                    context.dispatch('addresses');
                    context.commit('setSuccessMessage', 'Address updated successfully.');
                })
                .catch((error) => {
                    context.commit('setErrorMessage', 'Something wrong.');
                    var formErrors = error.response.data.errors
                    context.commit('setFormErrors', formErrors);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        deleteAddresses(context, payload) {
            context.commit('setLoadingState', true);
            axios.post('/api/v1/remove-address', payload)
                .then((response) => {
                    context.dispatch('addresses');
                    context.commit('setSuccessMessage', 'Address removed.');
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        setDefaultAddress(context, payload) {
            context.commit('setLoadingState', true);
            axios.post('/api/v1/default-address', payload)
                .then((response) => {
                    context.commit('setSuccessMessage', 'Default address updated.');
                    context.dispatch('addresses');
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
    },
    getters: {
        getCustomer(state) {
            return state.currentUser;
        },
        getLoadingCounter(state) {
            return state.loadingCounter;
        },
        getLoading(state) {
            return state.loading;
        },
        getSuccessMessage(state) {
            return state.successMessage;
        },
        getResetPasswordMessage(state) {
            return state.resetPasswordMessage;
        },
        getErrorMessage(state) {
            return state.errorMessage;
        },
        getFormErrors(state) {
            return state.formErrors;
        },
        getIsLoggedIn(state) {
            return state.isLoggedIn;
        },
        getAddresses(state) {
            return state.addresses;
        },
        getAddressesPaginate(state) {
            return state.addressesPaginate;
        },
        getDefaultBilling(state) {
            return state.defaultBilling;
        },
        getDefaultShipping(state) {
            return state.defaultShipping;
        },
        getUserInformation(state) {
            return state.userInformation;
        },
        getOrders(state) {
            return state.orders;
        },
        getMessages(state) {
            return state.messages;
        },
        getSingleOrder(state, order) {
            return state.singleOrder;
        },
        getNewsletter(state) {
            return state.newsletter;
        },
    }
}

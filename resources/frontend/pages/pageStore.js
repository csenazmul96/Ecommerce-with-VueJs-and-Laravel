export default {
    namespaced: true,
    state: {
        loadingCounter: 0,
        loading: false,
        successMessage: 'Successfull',
        errorMessage: 'Something wrong',
        staticPage: null,
        homePage: null,
        categoryFound: null,
        productFound: null,
        welcomeModal: null,
        defaultImage: null,
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
        setErrorMessage(state, message) {
            state.errorMessage = message;
        },
        setStaticPage(state, payload) {
            state.staticPage = payload;
        },
        setHomePage(state, payload) {
            state.homePage = payload;
        },
        setWelcomeModal(state, payload = null) {
            state.welcomeModal = payload;
        },
        setCategoryFound(state, payload = false) {
            state.categoryFound = payload;
        },
        setProductFound(state, payload = false) {
            state.productFound = payload;
        },
        setDefaultImage(state, defaultImage) {
            state.defaultImage = defaultImage;
        },
    },
    actions: {
        staticPage(context, payload) {
            context.commit('setLoadingState', true);
            axios.get('/api/v1/static-page/' + payload)
                .then((response) => {
                    context.commit('setStaticPage', response.data);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        homePage(context, payload) {
            context.commit('setLoadingState', true);
            axios.get('/api/v1/home-page')
                .then((response) => {
                    context.commit('setHomePage', response.data.content);
                    context.commit('setWelcomeModal', response.data.content.welcomeMsg);
                    context.commit('setDefaultImage', response.data.content.defaultImage);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        async products(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setCategoryFound', null);
            let found = false;

            await axios.post('/api/v1/search_items', payload)
                .then((response) => {
                    if (response.data.notfound == 404) {
                        context.commit('setCategoryFound', false);
                    } else {
                        context.commit('setCategoryFound', true);
                        found = true;
                    }
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });

            return found;
        },
        async singleProduct(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setProductFound', null);
            let found = false;

            await axios.get('/api/v1/single-product/' + payload)
                .then((response) => {
                    if (response.data.notfound == 404) {
                        context.commit('setProductFound', false);
                    } else {
                        context.commit('setProductFound', true);
                        found = true;
                    }
                    context.commit('setLoadingState', false);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });

            return found;
        },
    },
    getters: {
        getLoading(state) {
            return state.loading;
        },
        getLoadingCounter(state) {
            return state.loadingCounter;
        },
        getSuccessMessage(state) {
            return state.successMessage;
        },
        getErrorMessage(state) {
            return state.errorMessage;
        },
        getStaticPage(state) {
            return state.staticPage;
        },
        getHomePage(state) {
            return state.homePage;
        },
        getCategoryFound(state) {
            return state.categoryFound;
        },
        getProductFound(state) {
            return state.productFound;
        },
        getWelcomeModal(state) {
            return state.welcomeModal;
        },
        getDefaultImage(state) {
            return state.defaultImage
        },
    }
}

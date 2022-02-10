import * as helper from "../customer/helpers/authHelper";
export default {
    namespaced: true,
    state: {
        loadingCounter: 0,
        loading: false,
        successMessage: 'Successfull',
        errorMessage: 'Something wrong',
        categories: [],
        headerContents: null,
        footerContents: null,
        defaultImage: null,
        socialLinks: null,
        cartItems: [],
        wishlistItems: [],
        searchedItems: [],
        searchedCategories: [],
        states: [],
        countries: [],
        shippingMethods: [],
        promoCode: null,
        pointExists: null,
        dollarDiscount: null,
        searchLoading: false,
        formErrors: {},
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
        setFormErrors(state, formErrors) {
            state.formErrors = formErrors;
        },
        setHeaderContents(state, contents) {
            state.headerContents = contents;
        },
        setFooterContents(state, contents) {
            state.footerContents = contents;
        },
        setCategories(state, categories) {
            state.categories = categories;
        },
        setSocialLinks(state, socialLinks) {
            state.socialLinks = socialLinks;
        },
        setCartItems(state, cartItems) {
            state.cartItems = cartItems;
        },
        setWishlistItems(state, wishlistItems) {
            state.wishlistItems = wishlistItems;
        },
        setDefaultImage(state, defaultImage) {
            state.defaultImage = defaultImage;
        },
        setSearchedItems(state, searchedItems) {
            state.searchedItems = searchedItems;
        },
        setSearchedCategories(state, searchedCategories) {
            state.searchedCategories = searchedCategories;
        },
        setStates(state, states) {
            state.states = states;
        },
        setCountries(state, countries) {
            state.countries = countries;
        },
        setShippingMethods(state, shippingMethods) {
            state.shippingMethods = shippingMethods;
        },
        setPromoCode(state, promoCode) {
            state.promoCode = promoCode;
        },
        setPointExists(state, pointExists) {
            state.pointExists = pointExists;
        },
        setDollarDiscount(state, dollarDiscount) {
            state.dollarDiscount = dollarDiscount;
        },
        setSearchLoadingState(state, payload = true) {
            state.searchLoading = payload;
        },
    },
    actions: {
        headerContents(context) {
            context.commit('setLoadingState', true);
            axios.get('/api/v1/headerdefaultcontent')
                .then((response) => {
                    context.commit('setHeaderContents', response.data.content);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        footerContents(context) {
            context.commit('setLoadingState', true);
            axios.get('/api/v1/default-footer')
                .then((response) => {
                    context.commit('setFooterContents', response.data.content);
                    context.commit('setSocialLinks', response.data.socialLinks);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        defaultCategories(context) {
            context.commit('setLoadingState', true);
            axios.get('/api/v1/categories')
                .then((response) => {
                    context.commit('setCategories', response.data.categories);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        addNewsletter(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setSuccessMessage', '');
            axios.post('/api/v1/add-newsletter', payload)
                .then((response) => {
                    context.commit('setSuccessMessage', response.data.message);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        searchInSite(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setSearchLoadingState', true);
            axios.post('/api/v1/search_in_site', payload)
                .then((response) => {
                    context.commit('setSearchedItems', response.data.items);
                    context.commit('setSearchedCategories', response.data.categories);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                    context.commit('setSearchLoadingState', false);
                });
        },
        async cartItems(context, payload) {
            const guestId = localStorage.getItem('cart') ? localStorage.getItem('cart') : '';
            return await axios.get('/api/v1/cart-items?guest='+guestId)
                .then((response) => {
                    let resolveCarts = [];
                    response.data.cartItems.forEach(cartItem => {
                        let indexFound = resolveCarts.findIndex(resolveCart => (resolveCart.item_id == cartItem.item_id && resolveCart.color_id == cartItem.color_id && resolveCart.size_id == cartItem.size_id));
                        if (indexFound >= 0) {
                            resolveCarts[indexFound].quantity = Number(resolveCarts[indexFound].quantity) + Number(cartItem.quantity)
                        } else {
                            resolveCarts.push(cartItem);
                        }
                        context.commit('setPromoCode', response.data.promoCode);
                        context.commit('setPointExists', response.data.pointExists);
                        context.commit('setDollarDiscount', response.data.dollarDiscount);
                    });
                    // resolveCarts = response.data.cartItems;
                    context.commit('setCartItems', resolveCarts);
                    context.commit('setDefaultImage', response.data.default_img);
                })
                .finally(() => {
                });
        },
        states(context) {
            context.commit('setLoadingState', true);
            axios.get('/api/v1/state')
                .then((response) => {
                    context.commit('setStates', response.data.states);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        countries(context) {
            context.commit('setLoadingState', true);
            axios.get('/api/v1/country')
                .then((response) => {
                    context.commit('setCountries', response.data.countries);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        shippingMethods(context) {
            context.commit('setLoadingState', true);
            axios.get('/api/v1/shipping-methods')
                .then((response) => {
                    context.commit('setShippingMethods', response.data.shippingMethods);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        deleteFromCart(context, payload) {
            const guestId = localStorage.getItem('cart') ? localStorage.getItem('cart') : '';
            payload['guest_id'] = guestId
            axios.post('/api/v1/delete-cart', payload)
                .then((response) => {
                    context.dispatch('cartItems')
                })
                .finally(() => {
                });
        },
        async wishlistItems(context) {
            await axios.post('/api/v1/wishlist')
                .then((response) => {
                    context.commit('setWishlistItems', response.data.items);
                    context.commit('setDefaultImage', response.data.default_img);
                })
                .finally(() => {
                });
        },
        deleteFromWishlist(context, payload) {
            context.commit('setLoadingState', true);
            axios.post('/api/v1/remove_wishlist', payload)
                .then((response) => {
                    context.dispatch('wishlistItems')
//                     context.commit('setSuccessMessage', 'Item removed from wishlist');
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        async applyCoupon(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setFormErrors', {});
            return new Promise((resolve, reject) => {
                axios.post('/api/v1/buyer/apply-coupon', payload)
                    .then((response) => {
                        context.commit('setPromoCode', response.data.coupon);
                        context.commit('setSuccessMessage', 'Best Coupon is applied.');
                        resolve('Successfull.');
                    })
                    .catch((error) => {
                        var formErrors = error.response.data.errors
                        context.commit('setFormErrors', formErrors);
                        if (error.response.status == 401) {
                            context.commit('setErrorMessage', 'Please login first to apply coupon.');
                        } else {
                            context.commit('setErrorMessage', error.response.data.message);
                        }
                        reject('Something Wrong.');
                    })
                    .finally(() => {
                        context.commit('setLoadingState', false);
                    });
            });
        },
        removeCoupon(context, payload) {
            context.commit('setLoadingState', true);
            axios.post('/api/v1/buyer/remove-coupon', payload)
                .then((response) => {
                    context.commit('setPromoCode', null);
                    context.commit('setSuccessMessage', 'Coupon code cleaned.');
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
    },
    getters: {
        getLoading(state) {
            return state.loading;
        },
        getLoadingCounter(state) {
            return state.loadingCounter;
        },
        getSearchLoading(state) {
            return state.searchLoading;
        },
        getSuccessMessage(state) {
            return state.successMessage;
        },
        getFormErrors(state) {
            return state.formErrors;
        },
        getErrorMessage(state) {
            return state.errorMessage;
        },
        getHeaderContents(state) {
            return state.headerContents;
        },
        getFooterContents(state) {
            return state.footerContents;
        },
        getCategories(state) {
            return state.categories;
        },
        getSocialLinks(state) {
            return state.socialLinks;
        },
        getCartItems(state) {
            return state.cartItems;
        },
        getWishlistItems(state) {
            return state.wishlistItems;
        },
        getDefaultImage(state) {
            return state.defaultImage;
        },
        getSearchedItems(state) {
            return state.searchedItems;
        },
        getSearchedCategories(state) {
            return state.searchedCategories;
        },
        getStates(state) {
            return state.states;
        },
        getCountries(state) {
            return state.countries;
        },
        getShippingMethods(state) {
            return state.shippingMethods;
        },
        getPromoCode(state) {
            return state.promoCode;
        },
        getPointExists(state) {
            return state.pointExists;
        },
        getDollarDiscount(state) {
            return state.dollarDiscount;
        },
    }
}

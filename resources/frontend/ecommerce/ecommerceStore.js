import * as helper from "../customer/helpers/authHelper";
import * as common from "../customer/helpers/commonHelper";
const user = common.getLocalUser();
export default {
    namespaced: true,
    state: {
        currentUser: user,
        isLoggedIn: !!user,
        loadingCounter: 0,
        loading: false,
        successMessage: '',
        errorMessage: '',
        categoryNotFound: false,
        productNotFound: false,
        breadCrumbs: null,
        products: [],
        paginatedProducts: null,
        defaultImage: null,
        allValues: [],
        allBrands: [],
        colors: [],
        sizes: [],
        product: null,
        productBreadcrumbs: null,
        sizechart: null,
        returns: null,
        productWishlist: null,
        productImages: [],
        relatedProducts: [],
        recentlyViewedProducts: [],
        addresses: [],
        formErrors: {},
        singleProductData: {},
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
        setIsLoggedIn(state, payload = false) {
            state.isLoggedIn = payload;
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
        setCategoryNotFound(state, payload = false) {
            state.categoryNotFound = payload;
        },
        setProductNotFound(state, payload = false) {
            state.productNotFound = payload;
        },
        setBreadCrumbs(state, breadCrumbs) {
            state.breadCrumbs = breadCrumbs;
        },
        setProducts(state, products) {
            state.products = products;
        },
        setPaginatedProducts(state, paginatedProducts) {
            state.paginatedProducts = paginatedProducts;
        },
        setDefaultImage(state, defaultImage) {
            state.defaultImage = defaultImage;
        },
        setAllValues(state, values) {
            state.allValues = values;
        },
        setAllBrands(state, brands) {
            state.allBrands = brands;
        },
        setProduct(state, product) {
            state.product = product;
        },
        setProductBreadCrumbs(state, breadcrumbs) {
            state.productBreadcrumbs = breadcrumbs;
        },
        setProductWishlist(state, productWishlist) {
            state.productWishlist = productWishlist;
        },
        setSizechart(state, sizechart) {
            state.sizechart = sizechart;
        },
        setReturns(state, returns) {
            state.returns = returns;
        },
        setProductImages(state, images) {
            state.productImages = images;
        },
        setColors(state, colors) {
            state.colors = colors;
        },
        setSizes(state, sizes) {
            state.sizes = sizes;
        },
        setRelatedProducts(state, products) {
            state.relatedProducts = products;
        },
        setRecentlyViewedProducts(state, products) {
            state.recentlyViewedProducts = products;
        },
        setAddresses(state, addresses) {
            state.addresses = addresses;
        },
        setSingleProductData(state, product) {
            state.singleProductData = product;
        },
    },
    actions: {
        checkCustomer(context, payload) {
            helper.check(payload)
                .then((response) => {
                    context.commit('setIsLoggedIn', response.logged);
                })
                .catch((error) => {
                    context.commit('setIsLoggedIn', false);
                })
                .finally(() => {
                });
        },
        products(context, payload) {
            context.commit('setLoadingState', true);
            return new Promise((resolve, reject) => {
                let url = '';
                if(payload.third)
                    url = '/api/v1/search_items/'+payload.parent+'/'+payload.second+'/'+payload.third;
                else if(payload.second)
                    url = '/api/v1/search_items/'+payload.parent+'/'+payload.second
                else if(payload.search_text)
                    url = '/api/v1/search_items/search_text/'+payload.search_text
                else
                    url = '/api/v1/search_items/'+payload.parent
                axios.post(url, payload)
                    .then((response) => {
                        if (response.data.notfound == 404) {
                            context.commit('setCategoryNotFound', true);
                            window.location = '/';
                        } else {
                            if (response.data.currentCategory) {
                                context.commit('setBreadCrumbs', response.data.currentCategory);
                            } else {
                                let breadCrumbs = {
                                    current: {
                                        name: 'Search Result'
                                    }
                                }
                                context.commit('setBreadCrumbs', breadCrumbs);
                            }
                            context.commit('setProducts', response.data.items.data);
                            context.commit('setPaginatedProducts', response.data.items);
                            context.commit('setDefaultImage', response.data.default_img);
                        }
                        resolve('Successfull.');
                    })
                    .catch((error) => {
                        reject('Something Wrong.');
                    })
                    .finally(() => {
                        context.commit('setLoadingState', false);
                    });
            });
        },
        async singleProduct(context, payload) {
            context.commit('setLoadingState', true);
            context.commit('setProductNotFound', false);
            const guestID = localStorage.getItem('cart') ? localStorage.getItem('cart') : '';
            return new Promise((resolve, reject) => {
                axios.get('/api/v1/single-product/' + payload+'?user='+guestID)
                    .then((response) => {
                        if (response.data.notfound == 404) {
                            context.commit('setProductNotFound', true);
                            window.location = '/';
                        } else {
                            context.commit('setProduct', response.data.product);
                            context.commit('setProductBreadCrumbs', response.data.breadcrumbs);
                            context.commit('setSizechart', response.data.sizechart);
                            context.commit('setReturns', response.data.returns);
                            context.commit('setColors', response.data.colors);
                            context.commit('setSizes', response.data.sizes);
                            context.commit('setProductImages', response.data.images);
                            context.commit('setRelatedProducts', response.data.relatedItem);
                            context.commit('setDefaultImage', response.data.default_img);
                            context.commit('setProductWishlist', response.data.wishlist);
                        }
                        resolve('Successfull.');
                    })
                    .catch((error) => {
                        reject('Something Wrong.');
                    })
                    .finally(() => {
                        context.commit('setLoadingState', false);
                    });
            });
        },
        async singleProductData(context, payload) {
            context.commit('setLoadingState', true);
            return new Promise((resolve, reject) => {
                axios.get('/api/v1/single-product/' + payload.slug)
                    .then((response) => {
                        if (response.data.notfound == 404) {
                            context.commit('setProductNotFound', true);
                            window.location = '/';
                        } else {
                            context.commit('setSingleProductData', response.data.product);
                            context.commit('setProduct', response.data.product);
                            context.commit('setLoadingState', false);
                        }
                        resolve('Successfull.');
                    })
                    .catch((error) => {
                        reject('Something Wrong.');
                    })
                    .finally(() => {
                        context.commit('setLoadingState', false);
                    });
            });
        },
        productView(context, payload) {
            axios.get('/api/v1/product-view/' + payload)
                .then((response) => {})
        },
        recentlyViewed(context) {
            context.commit('setLoadingState', true);
            axios.get('/api/v1/recently-viewed')
                .then((response) => {
                    context.commit('setRecentlyViewedProducts', response.data.recentlyViewed);
                }).finally(()=>context.commit('setLoadingState', false))
        },
        allValues(context) {
            context.commit('setLoadingState', true);
            axios.get('/api/v1/product-values')
                .then((response) => {
                    context.commit('setAllValues', response.data.productValues);
                })
                .finally(() => {
                    context.commit('setLoadingState', false);
                });
        },
        allBrands(context) {
            context.commit('setLoadingState', false);
            axios.get('/api/v1/product-brands')
                .then((response) => {
                    context.commit('setAllBrands', response.data.productBrands);
                })
                .finally(() => {
                    context.commit('setLoadingState', true);
                });
        },
        async addToCart(context, payload) {
            context.commit('setFormErrors', {});
            return new Promise((resolve, reject) => {
                const guestID = localStorage.getItem('cart') ? localStorage.getItem('cart') : '';
                if(guestID.length > 0 && guestID.length < 10){
                    payload['guest_id'] = guestID;
                }else{
                    payload['guest_id'] = '';
                }
                axios.post('/api/v1/add-to-cart', payload)
                    .then((response) => {
                        if(response.data.guest)
                            localStorage.setItem('cart', response.data.guest);
                        resolve('Successfull.');
                    })
                    .catch((error) => {
                        context.commit('setErrorMessage', 'Something wrong.');
                        var formErrors = error.response.data.errors
                        context.commit('setFormErrors', formErrors);
                        reject('Something Wrong.');
                    })
                    .finally(() => {
                    });
            });
        },
        updateCartItem(context, payload) {
            const guestId = localStorage.getItem('cart') ? localStorage.getItem('cart') : '';
            payload['guest_id'] = guestId
            context.commit('setFormErrors', {});
            return new Promise((resolve, reject) => {
                axios.post('/api/v1/update-cart-item', payload)
                    .then((response) => {
                        context.commit('setSuccessMessage', 'Item cart updated');
                        resolve('Successfull.');
                    })
                    .catch((error) => {
                        context.commit('setErrorMessage', 'Something wrong');
                        var formErrors = error.response.data.errors
                        let errors = [];
                        errors['cartItems.' + payload.index + '.quantity'] = formErrors['quantity'];
                        context.commit('setFormErrors', errors);
                        reject('Something Wrong.');
                    })
                    .finally(() => {
                    });
            });
        },
        updateCart(context, payload) {
            const guestId = localStorage.getItem('cart') ? localStorage.getItem('cart') : '';
            payload['guest_id'] = guestId
            return new Promise((resolve, reject) => {
                axios.post('/api/v1/update-cart', payload)
                    .then((response) => {
                        context.commit('setSuccessMessage', 'Item cart updated');
                        resolve('Successfull.');
                    })
                    .catch((error) => {
                        var formErrors = error.response.data.errors
                        context.commit('setFormErrors', formErrors);
                        context.commit('setErrorMessage', 'Something wrong');
                        reject('Something Wrong.');
                    })
                    .finally(() => {
                    });
            });
        },
        addToWishlist(context, payload) {
            return axios.post('/api/v1/add-to-wishlist', payload)
                .then((response) => {
                    context.commit('setProductWishlist', response.data.wishlist);
                })
                .catch((error) => {
                    context.commit('setErrorMessage', error.response.data.message);
                })
                .finally(() => {

                });
        },
        removeFromWishlist(context, payload) {
            return axios.post('/api/v1/add-to-wishlist', payload)
                .then((response) => {
                    context.commit('setProductWishlist', null);
//                     context.commit('setSuccessMessage', 'Item removed from wishlist');
                })
                .catch((error) => {
                    context.commit('setErrorMessage', 'Something wrong');
                })
                .finally(() => {
                });
        },
        checkoutAddresses(context) {
            let payload = {
                manage: 'checkout'
            }
            axios.post('/api/v1/address', payload)
                .then((response) => {
                    context.commit('setAddresses', response.data.addresses.data);
                })
                .finally(() => {
                });
        },
        checkout(context, payload) {
            const guestId = localStorage.getItem('cart') ? localStorage.getItem('cart') : '';
            payload['guest_id'] = guestId
            return axios.post('/api/v1/buyer/checkout', payload)
                .then((response) => {
                    localStorage.setItem('cart', '');
                    localStorage.setItem("orderNumber", response.data.order_number);
                    window.location = '/order-complete';
                })
                .catch((error) => {
                    var formErrors = error.response.data.errors
                    context.commit('setFormErrors', formErrors);
                    context.commit('setErrorMessage', 'Something wrong');
                })
                .finally(() => {
                });
        },
        sendOrderEmail(context, payload) {
            // context.commit('setLoadingState', true);
            // axios.post('/api/v1/buyer/sendOrderEmail', payload)
            //     .then((response) => {
            //         // window.location = '/order-complete?order_number=' + payload.order_number;
            //     })
                // .catch((error) => {
                //     // window.location = '/order-fail?order_number=' + payload.order_number;
                // })
                // .finally(() => {
                //     context.commit('setLoadingState', false);
                // });
        },
        checkOrder(context, payload) {
            context.commit('setLoadingState', true);
            axios.post('/api/v1/buyer/checkOrder', payload)
                .then((response) => {
                    // let formData = {
                    //     'order_number': response.data.order_number
                    // }
                    // context.dispatch('sendOrderEmail', payload);
                    context.commit('setSuccessMessage', 'Your Order is successfull. Order number: ' + payload.order_number);
                })
                .catch((error) => {
                    // window.location = '/';
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
        getSuccessMessage(state) {
            return state.successMessage;
        },
        getErrorMessage(state) {
            return state.errorMessage;
        },
        getFormErrors(state) {
            return state.formErrors;
        },
        getCategoryNotFound(state) {
            return state.categoryNotFound;
        },
        getProductNotFound(state) {
            return state.productNotFound;
        },
        getBreadCrumbs(state) {
            return state.breadCrumbs;
        },
        getProducts(state) {
            return state.products;
        },
        getPaginatedProducts(state) {
            return state.paginatedProducts;
        },
        getDefaultImage(state) {
            return state.defaultImage;
        },
        getProduct(state) {
            return state.product;
        },
        getProductBreadcrumbs(state) {
            return state.productBreadcrumbs;
        },
        getSizechart(state) {
            return state.sizechart;
        },
        getReturns(state) {
            return state.returns;
        },
        getProductImages(state) {
            return state.productImages;
        },
        getProductWishlist(state) {
            return state.productWishlist;
        },
        getRelatedProducts(state) {
            return state.relatedProducts;
        },
        getRecentlyViewedProducts(state, products) {
            return state.recentlyViewedProducts;
        },
        getAllValues(state) {
            return state.allValues;
        },
        getAllBrands(state) {
            return state.allBrands;
        },
        getColors(state) {
            return state.colors;
        },
        getSizes(state) {
            return state.sizes;
        },
        getAddresses(state) {
            return state.addresses;
        },
        getSingleProduct(state) {
            return state.singleProductData;
        },
        getIsLoggedIn(state) {
            return state.isLoggedIn;
        },
    }
}

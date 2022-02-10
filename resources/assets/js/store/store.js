import Axios from "axios" 
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
export default{ 
      state:{ 
            DefaultCategory:[], 
            HomePageDefaultContent:[],
            HeaderContent:[],
            FooterContent:[],
            StaticPageState:[], 
            Items:[],
            SingleItem:[],
            CartItems:[],
            ProfileInfo:[],
            Wishlist:[],
            Sizes:[],
            Colors:[],
            Orders:[],
            User: JSON.parse(localStorage.getItem('UserData')) || null,
            Token: localStorage.getItem('access_token') || null,
            CheckAuth:null,
            States:'',
            Country:'',
      }, 
      getters:{  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// GETTERS STATE 
/* -------------------------- Default Getters list -------------------------- */
            HeaderCategory(state){ 
                  return state.DefaultCategory;
            }, 
            HeaderDefaultContentGetters(state){
                  return state.HeaderContent;
            }, 
            FooterContents(state){
                  return state.FooterContent
            },
            StaticPageContents(state){ 
                  return state.StaticPageState
            },
            ShowCartItems(state){
                  return state.CartItems
            }, 
/* -------------------------- Homepage Getters List ------------------------- */ 
            HomePageContent(state){
                  return state.HomePageDefaultContent;
            }, 
            CategoryItems(state){
                  return state.Items
            },
            SingleItem(state){
                  return state.SingleItem
            },
            AllSize(state){
                  return state.Sizes
            },
            AllColors(state){
                  return state.Colors
            },
/* -------------------------- Buyer Profile Getters ------------------------- */
            ProfileInfo(state){
                  return state.ProfileInfo
            },
            Wishlist(state){
                  return state.Wishlist
            },
            logout(state){
                   
            },
            CheckTokenIsvalid(state){
                  return state.Token
            },
            GetUserDataCheck(state){
                return state.CheckAuth;  
            },
            GetLoginUserData(state){
                  return state.User;  
            },
            GetAllCountry(state){
                  return state.Country
            },
            GetAllSate(state){ 
                  return state.States
            },
            GetOrders(state){
                  return state.Orders
            },
      },
      actions:{ ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ACTIONS STATE 
/* --------------------------- Default Action List -------------------------- */ 
            DefaultCategory(context){
                  Axios.get('/api/v1/categories')
                  .then((response)=>{ 
                        context.commit('CategoryMutation',response.data.categories);
                  }) 
            }, 
            HeaderDefaultContentAcion(context){
                  Axios.get('/api/v1/headerdefaultcontent')
                  .then((response)=>{   
                        context.commit('HeaderDefaultContentMutation',response.data.content);
                  }) 
            },
            DefaultFooterContent(context){
                  Axios.get('/api/v1/default-footer')
                  .then((response)=>{   
                        context.commit('FooterDefaultContent',response.data.content);
                  }) 
            }, 
            StaticPageDispatch(context,payload){ 
                  Axios.get('/api/v1/static-page/'+payload)
                  .then((response)=>{    
                        context.commit('StaticPageMutation',response.data);
                  }) 
            },
            GetCartItem(context){ 
                  let id = null;
                  if(this.state.User){ 
                        id = this.state.User.id;
                  } 
                  axios.post('/api/v1/cart-items', { data:id ,headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                  .then((response)=>{   
                        context.commit('CartItemMutation',response.data.cartitems);
                  })
            }, 

/* -------------------------- Home Page Action LIst ------------------------- */ 
            HomePageDefaultContent(context){
                  Axios.get('/api/v1/homepage-content/')
                  .then((response)=>{
                        context.commit('HomePageDefaultContentMutation', response.data);
                  });
            },
             

/* -------------------------- Category Page Action -------------------------- */
            GetAllProduct(context){
                  Axios.get('/api/v1/product/')
                  .then((response)=>{
                        context.commit('ItemsMutation',response.data.items);
                  })
            },
            ParentCategoryAction(context, payload){     
                  Axios.post('/api/v1/search_items',payload)
                  .then((response)=>{  
                        context.commit('ItemsMutation',response.data.items);
                  })
            }, 
            ParentCategoryActionPagination(context, payload){  
                  Axios.post('/api/v1/search_items?page='+payload.page,payload)
                  .then((response)=>{ 
                        context.commit('ItemsMutation',response.data);
                  })
            }, 
            async GetSingleProductInfo(context,payload){
                   
                  const item = await Axios.get('/api/v1/single-product/'+payload)
                  .then((response)=>{  
                        return response.data;
                  })

                  context.commit('SingleItemMutation',item);
            }, 
            GetsAllSizesAction(context){
                  Axios.get('/api/v1/all-sizes')
                  .then((response)=>{  
                        context.commit('AllSizeMutation',response.data);
                  })
            },
            GetsAllColorsAction(context){
                  Axios.get('/api/v1/all-colors')
                  .then((response)=>{  
                        context.commit('AllColorsMutation',response.data.colors);
                  }) 
            },

/* -------------------------- Buyer Profile Action -------------------------- */
            UserLogin(context ,formdata){   
                  const  Token = formdata.token 
                  localStorage.setItem('access_token',Token)
                  context.commit('access_token',Token); 
                  context.commit('CheckAuthMutation',1); 
                  context.commit('storeUserData',formdata.user);
                  localStorage.setItem('UserData',JSON.stringify(formdata.user))  
            },
            LogoutUser(context){  
                  Axios.post('/api/v1/buyer/logout')
                  .then((response)=>{   
                        toast.fire({
                              icon: 'success',
                              title: 'Sign Out successfully'
                              })
                        const  Token = null;
                        localStorage.setItem('access_token',null)
                        context.commit('access_token',null); 
                        context.commit('storeUserData',null);
                        localStorage.setItem('UserData',null)  
                        context.commit('CheckAuthMutation',null); 
                        
                  })
            },
            GetUserData(context, payload){  
                  context.commit('UserAccessCheck',localStorage.getItem('access_token')); 
            },
            GetProfileInformation(context){  
                  axios.get('/api/v1/profile', { headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                  .then((response)=>{   
                        context.commit('ProfileDataMutation',response.data);
                  }) 
            }, 
            GetProfileWishlist(context){
                  let id = null;
                  if(this.state.User){ 
                        id = this.state.User.id;
                  } 
                  axios.post('/api/v1/wishlist', { data:id ,headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                  .then((response)=>{   
                        context.commit('WishlistDataMutation',response.data.items);
                  }) 
            }, 
            AllOrders(context){
                  axios.get('/api/v1/buyer/orders', { headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                  .then((response)=>{   
                        context.commit('OrderDataMutation',response.data);
                  }) 
            }, 
/* ----------------------------- Checkout Action ---------------------------- */
            GetAllState(context){
                  Axios.get('/api/v1/state')
                  .then((response)=>{    
                        context.commit('StateMutation',response.data);
                  })
            },
            GetAllCountry(context){
                  Axios.get('/api/v1/country')
                  .then((response)=>{   
                        context.commit('CountryMutation',response.data);
                  })
            },

      },
      mutations:{ ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// MUTITION STATE 
/* -------------------------- Default Mutation List ------------------------- */
            CategoryMutation(state, payload){
                  return state.DefaultCategory = payload;
            }, 
            HeaderDefaultContentMutation(state, payload){
                  return state.HeaderContent = payload
            }, 
            FooterDefaultContent(state, payload){
                  return state.FooterContent = payload
            },
            StaticPageMutation(state,payload){
                  return state.StaticPageState = payload;
            },
            CartItemMutation(state,payload){
                  return state.CartItems = payload
            },

/* ------------------------- Home Page Mutation List ------------------------ */
            HomePageDefaultContentMutation(state,payload){
                  return state.HomePageDefaultContent = payload;
            }, 

/* ------------------------- Category Page Mutation ------------------------- */
            ItemsMutation(state,payload){
                  return state.Items = payload
            },
            SingleItemMutation(state, payload){
                  return state.SingleItem = payload
            }, 
            AllSizeMutation(state, payload){
                  return state.Sizes = payload;
            },
            AllColorsMutation(state, payload){
                  return state.Colors = payload
            },

/* -------------------- Profile Data Information Mutation ------------------- */
            storeUserData(state, payload){ 
                  return state.User = payload;
            },
            access_token(state,payload){
                  return state.Token = payload;
            },
            ProfileDataMutation(state, payload){
                  return state.ProfileInfo = payload
            }, 
            WishlistDataMutation(state, payload){
                  return state.Wishlist = payload
            }, 
            CheckAuthMutation(state, payload){
                  return state.CheckAuth = payload
            }, 

            UserAccessCheck(state, payload){ 
                  return state.CheckAuth = payload
            },
            StateMutation(state, payload){ 
                  return state.States = payload
            },
            CountryMutation(state,payload){
                  return state.Country = payload
            },
            OrderDataMutation(state, payload){
                  return state.Orders = payload;
            },
            
            
      }
}
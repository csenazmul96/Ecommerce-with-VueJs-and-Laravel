(window.webpackJsonp=window.webpackJsonp||[]).push([[12],{TI3i:function(t,e,r){"use strict";r.r(e);var i=r("o0o1"),s=r.n(i),a=r("Wgwc"),n=r("stGK"),o=r("fuM5");function l(t,e,r,i,s,a,n){try{var o=t[a](n),l=o.value}catch(t){return void r(t)}o.done?e(l):Promise.resolve(l).then(i,s)}function d(t){return function(){var e=this,r=arguments;return new Promise((function(i,s){var a=t.apply(e,r);function n(t){l(a,i,s,n,o,"next",t)}function o(t){l(a,i,s,n,o,"throw",t)}n(void 0)}))}}var _={name:"customerDashboard",mixins:[r("ZNft").a],data:function(){return{orderEnumeration:{1:"init",2:"new",3:"confirm",4:"partially shipped",5:"fully shipped",6:"back",7:"cancel by buyer",8:"cancel by vendor",9:"cancel by agrement",10:"returned",11:"declined"}}},components:{productComponent:function(){return r.e(0).then(r.bind(null,"sdT4"))},orderPagination:function(){return r.e(1).then(r.bind(null,"MKMn"))}},beforeCreate:function(){this.$store&&this.$store.state&&this.$store.state.defaultStore||this.$store.registerModule("defaultStore",n.a),this.$store&&this.$store.state&&this.$store.state.customerStore||this.$store.registerModule("customerStore",o.a)},filters:{dateFormate:function(t,e){return a(t).format(e)},round:function(t,e){return t||(t=0),e||(e=0),t=(t=Number(t)).toFixed(e)}},mounted:function(){this.orderNumber?this.singleOrder():this.ordersPreload()},watch:{$route:function(t,e){this.orderNumber?this.singleOrder():this.ordersPreload()}},computed:{defaultImage:function(){return this.$store.getters["defaultStore/getDefaultImage"]},orders:function(){return this.$store.getters["customerStore/getOrders"]},orderSingle:function(){return this.$store.getters["customerStore/getSingleOrder"]},orderNumber:function(){return this.$route.params.order_number},headerContents:{get:function(){return this.$store.getters["defaultStore/getHeaderContents"]},set:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null;this.$store.commit("defaultStore/setHeaderContents",t)}}},methods:{ordersPreload:function(){var t=arguments,e=this;return d(s.a.mark((function r(){var i;return s.a.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return i=t.length>0&&void 0!==t[0]?t[0]:1,e.$Progress.start(),$("#orderListPreload").fadeIn(),r.next=5,e.$store.dispatch("customerStore/orders",i).then((function(t){$("#orderListPreload").fadeOut("slow")})).catch((function(t){}));case 5:case"end":return r.stop()}}),r)})))()},singleOrder:function(){var t=this;return d(s.a.mark((function e(){return s.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.$Progress.start(),$("#orderListPreload").fadeIn(),e.next=4,t.$store.dispatch("customerStore/singleOrder",t.orderNumber).then((function(t){$("#orderListPreload").fadeOut("slow")})).catch((function(t){}));case 4:case"end":return e.stop()}}),e)})))()},buyerLogout:function(){this.$Progress.start(),this.$store.dispatch("customerStore/tryLogout")}}},c=r("KHd+"),v=Object(c.a)(_,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("div",{staticClass:"account_bredcrumbs"},[t._m(0),t._v(" "),r("div",{staticClass:"a_c_bredcrumbs_right"},[r("a",{attrs:{href:"javascript:void(0)"},on:{click:function(e){return e.preventDefault(),t.buyerLogout(e)}}},[t._v("Log Out")])])]),t._v(" "),t.orderNumber?r("div",{staticClass:"my_acc_container"},[r("div",{staticClass:"card my_acc_order"},[r("div",{staticClass:"card_header"},[r("h2",[t._v("Vendor Order Details - "+t._s(t.orderNumber))])]),t._v(" "),r("div",{staticClass:"card-body",staticStyle:{position:"relative","min-height":"150px"}},[t._m(4),t._v(" "),r("div",{staticClass:"row"},[r("div",{staticClass:"content col-md-12 margin-bottom-1x"},[r("h4",[t._v("Vendor Order Details - "+t._s(t.orderNumber))]),t._v(" "),r("br"),r("br"),r("br")])]),t._v(" "),t.orderSingle?r("div",{staticClass:"row"},[r("div",{staticClass:"col-md-5"},[r("img",{attrs:{src:t.headerContents&&t.headerContents.logo?t.headerContents.logo:"",alt:"Site logo"}})]),t._v(" "),r("div",{staticClass:"col-md-2"}),t._v(" "),r("div",{staticClass:"col-md-5 "},[r("table",{staticClass:"table table-bordered"},[r("tbody",[r("tr",[r("th",[t._v("Order No.")]),t._v(" "),r("td",{staticClass:"text-right"},[t._v(t._s(t.orderSingle.order_number))])]),t._v(" "),r("tr",[r("th",[t._v("Order Date")]),t._v(" "),r("td",{staticClass:"text-right"},[t._v(t._s(t._f("dateFormate")(t.orderSingle.created_at,"MMMM DD, YYYY hh:mm:ss A")))])]),t._v(" "),r("tr",[r("th",[t._v("Status")]),t._v(" "),r("td",{staticClass:"text-right"},[t._v("\n                                        "+t._s(t.orderEnumeration[t.orderSingle.status])+"\n                                    ")])])])])])]):t._e(),t._v(" "),t.orderSingle?r("div",{staticClass:" padding-bottom-1x"},[r("table",{staticClass:"table table-bordered"},[t._m(5),t._v(" "),r("tbody",[r("tr",[r("td",[t.orderSingle.shipping_location?[t._v(t._s(t.orderSingle.shipping_location)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.shipping_address?[t._v(t._s(t.orderSingle.shipping_address)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.shipping_unit?[t._v(t._s(t.orderSingle.shipping_unit)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.shipping_city?[t._v(t._s(t.orderSingle.shipping_city)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.shipping_state||t.orderSingle.shipping_zip?[t._v(t._s(t.orderSingle.shipping_state)+" - "+t._s(t.orderSingle.shipping_zip)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.shipping_country?[t._v(t._s(t.orderSingle.shipping_country)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.shipping_phone?[t._v("Phone: "+t._s(t.orderSingle.shipping_phone)+" , ")]:t._e()],2),t._v(" "),r("td",[t.orderSingle.billing_location?[t._v(t._s(t.orderSingle.billing_location)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.billing_address?[t._v(t._s(t.orderSingle.billing_address)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.billing_unit?[t._v(t._s(t.orderSingle.billing_unit)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.billing_city?[t._v(t._s(t.orderSingle.billing_city)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.billing_state||t.orderSingle.billing_zip?[t._v(t._s(t.orderSingle.billing_state)+" - "+t._s(t.orderSingle.billing_zip)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.billing_country?[t._v(t._s(t.orderSingle.billing_country)+" "),r("br")]:t._e(),t._v(" "),t.orderSingle.billing_phone?[t._v("Phone: "+t._s(t.orderSingle.billing_phone)+" , ")]:t._e()],2)]),t._v(" "),r("tr",[t._m(6),t._v(" "),r("td",[r("b",[t._v("Phone: ")]),t._v(t._s(t.orderSingle.shipping_phone))])])])]),t._v(" "),r("table",{staticClass:"table table-bordered"},[r("tbody",[r("tr",[r("th",[t._v("Shipping Method")]),t._v(" "),r("td",[t._v(t._s(t.orderSingle.shipping))]),t._v(" "),r("th",[t._v("Tracking Number")]),t._v(" "),r("td",[t._v(t._s(t.orderSingle.tracking_number))]),t._v(" "),r("th",[t._v("Invoice Number")]),t._v(" "),r("td",[t._v(t._s(t.orderSingle.invoice_number))])])])])]):t._e(),t._v(" "),r("h4",[t._v("Items")]),t._v(" "),r("hr",{staticClass:"padding-bottom-1x"}),t._v(" "),r("div",{staticClass:"table-responsive"},[r("table",{staticClass:"table table-bordered"},[t._m(7),t._v(" "),t.orderSingle&&t.orderSingle.items?r("tbody",t._l(t.orderSingle.items,(function(e,i){return r("tr",{key:"orderItem_"+i},[r("td",{attrs:{rowspan:""}},[r("img",{staticStyle:{height:"100px"},attrs:{src:e.item.images&&e.item.images.length>0?e.item.images[0].thumbs_img:t.defaultImage.value,alt:"Hologram Product "+e.item.style_no},on:{error:function(r){return t.imgErrHndlHasan(r,e.item.images[0].thumbs_image_path)}}})]),t._v(" "),r("td",{staticClass:"text-center align-middle"},[t._v(" "+t._s(e.style_no))]),t._v(" "),r("td",{staticClass:"text-center align-middle"},[t._v(" "+t._s(e.size_name?e.size_name:""))]),t._v(" "),r("td",{staticClass:"text-center align-middle"},[t._v(" "+t._s(e.color_name?e.color_name:""))]),t._v(" "),r("td",{staticClass:"text-center align-middle"},[t._v(" "+t._s(e.qty))]),t._v(" "),r("td",{staticClass:"text-center align-middle"},[t._v(" $"+t._s(t._f("round")(e.per_unit_price,2)))]),t._v(" "),r("td",{staticClass:"text-right align-middle"},[t._v(" $"+t._s(t._f("round")(e.amount,2)))])])})),0):t._e()])]),t._v(" "),t.orderSingle?r("div",{staticClass:"row"},[r("div",{staticClass:"col-md-9"}),t._v(" "),r("div",{staticClass:"col-md-3"},[r("table",{staticClass:"table table-bordered"},[r("tbody",[r("tr",[r("th",[t._v("Sub Total")]),t._v(" "),r("td",{staticClass:"text-right"},[t._v("$"+t._s(t._f("round")(t.orderSingle.subtotal,2)))])]),t._v(" "),t.orderSingle.discount>0?r("tr",[r("th",[t._v("Discount")]),t._v(" "),r("td",{staticClass:"text-right"},[t._v("$"+t._s(t._f("round")(t.orderSingle.discount,2)))])]):t._e(),t._v(" "),t.orderSingle.dollar_point_discount>0?r("tr",[r("th",[t._v("Point Discount")]),t._v(" "),r("td",{staticClass:"text-right"},[t._v("$"+t._s(t._f("round")(t.orderSingle.dollar_point_discount,2)))])]):t._e(),t._v(" "),r("tr",[r("th",[t._v("Shipping Cost")]),t._v(" "),r("td",{staticClass:"text-right"},[t._v("$"+t._s(t._f("round")(t.orderSingle.shipping_cost,2)))])]),t._v(" "),r("tr",[r("th",[t._v("Total")]),t._v(" "),r("td",{staticClass:"text-right"},[r("b",[t._v("$"+t._s(t._f("round")(t.orderSingle.total,2)))])])])])])])]):t._e(),t._v(" "),t.orderSingle?r("div",{staticClass:"row"},[r("div",{staticClass:"col-md-12"},[r("p",[r("b",[t._v("Note: "+t._s(t.orderSingle.note))])])])]):t._e(),t._v(" "),r("div",{staticClass:"row"},[r("div",{staticClass:"content col-md-12 margin-top-1x"},[r("router-link",{staticClass:"btn btn_common width_200p float-left",attrs:{to:{name:"order-detail"}}},[t._v("Back To\n                            Order List\n                        ")])],1),t._v(" "),t._m(8)])])])]):r("div",{staticClass:"my_acc_container"},[r("div",{staticClass:"card my_acc_order"},[t._m(1),t._v(" "),r("div",{staticClass:"card-body",staticStyle:{position:"relative","min-height":"100px"}},[t._m(2),t._v(" "),r("div",{staticClass:"table-responsive"},[r("table",{staticClass:"table table-striped"},[r("tbody",[t._m(3),t._v(" "),t.orders&&t.orders.data&&t.orders.data.length>0?t._l(t.orders.data,(function(e,i){return r("tr",{key:"order_index_"+i},[r("td",[t._v(t._s(t._f("dateFormate")(e.created_at,"MMMM DD, YYYY hh:mm:ss A")))]),t._v(" "),r("td",[r("router-link",{attrs:{to:{name:"order-detail",params:{order_number:e.order_number}}}},[t._v(t._s(e.order_number)+"\n                                        ")])],1),t._v(" "),r("td",[t._v("\n                                        "+t._s(e.tracking_number)+"\n                                    ")]),t._v(" "),r("td",[t._v("$ "+t._s(t._f("round")(e.total,2)))]),t._v(" "),r("td",{staticClass:"text-right"},[r("span",{staticClass:"label label-info"},[t._v(t._s(t.orderEnumeration[e.status]))])])])})):t._e()],2)])])]),t._v(" "),r("div",{staticClass:"review_area"},[r("div",{staticClass:"review_content"},[r("div",{staticClass:"container"},[r("div",{staticClass:"review_bottom r_b_without_login"},[t.orders?r("orderPagination",{attrs:{paginateData:t.orders},on:{paginate:t.ordersPreload}}):t._e()],1)])])])])]),t._v(" "),r("div",{staticClass:"my_acc_container"},[r("div",{staticClass:"my_acc_back"},[r("router-link",{staticClass:"btn btn_transparent width_240p",attrs:{to:{name:"home"}}},[r("i",{staticClass:"fas fa-shopping-cart"}),t._v(" "),r("span",{staticClass:"ml_5"},[t._v("Continue Shopping")])]),t._v(" "),r("button",{staticClass:"btn_transparent width_240p",on:{click:function(e){return e.preventDefault(),t.buyerLogout(e)}}},[t._v("Log Out")])],1)]),t._v(" "),t._m(9)])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"a_c_bredcrumbs_left"},[e("a",{attrs:{href:"javascript:void(0)",onclick:"window.history.back()"}},[this._v("← back ")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card_header"},[e("h2",[this._v("Order History")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"preloader_wrap",attrs:{id:"orderListPreload"}},[e("div",{staticClass:"loader-container"},[e("div",{staticClass:"loader-circle"})])])},function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("tr",[r("th",[t._v("Date")]),t._v(" "),r("th",[t._v("Order #")]),t._v(" "),r("th",[t._v("Order Tracking No.")]),t._v(" "),r("th",[t._v("Amount")]),t._v(" "),r("th",{staticClass:"text-right"},[t._v("Status")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"preloader_wrap",attrs:{id:"orderListPreload"}},[e("div",{staticClass:"loader-container"},[e("div",{staticClass:"loader-circle"})])])},function(){var t=this.$createElement,e=this._self._c||t;return e("thead",[e("tr",[e("th",[this._v("Shipping Address")]),this._v(" "),e("th",[this._v("Billing Address")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("td",[e("b",[this._v("Phone: ")])])},function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("thead",[r("tr",[r("th",[t._v("Image")]),t._v(" "),r("th",{staticClass:"text-center align-middle"},[t._v("Style No.")]),t._v(" "),r("th",{staticClass:"text-center align-middle"},[t._v("Size")]),t._v(" "),r("th",{staticClass:"text-center align-middle"},[t._v("Color")]),t._v(" "),r("th",{staticClass:"text-center align-middle"},[t._v("Qty")]),t._v(" "),r("th",{staticClass:"text-center align-middle"},[t._v("Unit Price")]),t._v(" "),r("th",{staticClass:"text-right align-middle"},[t._v("Amount")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal fade",attrs:{id:"print-modal",tabindex:"-1",role:"dialog","aria-labelledby":"modalLabelSmall","aria-hidden":"true"}},[e("div",{staticClass:"modal-dialog modal-sm"},[e("div",{staticClass:"modal-content"},[e("div",{staticClass:"modal-header"},[e("h4",{staticClass:"modal-title",attrs:{id:"modalLabelSmall"}},[this._v("Print")]),this._v(" "),e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])])]),this._v(" "),e("div",{staticClass:"modal-body"},[e("a",{staticClass:"btn btn-primary",attrs:{href:"",target:"_blank",id:"btnPrintWithImage"}},[this._v("Print with\n                                        Images")]),e("br"),e("br"),this._v(" "),e("a",{staticClass:"btn btn-primary",attrs:{href:"",target:"_blank",id:"btnPrintWithoutImage"}},[this._v("Print without\n                                        Images")])])])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"logout_mobile"},[e("button",{staticClass:"btn_transparent"},[this._v("logout")])])}],!1,null,null,null);e.default=v.exports}}]);
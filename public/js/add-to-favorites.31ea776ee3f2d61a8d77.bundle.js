(window.webpackJsonp=window.webpackJsonp||[]).push([[3],{"4ulx":function(t,e,o){"use strict";o("PFUf")},"7ASG":function(t,e,o){(t.exports=o("I1BE")(!1)).push([t.i,".link--add-to-favorites[data-v-609127d8]{cursor:pointer;font-size:1.35rem}",""])},"KHd+":function(t,e,o){"use strict";function r(t,e,o,r,i,s,n,a){var c,l="function"==typeof t?t.options:t;if(e&&(l.render=e,l.staticRenderFns=o,l._compiled=!0),r&&(l.functional=!0),s&&(l._scopeId="data-v-"+s),n?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(n)},l._ssrRegister=c):i&&(c=a?function(){i.call(this,(l.functional?this.parent:this).$root.$options.shadowRoot)}:i),c)if(l.functional){l._injectStyles=c;var f=l.render;l.render=function(t,e){return c.call(e),f(t,e)}}else{var u=l.beforeCreate;l.beforeCreate=u?[].concat(u,c):[c]}return{exports:t,options:l}}o.d(e,"a",(function(){return r}))},PFUf:function(t,e,o){var r=o("7ASG");"string"==typeof r&&(r=[[t.i,r,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};o("aET+")(r,i);r.locals&&(t.exports=r.locals)},lrRM:function(t,e,o){"use strict";(function(t){e.a={props:["product"],data:function(){return{isFavorited:!1}},mounted:function(){var t=localStorage.getItem("favorites");if(null==t)localStorage.setItem("favorites",JSON.stringify([]));else try{var e=parseInt(this.product.id);t=(t=JSON.parse(localStorage.getItem("favorites"))).filter((function(t){return t===e})),this.isFavorited=0!==t.length}catch(t){console.log(t)}},methods:{toggleFavorites:function(){try{var e=parseInt(this.product.id),o=JSON.parse(localStorage.getItem("favorites"));if(0===o.length)return o.push(e),localStorage.setItem("favorites",JSON.stringify(o)),!0;if(-1===t.inArray(e,o))return o.push(e),localStorage.setItem("favorites",JSON.stringify(o)),!0;if(-1!==t.inArray(e,o))return o=o.filter((function(t){return t!==e})),localStorage.setItem("favorites",JSON.stringify(o)),!1}catch(t){console.log(t)}},toggleItem:function(){this.isFavorited=this.toggleFavorites(),this.isFavorited?toast(this.product.name+" - <strong>ADDED</strong> to your wishlist."):toast(this.product.name+" - <strong>REMOVED</strong> from your wishlist"),this.$root.$emit("favorites_updated")}}}}).call(this,o("EVdn"))},q3Vo:function(t,e,o){"use strict";o.r(e);var r=o("lrRM").a,i=(o("4ulx"),o("KHd+")),s=Object(i.a)(r,(function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("span",{staticClass:"d-flex justify-content-center px-2 align-items-center"},[o("a",{staticClass:"link link--add-to-favorites px-1 mx-1 ",class:this.isFavorited?"text-red":"",on:{click:function(e){return t.toggleItem()}}},[o("i",{staticClass:"fa-heart",class:this.isFavorited?"fas":"far"})])])}),[],!1,null,"609127d8",null);e.default=s.exports}}]);
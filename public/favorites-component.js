(window.webpackJsonp=window.webpackJsonp||[]).push([[10],{0:function(t,e,n){"use strict";function s(t,e,n,s,o,r,i,a){var c,l="function"==typeof t?t.options:t;if(e&&(l.render=e,l.staticRenderFns=n,l._compiled=!0),s&&(l.functional=!0),r&&(l._scopeId="data-v-"+r),i?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(i)},l._ssrRegister=c):o&&(c=a?function(){o.call(this,this.$root.$options.shadowRoot)}:o),c)if(l.functional){l._injectStyles=c;var f=l.render;l.render=function(t,e){return c.call(e),f(t,e)}}else{var u=l.beforeCreate;l.beforeCreate=u?[].concat(u,c):[c]}return{exports:t,options:l}}n.d(e,"a",(function(){return s}))},87:function(t,e,n){"use strict";n.r(e);var s={props:{cssClass:{type:String,default:"text-muted"}},data:function(){return{favoritesCounter:0}},mounted:function(){var t=this;this.favoritesCounter=this.getFavoritesCounter(),this.$root.$on("favorites_updated",(function(){t.favoritesCounter=t.getFavoritesCounter()}))},methods:{getFavoritesCounter:function(){try{var t=localStorage.getItem("favorites");return null==t?(localStorage.setItem("favorites",JSON.stringify([])),0):JSON.parse(t).length}catch(t){return 0}}}},o=n(0),r=Object(o.a)(s,(function(){var t=this.$createElement,e=this._self._c||t;return e("span",{staticClass:"fa-layers fa-fw m-0 position-relative d-flex flex-row"},[e("i",{staticClass:"fas fa-heart text-black",staticStyle:{"font-size":"1.7rem","padding-bottom":"0.35rem"}}),this._v(" "),e("span",{staticClass:"fa-layers-counter bg-highlight text-white d-flex flex-column align-items-center justify-content-center",staticStyle:{"font-size":"0.7rem",position:"absolute","border-radius":"50%",height:"20px",width:"20px",right:"-20px",top:"0"}},[e("span",{staticClass:"d-flex"},[this._v("\n            "+this._s(this.favoritesCounter)+"\n        ")])])])}),[],!1,null,null,null);e.default=r.exports}}]);
$(function() {

    $('body').on('click', '.navbar-nav a.position-absolute.d-flex', function(event) {
        event.preventDefault();
        let parent = $(this).parent('li')
        let icon = $(this).find('.fas')

        if (icon.hasClass('fa-plus')) {
            parent.next('ul').slideDown(function() {
                icon.removeClass('fa-plus').addClass('fa-minus')
            })
        } else {
            parent.next('ul').slideUp(function() {
                icon.removeClass('fa-minus').addClass('fa-plus')
            })
        }

    });

    $('body').on('click', '.jq-category-click', function(event) {
        event.preventDefault();
        try {
            var currentItem = $(this)
            var categorySlug = currentItem.data('slug')
            var requestedUrl = categoryBrowserUrl + '/' + categorySlug
            var routeUrl = requestedUrl.replace(location.origin, '')

            History.pushState({
                    slug: categorySlug
                }, 
                categorySlug, 
                routeUrl
            )

            window.dataLayer.push({
                'event': 'left_nav_click',
            });
        } catch (e) {
            console.log(e)
        }

        if (isMobile()) {
            setTimeout(function() {
                $('#navbar-toggler').trigger('click')
            }, 500)
            $("html, body").animate({ scrollTop: 0 }, 100);
        }

        var state = History.getState()

        if (Object.keys(state.data).length === 0 && state.data.constructor === Object) {
            browseCategory(currentItem, state)
        }
    });
})

window.browseCategory = function(currentItem, state) {

    try {
        localStorage.setItem('scrollPosition', 0)
    } catch (e) {

    }

    var navbarTemplate = '<li class="pl-{depth}">\
                                <a data-slug="{slug}" href="#" data-depth="{depth}" class="nav-item text-red jq-category-click d-block position-relative">\
                                    {name}\
                                </a>\
                          </li>'

    var parentItem = currentItem.parent()
    var categorySlug = currentItem.data('slug')

    if (categorySlug === undefined || categorySlug === null) {
        categorySlug = '';
    }

    var requestedUrl = categorySlug !== '' ? categoryBrowserUrl + '/' + categorySlug : categoryBrowserUrl;

    try {
        if (state.data.formData !== undefined) {
            requestedUrl = requestedUrl + '?' + state.data.formData;
        }
    } catch (e) {
    }

    $('#main-navbar .nav-item').removeClass('font-weight-bold')

    // show loading
    currentItem.removeClass('text-red').addClass('font-weight-bold')

    let icon = currentItem.parent('li').find('i.fas');

    if (icon.length !== 0) {
        currentItem.parents('#main-navbar')
            .find('.fa-minus')
            .removeClass('fa-minus')
            .addClass('fa-plus')
            
        icon.removeClass('fa-plus').addClass('fa-minus')
    }

    prepareAjaxHeader()

    var links = parentItem.siblings('li').find('a')
    links.removeClass('font-weight-bold')

    let container = '#main-navbar'

    $(container).parent().busyLoad('show')

    try {
        if (window.currentAjaxRequest !== null) {
            window.currentAjaxRequest.abort();
        }
    } catch (e) {

    }

    window.currentAjaxRequest = $.ajax({
        method: "POST",
        url: requestedUrl,
        data: $('.jq-filters-container').serialize()
    })
    .done(function(data) {

        // remove other expended subnav
        parentItem.siblings('ul').remove()

        if (data.navItems.length === 0) {
            parentItem.siblings('li').find('a').addClass('text-red')
            currentItem.addClass('text-red')
        } else {
            parentItem.siblings('li').find('a').removeClass('text-red')
        }

        parentItem.next('ul').remove()
        parentItem.after('<ul class="list-unstyled jq-ajax-navbar '+parentItem.attr('class')+'">')

        $.each(data.navItems, function(index, child) {
            child.depth = child.depth + 3
            $( parentItem.next('ul') ).append( nano(navbarTemplate, child) )
        })
        
        if ($('.jq-categories-page').length === 0) {
            location.reload()
        } else {
            app.$emit('refresh_products', data)
        }
    })
    .always(function() {
        $(container).parent().busyLoad('hide')
        setTimeout(function() {
            $('[data-toggle="tooltip"]').tooltip()
        }, 500)
    })
    .fail(function(ret) {
        setTimeout(function(){
            location.reload()
        }, 500)
    })
}

window.isMobile = function () { 
    if( 
        navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)
    )
    {
        return true;
    } else {
        return false;
    }
}

window.checkoutEcommerceEvent = function (availabeCartItems, step = 1) {
    try {
        let productsList = availabeCartItems.map(function(item){
            return {
                'id': item.id,
                'name': item.name,
                'quantity': item.quantity,
                'price': item.price,
            }
        })
        window.dataLayer.push({
            'event': 'checkout',
            'ecommerce': {
                'checkout': {
                    'actionField': {'step': step},
                    'products': productsList
                }
            }
        });
    } catch (e) {
        
    }
}

window.prepareAjaxHeader = function (){

    let token = $('meta[name="_token"]').attr('content');

    $.ajaxSetup({
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token
        }
    });

    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

window.toast = function toast(html){
    $('.toast').toast('hide')
    $('body').prepend('<div aria-live="polite" id="toast" aria-atomic="true" style="position: fixed; top: 5px; right: 5px; z-index: 1000000;">\
        <div style="position: relative;" class="px-1">\
            <div class="jq-toast toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">\
                <div class="toast-body"></div>\
            </div>\
        </div>\
    </div>')
    $('.jq-toast .toast-body').html(html);
    $('.toast').toast('show')
    setTimeout(function(){
        $('#toast').remove()
    }, 3001)
}

window.truncate = function (text, stop, clamp) {
    if (text !== null && text !== undefined) {
        return text.slice(0, stop) + (stop < text.length ? clamp || '...' : '')
    }
    
    return '';
};

function nano(template, data) {
    return template.replace(/\{([\w\.]*)\}/g, function(str, key) {
        var keys = key.split("."), v = data[keys.shift()];
        for (var i = 0, l = keys.length; i < l; i++) v = v[keys[i]]
        return (typeof v !== "undefined" && v !== null) ? v : ""
    })
}
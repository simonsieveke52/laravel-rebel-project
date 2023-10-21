require('bootstrap');
require('jquery');
require('corejs-typeahead');
$('.quote-typeahead').typeahead({
        highlight: true,
        hint: true,
        minLength: 2
    },
    {
        name: 'Product',
        limit: Infinity,
        display: 'name',
        source: function (query,syncResults,asyncResults) {
            $.get('/quoterequest/search', {
                responseType: 'json',
                params: {
                    query: query
                }
            }).then(function (response) {
                asyncResults(response.data);
            });
        },
        templates: {
            empty: '<div class="bg-white border p-3 rounded"><p>No results found</p></div>',
            pending: '<div>Searching...</div>',
            suggestion: function(data) {
                return `<div class="row hover-bg border-radius-0 cursor-pointer bg-white border-secondary2 justify-content-around">
                <div class="col-4 px-1 align-self-center">
                    <img class="img-fluid" style="min-height:120px; width:auto" src="${ data['image']}">
                </div>
                <div class="col-8 pl-0 align-self-center">
                    <h6>${ data['name'] }</h6>
                </div>
            </div>`;
            }
        }
    });
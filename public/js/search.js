
    var queryParameters = {}, queryString = location.search.substring(1), re = /([^&=]+)=([^&]*)/g, map;

    $("#searchBtn").on("click", function(e){
        performSearch();
    });

    $('#search').keyup(function(e){
        if(e.keyCode == 13)
        {
            performSearch();
        }
    });

    function performSearch()
    {
        var search = $('#search').val();
        var filter = $('#filter').val();

        search = $.trim(search);
        filter = $.trim(filter);
        queryParameters['search'] = search.replace(/ /g, "+");
        queryParameters['filter'] = filter.replace(/ /g, "+");
        query = parametrize(queryParameters);

        location.search = query;
    }

    function initSearchFilter() {
        $('#search').val(search);

        if(search != '' & filter == "title") {
            $.each( $('.content_title'), function( key ) {
                var html = $(this).html();
                var regexp = new RegExp(search, "gi");

                // Highlight the search string for user
                $(this).html(html.replace(regexp, '<strong>$&</strong>'));
            });
        }

        $('#filter').val(filter);
    }

    initSearchFilter();

    // Creates a mapap with the query string parameters
    while (m = re.exec(queryString)) {
        queryParameters[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
    }

    function parametrize(queryParameters) {
        var queryArray = new Array();

        for (key in queryParameters) {
            if(queryParameters[key] != null) {
                queryArray.push(key + '=' + queryParameters[key]);
            }
        }

        return queryArray.join('&');
    }

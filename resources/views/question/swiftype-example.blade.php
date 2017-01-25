<html>
<head>

    <style>
        body {
            font-weight: 300;
            font-family: "Avenir", "Helvetica", "Arial", "sans-serif";
            background: #eeeeee;
            margin-top: 20px;
        }
    </style>

</head>

<body>
    <div id="remote">
        <input class="typeahead" type="text" placeholder="search">
    </div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="{{asset('/vendor/swiftype/autocomplete/typeahead.bundle.js')}}"></script>

    <script>
        var suggestionEngine = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: 'http://api.swiftype.com/api/v1/public/engines/search?q=',
                replace: function(url, query) {
                    return url + query + "&engine_key=Bvv4g4nWyhxqcUuP4QGV";
                },
                transform: function(response) {
                    return response.records.questions;
                },
                ajax: {
                    type: "POST",
                    data: {
                        q: function() {
                            return $('.typeahead').val()
                        }
                    }
                }
            }
        });
    </script>

    <script>

        var suggestionTemplate = function (data) {
            return '<div class="tt-suggestion tt-selectable"><a href="' + data.url + '">' + data.title + '</a></div>'
        };

        var footerTemplate = function (data) {
            return '<div class="tt-suggestion tt-selectable"><a href="#">See all results for "' + data.query + '"</a></div>'
        };


        $('#remote .typeahead').typeahead(null,
                {
                    name: 'questions',
                    display: 'title',
                    source: suggestionEngine,
                    templates: {
                        notFound: 'Not Found',
                        suggestion: suggestionTemplate,
                        footer: footerTemplate
                    }
                }
        );
    </script>

    <script>
        $("#remote .typeahead").on('typeahead:selected', function(obj, datum){
            window.location.href = datum.url;
        });
    </script>

    <script>
        $('.typeahead').keydown(function(key) {
            if (key.keyCode == 13) {
                window.location.href = '{{url('/search')}}' + '?search_query=' + $(this).val();
            }
        });
    </script>

</body>

</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>
            Todo Task
        </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }
        .flex-center {
            display: flex;
        }

        .position-ref {
            position: relative;
        }
        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        .sidebar-left{
                padding: 50px 0;
                background: #eee;
                height: 500px;
        }
        pre {
            color: white;
            background-color: #2f383d;
            overflow-x: auto;
        }
        pre div.hljs {
            background-color: #2f383d;
            white-space: pre;
            padding: 15px;
            color: #fff;
        }
        </style>
    </head>
    <body>

        <div class="flex-center position-ref full-height">
            <div class="col-sm-2">
                <div class="sidebar-left">
                    <ul>
                        <li>Exercise 1</li>
                        <ul>
                            <li> <a href="/todo">CRUD</a></li>
                            <li> <a href="/command">Command</a></li>
                            <li> <a href="/geometric-shapes">Shape</a></li>
                        </ul>
                        <li>Exercise 2</li>
                        <ul>
                            <li>Question1</li>
                            <li>Question2</li>
                        </ul>
                        <li>Exercise 3</li>
                        <ul>
                            <li><a href="/restapi">API</a></li>
                        </ul>
                    </ul>
                </div>
            </div>
            <div class="col-sm-9">
                @yield('content')
            </div>
        </div>
        <script type="text/javascript">
        function formatMAC(e) {
            var r = /([a-f0-9]{2})([a-f0-9]{2})/i,
                str = e.target.value.replace(/[^a-f0-9]/ig, "");
            
            while (r.test(str)) {
                str = str.replace(r, '$1' + ':' + '$2');
            }

            e.target.value = str.slice(0, 17);
        };

        $('#mac_address').keydown(formatMAC);
        </script>
        @yield('scripts')  

    </body>
</html>

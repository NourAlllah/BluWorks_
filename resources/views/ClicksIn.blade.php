<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>BluWorks Clock In</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/tailwindcss.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/clicksIn.css') }}">
</head>
<body>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white" style="display: flex;flex-direction: column;justify-content: flex-start;">

            <div class="contain">
                <h1 class="greetings">Hi, {{ $worker->name }}</h1>
                <div class="buttons_container">
                    <div>
                        <form action="/api/worker/clock-in" method="post" id="clock_in_form" >
                            @csrf
                            <button type="submit" class="button_action">Clock In </button>
                        </form>
                    </div>
                    <div>
                        <form action="/api/worker/clock-ins" method="get" id="worker_clock_ins_history">
                            @csrf
                
                            <button type="submit" class="button_action">My history</button>
                        </form>
                    </div>
                </div>
            </div>
        
            <div id="history">
                <table id="clockInsTable" style="display: none" >
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Hour</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            
        </div>
      
    </body>

    <script src="{{ asset('js/clicksIn.js') }}"></script>

</body>
</html>

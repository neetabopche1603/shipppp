<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Shipment</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <!-- Styles -->
    
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" >

        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
        <!-- Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
        <!-- Material Design Bootstrap -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
        <style>
            .back 
            {
                font-family: 'Nunito', sans-serif;
                background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../image/back.jpg');
                background-size: cover;  
            }

            .hit-the-floor {
                color: #fff;
                /* font-size: 12em; */
                font-weight: bold;
                font-family: Helvetica;
                text-shadow: 
                0 1px 0 #ccc, 
                0 2px 0 #c9c9c9, 
                0 3px 0 #bbb, 
                0 4px 0 #b9b9b9, 
                0 5px 0 #aaa, 
                0 6px 1px rgba(0,0,0,.1), 
                0 0 5px rgba(0,0,0,.1), 
                0 1px 3px rgba(0,0,0,.3), 
                0 3px 5px rgba(0,0,0,.2), 
                0 5px 10px rgba(0,0,0,.25), 
                0 10px 10px rgba(0,0,0,.2), 
                0 20px 20px rgba(0,0,0,.15);
            }
            .area {
                text-align: center;
                font-size: 6.5em;
                color: #fff;
                letter-spacing: -7px;
                font-weight: 700;
                text-transform: uppercase;
                animation: blur .75s ease-out infinite;
                text-shadow: 0px 0px 5px #fff, 0px 0px 7px #fff;
            }
            
            @keyframes blur {
                from {
                text-shadow:0px 0px 10px #fff,
                    0px 0px 10px #fff, 
                    0px 0px 25px #fff,
                    0px 0px 25px #fff,
                    0px 0px 25px #fff,
                    0px 0px 25px #fff,
                    0px 0px 25px #fff,
                    0px 0px 25px #fff,
                    0px 0px 50px #fff,
                    0px 0px 50px #fff,
                    0px 0px 50px #7B96B8,
                    0px 0px 150px #7B96B8,
                    0px 10px 100px #7B96B8,
                    0px 10px 100px #7B96B8,
                    0px 10px 100px #7B96B8,
                    0px 10px 100px #7B96B8,
                    0px -10px 100px #7B96B8,
                    0px -10px 100px #7B96B8;
                }
            }
                        *,
            *:before,
            *:after{
                padding: 0;
                margin: 0;
                box-sizing: border-box;
            }
            /* body{
                background-color: #080710;
            } */
            .background{
                width: 430px;
                height: 520px;
                position: absolute;
                transform: translate(-50%,-50%);
                left: 50%;
                top: 50%;
            }
            .background .shape{
                height: 200px;
                width: 200px;
                position: absolute;
                border-radius: 50%;
            }
            .shape:first-child{
                background: linear-gradient(
                    #1845ad,
                    #23a2f6
                );
                position: relative;
                left: 14%;
                top: 0px;
            }
            .shape:last-child{
                background: linear-gradient(
                    to right,
                    #ff512f,
                    #f09819
                );
                position: relative;
                right: -106%;
                bottom: -63%;
            }
            form{
                height: 520px;
                width: 400px;
                background-color: rgba(255,255,255,0.13);
                position: relative;
                transform: translate(-50%,-50%);
                top: 70%;
                left: 70%;
                border-radius: 10px;
                backdrop-filter: blur(10px);
                border: 2px solid rgba(255,255,255,0.1);
                box-shadow: 0 0 40px rgba(8,7,16,0.6);
                padding: 50px 35px;
            }
            form *{
                font-family: 'Poppins',sans-serif;
                color: #ffffff;
                letter-spacing: 0.5px;
                outline: none;
                border: none;
            }
            form h3{
                font-size: 32px;
                font-weight: 500;
                line-height: 42px;
                text-align: center;
            }

            label{
                display: block;
                margin-top: 20px;
                font-size: 16px;
                font-weight: 500;
            }
            input{
                display: block;
                height: 50px;
                width: 100%;
                background-color: rgba(255,255,255,0.07);
                border-radius: 3px;
                padding: 0 10px;
                margin-top: 8px;
                font-size: 14px;
                font-weight: 300;
            }
            ::placeholder{
                color: #e5e5e5;
            }
            button{
                margin-top: 50px;
                width: 100%;
                background-color: #ffffff;
                color: #080710;
                padding: 15px 0;
                font-size: 18px;
                font-weight: 600;
                border-radius: 5px;
                cursor: pointer;
            }
            .social{
            margin-top: 30px;
            display: flex;
            }
            .social div{
            background: red;
            width: 150px;
            border-radius: 3px;
            padding: 5px 10px 10px 5px;
            background-color: rgba(255,255,255,1.27);
            color: black;
            text-align: center;
            }
            .social div:hover{
            background-color: rgba(255,255,255,0.47);
            }
            .social .fb{
            margin-left: 25px;
            }
            .social i{
            margin-right: 4px;
            }


        </style>

    </head>
    <body class="back">
        <!-- <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0"> -->

                <div class="d-flex justify-content-center">
                    <h4 class="area">SHIPMENT</h4>
                </div>

                <!-- <div class="container mt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card rounded">
                                <div class="card-header">{{ __('Login') }}</div>

                                <div class="card-body">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <div class="form-group row">
                                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                            <div class="col-md-6">
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                            <div class="col-md-6">
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6 offset-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="remember">
                                                        {{ __('Remember Me') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-0">
                                            <div class="col-md-8 offset-md-4">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Login') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   -->

                <div class="container" style="height:95.8vh;">
                    <div class="row py-5">
                        <div class="col-md-8">
                            <div class="background">
                                <div class="shape"></div>
                                <div class="shape"></div>
                            </div>
                            <form method="POST" class="pt-5" action="{{ route('login') }}">
                                @csrf
                                <h3>Login Here</h3>

                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Email" id="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                <label for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password" id="password">

                                <button type="submit" class="btn btn-primary mt-5" style="margin-left:-1px;"">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
             

            <!-- <div class="container mt-5">
                <div class="row d-flex pt-5 justify-content-center">
                    <div class="col-md-4">
                        <h3 class="text-center hit-the-floor">Login</h3>
                        <div class="col-12">
                            @if (Route::has('login'))
                                <div class="row d-flex justify-content-center">
                                    @auth
                                            <div class=" text-center">
                                            <a href="{{ url('/home') }}" class="btn btn-info hidden ">Home</a>
                                            </div>
                                    @else
                                            <div class=" text-center">
                                            <a href="{{  route('login') }}" class="btn btn-info hidden ">Login</a>
                                            </div>
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>          

                </div>
            </div> -->

               
        <!-- </div> -->

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>

        <script>
                function AddClass() {
                    var delay = 1000; // milliseconds
                    var delay1 = 1000;
                    var delay2 = 1000;
                    var delay3 = 1000;
                    var delay4 = 1000;
                    var delay5 = 1000;
                    setTimeout(function() {
                        $('.head2').addClass('animated slideInLeft');
                        $('.head2').removeClass('hidden');
                        AddClass();
                    }, delay);
                    setTimeout(function() {
                        $('.head3').addClass('animated slideInLeft');
                        $('.head3').removeClass('hidden');
                        AddClass();
                    }, delay1);
                    setTimeout(function() {
                        $('.head4').addClass('animated slideInLeft');
                        $('.head4').removeClass('hidden');
                        AddClass();
                    }, delay2);
                    setTimeout(function() {
                        $('.head5').addClass('animated slideInLeft');
                        $('.head5').removeClass('hidden');
                        AddClass();
                    }, delay3);
                    setTimeout(function() {
                        $('.head6').addClass('animated slideInLeft');
                        $('.head6').removeClass('hidden');
                        AddClass();
                    }, delay4);
                    setTimeout(function() {
                        $('.btn').addClass('animated fadeIn');
                        $('.btn').removeClass('hidden');
                        AddClass();
                    }, delay5);
                };
                AddClass();
        </script>
    </body>
</html>

{{--
  @yield('title')标题
  @yield('content')主体
  @yield('footer')底部导航
--}}
<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> Iron Man-@yield('title')</title>
    <link rel="shortcut icon" href="images/favicon.ico" />
    
    <!-- Bootstrap -->
    <link href="{{asset('shop/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/response.css')}}" rel="stylesheet">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('shop/js/jquery.min.js')}}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('shop/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('shop/js/style.js')}}"></script>
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <!--焦点轮换-->
    <script src="{{asset('shop/js/jquery.excoloSlider.js')}}"></script>
    
    <script src="{{asset('shop/layui/layui.js')}}"></script>
    <script src="{{asset('shop/js/jquery.spinner.js')}}"></script>
    
    <!-- Bootstrap -->
    
   
   
    {{--     <link href="{{ asset('shop/css/style1.css') }}" rel="stylesheet">--}}
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    </script>
  </head>
  <body>
    <div class="maincont">
    @yield('content')
    @yield('footer')


     
    </div><!--maincont-->

    <!--焦点轮换-->
    <script>
        $(function () {
         $("#sliderA").excoloSlider();
        });
    </script>
  <!--jq加减-->
   <script>
    $('.spinnerExample').spinner({});
    </script>
  </body>
</html>
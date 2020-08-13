<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Budget Webform</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        
        <!--<link rel="stylesheet" href="{{asset("node_modules/bootstrap/dist/css/bootstrap.min.css")}}">-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" type="text/css" />
        
        <!-- Font Awesome -->
        <!--<link rel="stylesheet" href="{{asset("node_modules/font-awesome/css/font-awesome.min.css")}}">-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- iCheck for checkboxes and radio inputs -->
        <!--<link rel="stylesheet" href="{{asset("node_modules/admin-lte/plugins/iCheck/all.css")}}">
        <script src="{{asset("node_modules/admin-lte/plugins/iCheck/icheck.min.js")}}"></script>-->
                        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" type="text/css" />
        <style type="text/css">
            .multiselect.dropdown-toggle {
                text-align: left;
                height: 30px;
            }
        </style>
        
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset("admin-lte/dist/css/AdminLTE.min.css")}}">
        
        <style type="text/css">
            .sidebar-mini.sidebar-collapse .main-header .navbar {
                margin-left: 0px;
            }            
            .sidebar-mini.sidebar-collapse .main-sidebar {       
                width: 0px !important;
            }
            .sidebar-mini.sidebar-collapse .main-footer {
                margin-left: 0px !important;              
            }
        </style>
        
        <style type="text/css">
            .project-layout {
                margin-right: 20px
            }
            
            .project-button {
                width: 160px
            }
            
            .block-background-color {
                padding: 10px;
                /*border: 1px solid #333333;*/
                width: 1020px;
                height: 260px;
                background-color: #f6fafd;
            }
            
            .font-bold {
                font-weight: bold;
            }
            
            .border-top-style-list {
                border-top: solid 1px lightgray;
            }
            
            .border-bottom-style-list {
                border-bottom: solid 1px lightgray;
            }
            
            .project-font-size {
                font-size: 14px;
            }
           
        </style>
            
        
        <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
              page. However, you can choose any other skin. Make sure you
              apply the skin class to the body tag so the changes take effect. -->
        <link rel="stylesheet" href="{{asset("admin-lte/dist/css/skins/skin-blue.min.css")}}">
        <link rel="stylesheet" href="{{asset("admin-lte/dist/css/skins/skin-green.min.css")}}">

        <!--<script src="https://bossanova.uk/jexcel/v4/jexcel.js"></script>
        <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
        <link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
        <link rel="stylesheet" href="https://bossanova.uk/jexcel/v4/jexcel.css" type="text/css" />-->
        
        <script src="https://jexcel.net/v5/jexcel.js"></script>
        <script src="https://jexcel.net/v5/jsuites.js"></script>
        <link rel="stylesheet" href="https://jexcel.net/v5/jsuites.css" type="text/css" />
        <link rel="stylesheet" href="https://jexcel.net/v5/jexcel.css" type="text/css" />

        <style type="text/css">
            .jexcel_content::-webkit-scrollbar {
                width: 20px;
                height: 12px;
            }
        </style>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Google Font -->
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        <script type="text/javascript">
            var backgroundColorError = "#ff7f7f";
        </script>


        <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
        
        <!-- jQuery UI -->
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

        <!--moment.js-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js" type="text/javascript"></script>
        <!-- Bootstrap-datepicker -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.ja.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
        
    </head>
    <!--
    BODY TAG OPTIONS:
    =================
    Apply one or more of the following classes to get the
    desired effect
    |---------------------------------------------------------|
    | SKINS         | skin-blue                               |
    |               | skin-black                              |
    |               | skin-purple                             |
    |               | skin-yellow                             |
    |               | skin-red                                |
    |               | skin-green                              |
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
    -->
    <body class="hold-transition skin-green sidebar-mini sidebar-collapse"> <!--style変更　 style="font-family: Segoe UI"-->
        <div class="wrapper">
            <header class="main-header">
                <!-- ロゴ -->
                <a href="{{ action('HomeController@index') }}"><img src="{{asset("image/TOPC_logo.png")}}" class="logo"></a>

                <!-- トップメニュー -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <ul class="nav navbar-nav">
                        <li><a style="font-size: 20px;width: 200px" href="">Budget Webform</a></li>
                        <li @if(Request::decodedPath() == "budget/enter") class="active" @endif><a href="{{asset("budget/enter")}}">Entry</a></li>
                        <li @if(Request::decodedPath() == "budget/show") class="active" @endif><a href="{{asset("budget/show")}}">Report</a></li>                        
                        <!--<li @if(substr(Request::decodedPath(),0,6) == "master") class="dropdown active" @else class="dropdown" @endif>
                            <a href="" data-toggle="dropdown" class="dropdown-toggle">Master<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li @if(Request::decodedPath() == "master/project") class="active" @endif><a href="{{asset("master/project")}}">Project</a></li>                                
                                <li><a href="xxx">Client</a></li>                                
                            </ul>
                        </li>-->
                    </ul>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li style="width: 200px"><a class="dropdown-item">{{Session::get('user')}}</a></li>           
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header><!-- end header -->


            <!-- サイドバー -->
            <!--<div style="position:fixed;left:0;top:0;">-->
            <aside class="main-sidebar">
                <section class="sidebar">
                    <ul class="sidebar-menu">
                        <!-- メニューヘッダ -->
                          <!-- メニュー項目 -->  
                        <!--予算入力-->
                        <li style="font-weight: bold" @if(Request::decodedPath() == "enter") class="active" @endif><a onclick="return movePageControl();" href="{{asset("budget/enter")}}" @if(isset($navigation_status[0]["intro"]) && $navigation_status[0]["intro"])  style="font-weight: bold;color:#292939"  @endif>&nbsp;予算入力</a></li>
                        <!--予算照会-->
                        <li style="font-weight: bold" @if(Request::decodedPath() == "show") class="active" @endif><a onclick="return movePageControl();" href="{{asset("budget/show")}}" @if(isset($navigation_status[0]["personal_info"]) && $navigation_status[0]["personal_info"]) style="font-weight: bold;color:#292939" @endif>&nbsp;予算照会</a></li>
                        <!--プロジェクトマスタ-->
                        <li style="font-weight: bold" @if(Request::decodedPath() == "project") class="active" @endif><a onclick="return movePageControl();" href="{{asset("master/project")}}" @if(isset($navigation_status[0]["personal_info"]) && $navigation_status[0]["personal_info"]) style="font-weight: bold;color:#292939" @endif>&nbsp;Projectマスタ</a></li>
                        
                    </ul>
                    
                </section>
            </aside><!-- end sidebar -->
            <!--</div>-->
            <!-- content -->
            <div class="content-wrapper" style="background-color: white">
                <!--Form変更監視-->
                <input type="hidden" id="isInputFieldChanged" value="false">
                @yield('content')

            </div><!-- end content -->
            
            
            <!--<footer class="main-footer">
                <div class="pull-right hidden-xs">Version1.0</div>
                <strong> </strong>
            </footer>--><!-- end footer -->



        </div><!-- end wrapper -->

        <!-- AdminLTE App -->
        <script src="{{asset("admin-lte/dist/js/adminlte.min.js")}}"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <!--decimal.js-->
        <script type="text/javascript" src="{{ asset('js/decimal.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>


    </body>

</html>
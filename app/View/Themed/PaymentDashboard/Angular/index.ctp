<!DOCTYPE html>
<html lang="en" ng-app="vntapAuth">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment SDK</title>
    <?php

        echo $this->Html->css('/css/bootstrap.min.css');
        echo $this->Html->css('/css/font-awesome-4.7.0/css/font-awesome.min.css');
        echo $this->element('call_app_func');

    ?>
    <script>
        var BASE_URL = '<?php echo Router::url("/", true); ?>';
        var APP_URL = '<?php echo Router::url("/", true); ?>angular';
        var APP_KEY = '<?php echo $this->request->header('app') ?>';
        var APP_VERSION = '<?php echo $this->request->header('SDK_VERSION') ?>';
        var GAME_VERSION = '<?php echo $this->request->header('GAME_VERSION') ?>';
    </script>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&ampsubset=vietnamese" rel="stylesheet">
    <style type="text/css">
		* {
			font-family: 'Roboto Condensed', sans-serif;
		}
		body {
			background: #EEEEEE;
		}

		a:hover {
			text-decoration: none;
		}

		.form-control,
		.input-group-addon {
			border: #E3E3E3 1px solid;
			border-radius: 0;
			-webkit-appearance: none;
		}

		.form-group {
			margin-bottom: 10px;
		}

		.input-group-addon {
			background: #DADADA;
			color: #B4B4B4;
		}

		.btn {
			border-radius: 0;
			-webkit-appearance: none;
		}

		.btn-primary,
		.btn-primary:hover,
		.btn-primary:active,
		.btn-primary:visited,
		.btn-primary:focus {
			background-color: #00B0EB;
			border: none;
		}

		.btn-warning {
			background: #ff7e00;
			border: none;
		}

		.app-login {
			background: #fff;
			position: absolute;
			left: 50%;
			top: 50%;
			-webkit-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
			min-width: 300px;
		}

		.app-header {
			color: #26b6ec;
			font-size: 12.5pt;
			font-weight: bold;
		}

		.app-title {
			margin-top: 10px;
			text-align: center;
		}

		.btn-back a {
			color: #00B0EB;
			position: absolute;
			float: left;
			padding-left: 25px;
		}

		.app-body {
			margin: 0 25px;
		}

		.app-helper a {
			color: #014258;
			font-size: 12px;
			text-decoration: underline;
			margin-bottom: 0;
		}

		.app-plugins {
			margin-top: 5px;
		}

		.app-ext {
			margin-bottom: 10px;
		}

		.app-ext a {
			color: #FFFFFF;
			font-weight: bold;
		}

		.app-info {
			color: #DADADA;
			font-size: 10px;
			margin-top: 10px;
			margin-left: 25px;
			margin-right: 25px;
		}

		.app-or {
			position: relative;
			font-size: 12px;
			color: #000;
			margin-top: 10px;
			margin-bottom: 10px;
			padding-top: 5px;
			padding-bottom: 5px;
		}

		.hr-or {
			background: #000;
			height: 0.1px;
			margin-top: 0px !important;
			margin-bottom: 0px !important;
		}

		.span-or {
			display: block;
			position: absolute;
			left: 50%;
			top: -1px;
			margin-left: -25px;
			background-color: #fff;
			width: 50px;
			text-align: center;
		}

		.btn-facebook {
			background: #3c5a9a;
			border: none;
		}

		.btn-google {
			background: #d44837;
			border: none;
		}

		.btn-facebook,
		.btn-google {
			width: 120px;
			height: 30px;
			line-height: 20px;
			margin-bottom: 0;
		}

		.btn-facebook a,
		.btn-google a {
			color: #FFFFFF;
			font-weight: bold;
			padding: 0 10px;
		}

		.text-right {
			float: right;
		}

		.clear-float {
			clear: both;
		}
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div ng-view></div>

    <?php
        echo $this->Html->script('/js/jquery/jquery-1.11.2.min.js');
        echo $this->Html->script('/js/angular/angular.min.js');
        echo $this->Html->script('/js/angular/angular-route.min.js');
        echo $this->Html->script('/js/bootstrap.min.js');
    ?>
    <script type="text/javascript">
        //Pace.track(function(){
            var vntapAuth = angular.module('vntapAuth', ['ngRoute']);

            // set header
            vntapAuth.config(['$httpProvider', function($httpProvider) {
                $httpProvider.defaults.headers.common['Sdk-Version'] = APP_VERSION;
                $httpProvider.defaults.headers.common['Game-Version'] = GAME_VERSION;
            }]);

            // Router
            vntapAuth.config(function($routeProvider) {
                $routeProvider
                .when('/', {
                    templateUrl: APP_URL + '/login?app=' + APP_KEY,
                    controller: 'loginController'
                })

                .when('/register', {
                    templateUrl: APP_URL + '/register?app=' + APP_KEY,
                    controller: 'registerController'
                })

                .when('/maintain', {
                    templateUrl: APP_URL + '/maintain?app=' + APP_KEY,
                    controller: 'maintainController'
                })

                .otherwise({redirectTo:'/'});
            });

            // Register Controller
            vntapAuth.controller('loginController', ['$scope', '$http', function($scope, $http) {
                $scope.title = 'ĐĂNG NHẬP';
                $scope.sdk_version  = APP_VERSION;
                $scope.game_version = GAME_VERSION;

                $scope.formData = {};

                $scope.processForm = function() {
                    $http({
                        method: 'POST',
                        url: BASE_URL + '/api/users/login_v26.json?app=' + APP_KEY,
                        data: $.param($scope.formData),
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function(data) {
                        // maintain
                        if(data.retcode == 99){
                            // maintain
                            if(APP_KEY == '647f2395559c3ba04c162219451bfc2b' && GAME_VERSION == '1.8'){
                                window.location.href = "#/maintain/";
                            }else {
                                AppSDKexecute('SDKMaintain');
                            }
                        }else if (data.retcode != 0) {
                            AppSDKexecute('SDKErrorMessage', data.message);
                        } else {
                            AppSDKexecute('SDKLoginSuccess', data.data);
                        }
                    });
                };
            }]);

            vntapAuth.controller('registerController', ['$scope', '$http', function($scope, $http) {
                $scope.title = 'ĐĂNG KÝ';
                $scope.sdk_version  = APP_VERSION;
                $scope.game_version = GAME_VERSION;

                $scope.formData = {};

                $scope.processForm = function() {
                    $http({
                        method: 'POST',
                        url: BASE_URL + 'api/users/register_v26.json?app=' + APP_KEY,
                        data: $.param($scope.formData),
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function(data) {
                        // maintain
                        if(data.status == 99){
                            // maintain
                            AppSDKexecute('SDKMaintain');
                        }else if (data.status != 0) {
                            AppSDKexecute('SDKErrorMessage', data.message);
                        } else {
                            AppSDKexecute('SDKLoginSuccess', data.data);
                        }
                    });
                };
            }]);

            vntapAuth.controller('maintainController', ['$scope', function($scope) {
                $scope.title = 'Bảo trì';
            }]);
        //});
    </script>
</body>

</html>

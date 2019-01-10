<!DOCTYPE html>
<html lang="en" ng-app="vntAuth">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SDK</title>

    <?php
    echo $this->Html->css('/payment/css/bootstrap.min.css');
    echo $this->Html->css('/payment/css/font-awesome.min.css');
    echo $this->Html->css('/payment/css/bootstrap-theme.css');
    echo $this->Html->css('/css/angular.css');
    ?>
</head>

<body>
    <div class="overlay"></div>
    <div ng-view></div>

    <?php
    echo $this->Html->script('/js/jquery-2.0.0.min.js');
    echo $this->Html->script('/js/bootstrap.min.js');
    echo $this->Html->script('/js/angular/angular.min.js');
    echo $this->Html->script('/js/angular/angular-route.min.js');
    ?>

    <script type="text/javascript">
        var APP_URL = '<?php echo Router::url("/", true); ?>';
        var APP_KEY = '<?php echo $this->request->header('app') ?>';
        var TOKEN = '<?php echo $this->request->header('token') ?>';

        var vntAuth = angular.module('vntAuth', ['ngRoute']);
        vntAuth.config(function($routeProvider) {
            $routeProvider
                .when('/', {
                    templateUrl: APP_URL + 'BankmanualPayments/profile?app=' + APP_KEY + '&token=' + TOKEN,
                    controller: 'profileController'
                })

                .when('/detail/:id', {
                    templateUrl: function(params) {
                        return APP_URL + 'BankmanualPayments/detail?app=' + APP_KEY + '&token=' + TOKEN + '&productId=' + params.id ;
                    },
                    controller: 'detailController'
                })

                .otherwise({redirectTo:'/'});
        });

        vntAuth.controller('profileController', ['$scope', '$http', function($scope, $http) {
            $scope.message = "";

            $scope.formData = {};

            $scope.processForm = function() {
                $http({
                    method: 'POST',
                    url: APP_URL + 'BankmanualPayments/orders?app=' + APP_KEY + '&token=' + TOKEN,
                    data: $.param($scope.formData),
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                    .success(function(data) {
                        window.location.href= "#/detail/<?php echo $this->request->query("productId"); ?>";
                    });
            };
        }]);

        vntAuth.controller('detailController', ['$scope', '$http', function($scope, $http) {
            $scope.title = 'Tạo giao dịch';
            $scope.message = "";

            $scope.formData = {};
        }]);
    </script>
</body>
</html>

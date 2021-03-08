angular.module('starter', ['ionic', 'starter.controllers'])

.run(function($ionicPlatform, $ionicSideMenuDelegate) {
  $ionicPlatform.ready(function() {
    if (window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
    }
    if (window.StatusBar) {
      StatusBar.styleDefault();
    }
  });
})

.config(function($stateProvider, $urlRouterProvider) {
  $stateProvider

    .state('app', {
      url: "/app",
      abstract: true,
      templateUrl: "menu.html"
    })

    .state('app.home', {
      url: '/home',
      views: {
        'menuContent': {
          templateUrl: "home.html",
          controller: 'homeController'
        }
      }
    });

  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/app/home');
});
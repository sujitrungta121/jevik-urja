
var app = angular.module('myApp', []);
app.controller('Ctrl', function($scope, $http) {
$scope.details = [];
$scope.brands=[];
$scope.currentOptionVals=[];
$scope.banners=[];
    $http.get(baseUrl+"Api/dashboard").then(function (response) {
		//alert(response);
		console.log(JSON.stringify(response.data));
		$scope.details = response.data.details;
		$scope.brands=response.data.brands;
		$scope.banners=response.data.banners;
	});

	$scope.changeSelectedOptionId=function(id){
		alert("dfasdfasdd");
		console.log("Current Id : "+id);
	}

	setTimeout(function(){ $scope.getProductSlider(); }, 3000);

    
	$scope.getProductSlider=function(){
		var owl = $('.item-slide3');
	    owl.owlCarousel({
	      items:4,
	      loop:true,
	      nav:true,
	      margin: 10,
	      dots:false,
	      autoplay:false,
	      navText: ["<i class='ti-angle-left'></i>", "<i class='ti-angle-right'></i>"],
	      responsive: {
	        0: {
	          items: 1
	        }
	        , 480: {
	          items: 2
	        }
	        , 768: {
	          items: 2
	        }
	        , 1200: {
	          items: 4
	        }
	      }
	    })
	}
	
});

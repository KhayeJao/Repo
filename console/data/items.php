<?php
return [
    'manageUsers' => [
        'type' => 2,
        'description' => 'Manage Users',
    ],
    'logisticsUsers' => [
        'type' => 2,
        'description' => 'Manage Logistic Users',
    ],
	'manageMenu' => [
        'type' => 2,
        'description' => 'Manage Menu',
    ],
	'manageDish' => [
        'type' => 2,
        'description' => 'Manage Dish',
    ],
	 'homeSlider' => [
        'type' => 2,
        'description' => 'Home Slider',
    ],
	
    'editUserProfile' => [
        'type' => 2,
        'description' => 'Edit User Profile',
    ],
    'createRestaurant' => [
        'type' => 2,
        'description' => 'Create Restaurant',
    ],
	
	 'selectRestaurant' => [
        'type' => 2,
        'description' => 'Select Restaurant',
    ],
    'editRestaurant' => [
        'type' => 2,
        'description' => 'Edit Restaurant',
    ],
    'viewRestaurantList' => [
        'type' => 2,
        'description' => 'View Restaurant list',
    ],
    'viewUserList' => [
        'type' => 2,
        'description' => 'View User list',
    ],
    'placeOrder' => [
        'type' => 2,
        'description' => 'Place New Order',
    ],
    'viewOrder' => [
        'type' => 2,
        'description' => 'View Order',
    ],
    'placeTableOrder' => [
        'type' => 2,
        'description' => 'Book New Table',
    ],
	'callCenter' => [
        'type' => 2,
        'description' => 'Call Center',
    ],
    'viewTableOrder' => [
        'type' => 2,
        'description' => 'View Table Order',
    ],
    'manageCuisine' => [
        'type' => 2,
        'description' => 'Manage Cuisine',
    ],
	 'manageTrending' => [
        'type' => 2,
        'description' => 'Manage Trending',
    ],
    'manageService' => [
        'type' => 2,
        'description' => 'Manage Service',
    ],
    'manageLocation' => [
        'type' => 2,
        'description' => 'Manage Location',
    ],
    'manageCoupons' => [
        'type' => 2,
        'description' => 'Manage Coupons',
    ],
    'manageArea' => [
        'type' => 2,
        'description' => 'Manage Area',
    ],
    'manageSiteSetting' => [
        'type' => 2,
        'description' => 'Manage Site settings',
    ],
    'manageSiteContent' => [
        'type' => 2,
        'description' => 'Manage Site content',
    ],
    'manageRestaurantDishes' => [
        'type' => 2,
        'description' => 'Manage Dishes of restaurant',
    ],
    'manageRestaurantMenu' => [
        'type' => 2,
        'description' => 'Manage Menu of restaurant',
    ],
    'manageRestaurantService' => [
        'type' => 2,
        'description' => 'Manage Service of restaurant',
    ],
    'manageRestaurantCoupons' => [
        'type' => 2,
        'description' => 'Manage Coupon of restaurant',
    ],
    'manageRestaurantCuisine' => [
        'type' => 2,
        'description' => 'Manage Cuisine of restaurant',
    ],
    'manageRestaurantGallery' => [
        'type' => 2,
        'description' => 'Manage Gallery of restaurant',
    ],
    'manageRestaurantArea' => [
        'type' => 2,
        'description' => 'Manage Area of restaurant',
    ],
    'manageRestaurantCombo' => [
        'type' => 2,
        'description' => 'Manage Combo of restaurant', 
    ],
    'manageRestaurantDishTopping' => [
        'type' => 2,
        'description' => 'Manage Dish topping of restaurant',
    ],
    'manageRestaurantPhone' => [
        'type' => 2,
        'description' => 'Manage phone of restaurant',
    ],
    'manageRestaurantTables' => [
        'type' => 2,
        'description' => 'Manage Table of restaurant',
    ],
    'seeFavDish' => [
        'type' => 2,
        'description' => 'See favourite dish',
    ],
    'smsTemplate' => [
        'type' => 2,
        'description' => 'Sms Template',
    ],
    'groupSms' => [
        'type' => 2,
        'description' => 'Group Sms',
    ],
    'TakeOrder' => [
        'type' => 2,
        'description' => 'Take Order',
    ],
    'DeliveryBoy' => [
        'type' => 2,
        'description' => 'Delivery Boy',
    ],
	
    'telecaller' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'placeOrder',
            'placeTableOrder',
            'viewUserList',
			'callCenter',
            'editUserProfile',
            'manageCoupons',
            'groupSms', 
            'DeliveryBoy', 
            
        ],
    ],
    'restaurant' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'editRestaurant',
            'viewOrder',
            'viewTableOrder',
            'manageRestaurantDishes',
            'logisticsUsers',
            'manageRestaurantMenu',
            'manageRestaurantService',
            'manageRestaurantCoupons',
            'manageRestaurantCuisine',
            'manageRestaurantGallery',
            'manageRestaurantArea',
            'manageRestaurantCombo',
            'manageRestaurantDishTopping',
            'manageRestaurantPhone',
            'manageRestaurantTables',
            'TakeOrder',            'DeliveryBoy',
            
        ],
    ],
    'admin' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'restaurant',
            'telecaller',
            'manageUsers',
            'createRestaurant',
			'selectRestaurant',
            'manageCoupons',
            'manageCuisine',
			'manageTrending',
            'manageService',
            'manageLocation',
            'manageArea',
            'manageSiteSetting',
            'seeFavDish',
            'manageSiteContent',
            'manageCoupons',
            'placeOrder',
            'smsTemplate',
            'groupSms',
            'DeliveryBoy',
			'manageMenu',
			'manageDish',
			'homeSlider',
        ],
    ],
];

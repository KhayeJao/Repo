<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\rbac\UserRoleRule;
use common\rbac\RestaurantRoleRule;

class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;
        $auth->removeAll(); //remove previous rbac.php files under console/data
        //CREATE PERMISSIONS		
        //Permission to create users
        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Manage Users';
        $auth->add($manageUsers);
        
        //Permission to edit user profile
        $editUserProfile = $auth->createPermission('editUserProfile');
        $editUserProfile->description = 'Edit User Profile';
        $auth->add($editUserProfile);
        
        //Permission to create restaurant
        $createRestaurant = $auth->createPermission('createRestaurant');
        $createRestaurant->description = 'Create Restaurant';
        $auth->add($createRestaurant);
        
        //Permission to edit restaurant
        $editRestaurant = $auth->createPermission('editRestaurant');
        $editRestaurant->description = 'Edit Restaurant';
        $auth->add($editRestaurant);
        
        //Permission to view restaurant list
        $viewRestaurantList = $auth->createPermission('viewRestaurantList');
        $viewRestaurantList->description = 'View Restaurant list';
        $auth->add($viewRestaurantList);
        
        //Permission to view restaurant list
        $viewUserList = $auth->createPermission('viewUserList');
        $viewUserList->description = 'View User list';
        $auth->add($viewUserList);
        
        
        //Permission to place order
        $placeOrder = $auth->createPermission('placeOrder');
        $placeOrder->description = 'Place New Order';
        $auth->add($placeOrder);
        
        //Permission to view order
        $viewOrder = $auth->createPermission('viewOrder');
        $viewOrder->description = 'View Order';
        $auth->add($viewOrder);
        
        
        //Permission to book table
        $placeTableOrder = $auth->createPermission('placeTableOrder');
        $placeTableOrder->description = 'Book New Table';
        $auth->add($placeTableOrder);
        
        //Permission to view order
        $viewTableOrder = $auth->createPermission('viewTableOrder');
        $viewTableOrder->description = 'View Table Order';
        $auth->add($viewTableOrder);
        
        //Permission to manage cuisines
        $manageCuisine = $auth->createPermission('manageCuisine');
        $manageCuisine->description = 'Manage Cuisine';
        $auth->add($manageCuisine);
        
        
        //Permission to manage service
        $manageService = $auth->createPermission('manageService');
        $manageService->description = 'Manage Service';
        $auth->add($manageService);
        
        //Permission to manage location
        $manageLocation = $auth->createPermission('manageLocation');
        $manageLocation->description = 'Manage Location';
        $auth->add($manageLocation);
        
        //Permission to manage Coupons
        $manageCoupons = $auth->createPermission('manageCoupons');
        $manageCoupons->description = 'Manage Coupons';
        $auth->add($manageCoupons);
        
        //Permission to manage Area
        $manageArea = $auth->createPermission('manageArea');
        $manageArea->description = 'Manage Area';
        $auth->add($manageArea);
        
        //Permission to manage site setting
        $manageSiteSetting = $auth->createPermission('manageSiteSetting');
        $manageSiteSetting->description = 'Manage Site settings';
        $auth->add($manageSiteSetting);
        
        //Permission to manage site content
        $manageSiteContent = $auth->createPermission('manageSiteContent');
        $manageSiteContent->description = 'Manage Site content';
        $auth->add($manageSiteContent);
        
        
        //Permission to manage restaurant dishes
        $manageRestaurantDishes = $auth->createPermission('manageRestaurantDishes');
        $manageRestaurantDishes->description = 'Manage Dishes of restaurant';
        $auth->add($manageRestaurantDishes);
        
        
        //Permission to manage restaurant menus
        $manageRestaurantMenu = $auth->createPermission('manageRestaurantMenu');
        $manageRestaurantMenu->description = 'Manage Menu of restaurant';
        $auth->add($manageRestaurantMenu);
        
        //Permission to manage restaurant Service
        $manageRestaurantService = $auth->createPermission('manageRestaurantService');
        $manageRestaurantService->description = 'Manage Service of restaurant';
        $auth->add($manageRestaurantService);
        
        //Permission to manage restaurant Coupons
        $manageRestaurantCoupons = $auth->createPermission('manageRestaurantCoupons');
        $manageRestaurantCoupons->description = 'Manage Coupon of restaurant';
        $auth->add($manageRestaurantCoupons);
        
        //Permission to manage restaurant Cuisine
        $manageRestaurantCuisine = $auth->createPermission('manageRestaurantCuisine');
        $manageRestaurantCuisine->description = 'Manage Cuisine of restaurant';
        $auth->add($manageRestaurantCuisine);
        
        //Permission to manage restaurant Gallery
        $manageRestaurantGallery = $auth->createPermission('manageRestaurantGallery');
        $manageRestaurantGallery->description = 'Manage Gallery of restaurant';
        $auth->add($manageRestaurantGallery);
        
        //Permission to manage restaurant Area
        $manageRestaurantArea = $auth->createPermission('manageRestaurantArea');
        $manageRestaurantArea->description = 'Manage Area of restaurant';
        $auth->add($manageRestaurantArea);
        
        //Permission to manage restaurant Combo
        $manageRestaurantCombo = $auth->createPermission('manageRestaurantCombo');
        $manageRestaurantCombo->description = 'Manage Combo of restaurant';
        $auth->add($manageRestaurantCombo);
        
        //Permission to manage restaurant Dish Topping
        $manageRestaurantDishTopping = $auth->createPermission('manageRestaurantDishTopping');
        $manageRestaurantDishTopping->description = 'Manage Dish topping of restaurant';
        $auth->add($manageRestaurantDishTopping);
        
        //Permission to manage restaurant phone
        $manageRestaurantPhone = $auth->createPermission('manageRestaurantPhone');
        $manageRestaurantPhone->description = 'Manage phone of restaurant';
        $auth->add($manageRestaurantPhone);
        
        //Permission to manage restaurant tables
        $manageRestaurantTables = $auth->createPermission('manageRestaurantTables');
        $manageRestaurantTables->description = 'Manage Table of restaurant';
        $auth->add($manageRestaurantTables);
        
        
        //Permission to see favourite dish
        $seeFavDish = $auth->createPermission('seeFavDish');
        $seeFavDish->description = 'See favourite dish';
        $auth->add($seeFavDish);

        //APPLY THE USERS RULE
        $rule = new UserRoleRule(); //Apply our Rule that use the user roles from user table
        $auth->add($rule);
        
        //ROLES AND PERMISSIONS
        //telecaller role
        $telecaller = $auth->createRole('telecaller');  //telecaller role
        $telecaller->ruleName = $rule->name;
        $auth->add($telecaller);
        // ... add permissions as children of $telecaller ...
        //PERMISSONS FOR TELECALLER STARTS
        $auth->addChild($telecaller, $placeOrder); //TELECALLER CAN PLACE ORDER
//        $auth->addChild($telecaller, $manageRestaurantCoupons); //TELECALLER CAN ADD COUPONS TO ANY RESTAURANT
        $auth->addChild($telecaller, $placeTableOrder); //TELECALLER CAN ADD BOOK TABLE
        $auth->addChild($telecaller, $viewUserList); //TELECALLER CAN VIEW USER'S LIST
        $auth->addChild($telecaller, $editUserProfile); //TELECALLER CAN EDIT HIS PROFILE
        $auth->addChild($telecaller, $manageCoupons); //TELECALLER CAN MANAGE COUPONS
        //PERMISSONS FOR TELECALLER ENDS
        
        
        
        
        //restaurant role
        $restaurant = $auth->createRole('restaurant');
        $restaurant->ruleName = $rule->name;
        $auth->add($restaurant);
        // ... add permissions as children of $restaurant ..
        //PERMISSONS FOR RETAURANT STARTS
        $auth->addChild($restaurant, $editRestaurant); //TELECALLER CAN PLACE ORDER
        $auth->addChild($restaurant, $viewOrder); //TELECALLER CAN ADD COUPONS TO ANY RESTAURANT
        $auth->addChild($restaurant, $viewTableOrder); //TELECALLER CAN ADD BOOK TABLE
        $auth->addChild($restaurant, $manageRestaurantDishes); //TELECALLER CAN MANAGE THEIR RESTAURANT DISHES
        $auth->addChild($restaurant, $manageRestaurantMenu); //TELECALLER CAN MANAGE THEIR RESTAURANT MENU
        $auth->addChild($restaurant, $manageRestaurantService); //TELECALLER CAN MANAGE THEIR SERVICE
        $auth->addChild($restaurant, $manageRestaurantCoupons); //TELECALLER CAN MANAGE THEIR COUPONS
        $auth->addChild($restaurant, $manageRestaurantCuisine); //TELECALLER CAN MANAGE THEIR CUISINES
        $auth->addChild($restaurant, $manageRestaurantGallery); //TELECALLER CAN MANAGE THEIR GELLERY
        $auth->addChild($restaurant, $manageRestaurantArea); //TELECALLER CAN MANAGE THEIR AREA
        $auth->addChild($restaurant, $manageRestaurantCombo); //TELECALLER CAN MANAGE THEIR COMBO
        $auth->addChild($restaurant, $manageRestaurantDishTopping); //TELECALLER CAN MANAGE THEIR TOPPINGS
        $auth->addChild($restaurant, $manageRestaurantPhone); //TELECALLER CAN MANAGE THEIR PHONE NOs
        $auth->addChild($restaurant, $manageRestaurantTables); //TELECALLER CAN MANAGE THEIR TABLES
        //PERMISSONS FOR RETAURANT ENDS
        //
        //Admin role
        $admin = $auth->createRole('admin');
        $admin->ruleName = $rule->name;
        $auth->add($admin);
        $auth->addChild($admin, $restaurant); //restaurant is child of admin
        $auth->addChild($admin, $telecaller); //telecaller is child of admin
        // ... add permissions as children of $admin ..
        $auth->addChild($admin, $manageUsers); //admin role can create users and also edit users because is parent of restaurant
        $auth->addChild($admin, $createRestaurant); //ADMIN CAN CREATE RESTAURANTS
        $auth->addChild($admin, $manageCuisine); //ADMIN CAN MANAGE CUISINES
        $auth->addChild($admin, $manageService); //ADMIN CAN MANAGE SERVICES
        $auth->addChild($admin, $manageLocation); //ADMIN CAN MANAGE LOCATION
        $auth->addChild($admin, $manageArea); //ADMIN CAN MANAGE AREA
        $auth->addChild($admin, $manageSiteSetting); //ADMIN CAN MANAGE SETTINGS
        $auth->addChild($admin, $seeFavDish); //ADMIN CAN SEE FAV DISHES
        $auth->addChild($admin, $manageSiteContent); //ADMIN CAN MANAGE SITE CONTENT
        $auth->addChild($admin, $manageCoupons); //ADMIN CAN MANAGE COUPONS
        $auth->addChild($admin, $placeOrder); //ADMIN CAN PLACE ORDER
        
        
        //APPLY THE RESTAURANT RULE
        $rule = new RestaurantRoleRule(); //Apply our Rule that use the user roles from user table
        $auth->add($rule);
    }

}

<?php

namespace common\rbac;

use yii\rbac\Rule;/** * Checks if user role matches user passed via params  */

class UserRoleRule extends Rule {

    public $name = 'userRole';

    public function execute($user, $item, $params) {         //check the role from table user         
        if (isset(\Yii::$app->user->identity->type))
            $role = \Yii::$app->user->identity->type;
        else
            return false;
        
        if ($item->name === 'admin') {
            return $role == 'admin';
        } elseif ($item->name === 'restaurant') {
            return $role == 'restaurant'; //restaurant is a child of admin
        } elseif ($item->name === 'telecaller') {
            return $role == 'telecaller'|| $role == NULL; //telecaller is a child of restaurant and admin, if we have no role defined this is also the default role
        } else {
            return false;
        }
    }

}

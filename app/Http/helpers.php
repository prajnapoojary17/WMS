<?php
use App\User;
class Helper
{
        public static function getRole() {
        $userId = Auth::user()->id;
        $role = User::
              leftjoin('admin_details', 'users.id', '=', 'admin_details.user_id')
               ->leftjoin('master_users_sub_category', 'admin_details.sub_category_id', '=', 'master_users_sub_category.id')
                ->leftjoin ('master_users_category', 'users.cat_id', '=', 'master_users_category.id')
               ->where('users.id', $userId)
               ->first();
        return $role;
    }
       
}
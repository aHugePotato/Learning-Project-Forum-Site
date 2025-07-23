<?php

use app\management\model\Admin;

/**
 * function get_admin_role
 *
 * @param mixed $id
 *
 * @return mixed
 */
function get_admin_role($id)
{
    return  Admin::get($id)->role;
}

/**
 * 检查管理员是否有某权限
 *
 * @param mixed $adminId
 * @param string $permission
 *
 * @return bool
 */
function check_permission($adminId, string $permission)
{
    foreach (Admin::get($adminId)->role as $role) {
        if($role["name"]=="super_admin")
            //超级管理员直接返回true
            return true;
        foreach ($role->permission as $perm) {
            if ($permission == $perm["name"])
                return true;
        }
    }
    return false;
}

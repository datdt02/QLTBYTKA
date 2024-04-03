<?php
/**
 * get all media categories
 * @return $objects
 */
if (!function_exists('checkAuthUserPermission')) {
    function checkAuthUserPermission(string $permission)
    {
        if (!auth()->user()->can($permission)) {
            //return redirect()->back()->with("error", "Bạn không có quyền thực hiện chức năng này!");
            abort(403, "Bạn không có quyền thực hiện chức năng này!");
        }
    }
}

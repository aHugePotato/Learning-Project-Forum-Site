<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * function sanitize_filename
 * 过滤文件名
 *
 * @param string $fname
 *
 * @return mixed
 */
function sanitize_filename(string $fname)
{
    return preg_replace('/[^A-z0-9.]+/', '-', $fname);
}

/**
 * function uq_array_diff_bi
 * 获得不重复数组b相对于不重复数组a新增的元素和删除元素。
 * 结果分别存放在返回值的["added"]和["deleted"]里面。
 *
 * @param array $a
 * @param array $b
 *
 * @return array
 */
function uq_array_diff_bi(array $a, array $b)
{
    reset($a);
    reset($b);
    $elemOfA_isInB_Arr = $added = [];
    foreach ($b as $i) {
        $found = false;
        foreach ($a as $j => $k) {
            if ($i === $k) {
                $elemOfA_isInB_Arr[$j] = true;
                $found = true;
                break;
            }
        }
        if (!$found)
            $added[] = $i;
    }
    $deleted = [];
    foreach ($a as $i => $j)
        if (empty($elemOfA_isInB_Arr[$i]))
            $deleted[] = $j;
    return ["deleted" => $deleted, "added" => $added];
}

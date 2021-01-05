<?php
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

if (!function_exists('di')) {
    /**
     * di
     *
     * @param string $id
     * @return mixed
     */
    function di(string $id)
    {
        return \Hyperf\Utils\ApplicationContext::getContainer()->get($id);
    }
}

if (!function_exists('toTree')) {
    /**
     * 无限极分类树
     *
     * @param array $data_list
     * @param int $parent_id
     * @return array
     */
    function toTree(array $data_list, int $parent_id = 0) :array
    {
        $tree = [];

        foreach ($data_list as $item) {
            if ($item['pid'] !== $parent_id) continue;

            foreach ($data_list as $sub) {
                if($sub['pid'] === $item['id']) {
                    $children = toTree($data_list, $item['id']);
                    $item['children'] = $children;
                    break;
                }
            }
            $tree[] = $item;
        }

        return $tree;
    }
}

if (!function_exists('getConfig')) {
    /**
     * 获取配置
     *
     * @param string $key
     * @param $default
     * @return mixed
     */
    function getConfig(string $key, $default = null)
    {
        try {
            $configs = di(\Psr\SimpleCache\CacheInterface::class)->get('AppConfigs');
            return $configs[$key] ?? config($key, $default);
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            return $default;
        }
    }
}
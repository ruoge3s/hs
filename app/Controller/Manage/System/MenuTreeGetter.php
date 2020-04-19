<?php
declare(strict_types=1);

namespace App\Controller\Manage\System;

use App\Helper\Utils;
use App\Model\Menu;

/**
 * Trait MenuTreeGetter
 * 菜单树获取器
 * @package App\Controller\Manage\System
 */
trait MenuTreeGetter
{
    /**
     * 生成菜单树
     * @param callable|null $filter
     * @param bool $tree
     * @return array|bool|mixed
     */
    protected function getMenuTree(callable $filter=null, bool $tree=true)
    {
        $menu = Menu::query();

        if (is_callable($filter)) $menu = $filter($menu);

        $data = $menu->get(['id', 'pid', 'name', 'describe'])->toArray();
        if ($tree) {
            $data = Utils::toTree($data);
            $data = Utils::formatTree($data);
        }
        return $data;
    }
}

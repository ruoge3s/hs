<?php
declare(strict_types=1);

namespace App\Controller\Manage\System;

use App\Constants\ErrorCode;
use App\Controller\Manage\CommonController;
use App\Model\Menu;
use App\Model\RoleHasMenu;
use App\Request\MenuCreateRequest;
use App\Request\MenuUpdateRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MenuController
 * 菜单管理
 * @package App\Controller\Manage
 */
class MenuController extends CommonController
{
    use MenuTreeGetter;
    /**
     * 获取树形结构的菜单
     * @return ResponseInterface
     */
    public function tree()
    {
        return $this->success($this->getMenuTree());
    }

    /**
     * 创建菜单
     * @param MenuCreateRequest $request
     * @return ResponseInterface
     */
    public function create(MenuCreateRequest $request)
    {
        $request->validateResolved();
        $data = $request->validated();
        return $this->save(new Menu(), $data);
    }

    /**
     * 更新一个菜单
     * @param MenuUpdateRequest $request
     * @return ResponseInterface
     */
    public function update(MenuUpdateRequest $request)
    {
        $request->validateResolved();
        $data = $request->validated();
        $menu = Menu::query()->findOrFail($data['id']);
        return $this->save($menu, $data);
    }

    /**
     * 删除一个菜单
     * @param $id
     * @return ResponseInterface
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $menu = Menu::query()->findOrFail($id);
        /** @var Menu $menu */
        if ($menu->son()->first()) {
            return $this->failure(ErrorCode::OPERATE_FAILURE,'不能删除带有子级的菜单');
        } else {
            RoleHasMenu::query()->where('menu_id', '=', $menu->id)->delete(); // 尝试删除所有的关联信息
            return $menu->delete()
                ? $this->success()
                : $this->failure(ErrorCode::OPERATE_FAILURE, '删除失败');
        }
    }
}

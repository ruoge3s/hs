<?php
declare(strict_types=1);

namespace App\Constants;

use App\Model\User;
use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * Class ModelName
 * 主要为modeName和其名词性的名字对应
 * @package App\Constants
 * @method static string getMessage(string $modelName)
 * @Constants
 */
class ModelName extends AbstractConstants
{
    /**
     * @Message("用户")
     */
    const USER = User::class;
}

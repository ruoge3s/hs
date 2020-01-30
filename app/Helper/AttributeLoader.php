<?php
declare(strict_types=1);

namespace App\Helper;

/**
 * 属性加载器
 * Trait AttributeLoader
 * @package App\Help
 */
trait AttributeLoader
{
    /**
     * 加载类属性值
     * @param array $attributes
     * @param bool $strict
     * @return $this|null
     */
    public function load(array $attributes = [], $strict = false)
    {
        try {
            $rc = new \ReflectionClass(static::class);
        } catch (\Exception $e) {
            return null;
        }
        $publicProperties = $rc->getProperties(\ReflectionProperty::IS_PUBLIC);
        $propertyNames = [];
        foreach ($publicProperties as $property) {
            $propertyNames[] = $property->name;
        }
        foreach ($attributes as $key => $value) {
            if (in_array($key, $propertyNames)) {
                $this->$key = $value;
            } else {
                if ($strict) {
                    return null;
                }
            }
        }
        return $this;
    }
}

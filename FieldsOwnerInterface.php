<?php


namespace rushstart\field;

/**
 * Interface FieldsOwnerInterface
 */
interface FieldsOwnerInterface
{
    /**
     * Обычно это имя таблицы модели
     * @return string
     */
    public function getEntityName(): string;

    /**
     * Группирование по категории
     * @return array|string
     */
    public function getBundleName();
}
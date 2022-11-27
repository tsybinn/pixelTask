<?php
namespace App\HighloadBlock\Client;

class ClientTaskStatusEoTable extends \Bitrix\Main\Entity\DataManager
{
    public static function getTableName():string
    {
        return 'px_task_status';
    }


    public static function getMap():array
    {
        $arMap = [
            'ID' => [
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
            ],
            'NAME' => [
                'data_type' => 'string',
            ],
            'CODE' => [
                'data_type' => 'string',
            ],

        ];
        return $arMap;
    }
}
<?php

namespace App\HighloadBlock\Client;

class ClientTaskEoTable extends \Bitrix\Main\Entity\DataManager
{
    public static function getTableName(): string
    {
        return 'px_task';
    }

    public static function getMap(): array
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
            'PRICE' => [
                'data_type' => 'integer',
            ],
            'CLIENT_ID' => [
                'data_type' => 'integer',
            ],
            'STATUS_ID' => [
                'data_type' => 'integer',
            ],

        ];
        return $arMap;
    }
}
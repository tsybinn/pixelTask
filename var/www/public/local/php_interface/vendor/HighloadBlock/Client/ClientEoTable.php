<?php

namespace App\HighloadBlock\Client;

class ClientEoTable extends \Bitrix\Main\Entity\DataManager
{
    public static function getTableName(): string
    {
        return 'px_client';
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

        ];
        return $arMap;
    }

    public static function AddClientTask(int $count): void
    {
        set_time_limit(0);
        for ($i = 0; $i < $count; $i++) {
            echo $i;

            if (!$id) {
                $lastIdClient = ClientEoTable::query()
                    ->setSelect(['ID'])
                    ->setOrder(['ID' => 'desc'])
                    ->setLimit(1)
                    ->exec()
                    ->fetch();
                $name = 'test_' . ++$lastIdClient['ID'];
            } else {
                $name = 'test_' . ++$id;
            }

            $res = ClientEoTable::add(['NAME' => $name]);
            $id = $res->getId();

            for ($x = 0; $x < 500; $x++) {
                $status = 2;
                if ($x < 250) {
                    $status = 1;
                }

                $nameTask = bin2hex(random_bytes(10));;

                $arFieldList = ['NAME' => $nameTask, 'PRICE' => 1, 'CLIENT_ID' => $id, 'STATUS_ID' => $status];
                \App\HighloadBlock\Client\ClientTaskEoTable::add($arFieldList);
            }
            // медленнее
            // \App\HighloadBlock\Client\ClientTaskEoTable::addMulti($arFieldList);

        }

    }
}

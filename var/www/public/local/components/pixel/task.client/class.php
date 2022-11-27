<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Bitrix vars
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 * \Bitrix\Main\UI\PageNavigation
 */

use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\Relations\Reference;

class TaskClientComponent extends \CBitrixComponent
{
    //т.к. клиентов может становится больше, очень сильно увеличится
    // обьем данных в таблица px_task решил добавить пагинацию
    private object $obNavigation;

    public function onPrepareComponentParams($arParams)
    {
        return parent::onPrepareComponentParams($arParams);
    }

    public function executeComponent()
    {
        $this->initPageNavigation();
        $this->setResult();
        $this->arResult['PAGE_NAVIGATION'] = $this->getPageNavigation();

        $this->IncludeComponentTemplate();

    }

    private function initPageNavigation()
    {
        $this->obNavigation = new \Bitrix\Main\UI\PageNavigation("nav-more-news");
        $this->obNavigation->allowAllRecords(false)
            ->setPageSize($this->arParams['ELEMENT_COUNT'])
            ->initFromUri();
    }

    private function getPageNavigation()
    {
        global $APPLICATION, $component;
        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:main.pagenavigation",
            "",
            [
                "NAV_OBJECT" => $this->obNavigation,
                "SEF_MODE" => "N",
                'TYTLE' => 'Записи',
                'SHOW_ALWAYS' => 'Y',
                'HIDE_BLOCK_SEARCH_ITEMS' => true,
            ],
            false
        );
        $pageNavigation = ob_get_contents();
        ob_end_clean();
        return $pageNavigation;
    }

    private function setResult(): void
    {
        $obCache = new CPHPCache();
        $cacheId = $this->arParams['ELEMENT_COUNT'] . $this->obNavigation->getCurrentPage();
        // кеширование
        if ($obCache->InitCache($this->arParams['CACHE_TIME'], $cacheId, SITE_ID . '/task/')) {
            $this->arResult = $obCache->GetVars();
            $this->obNavigation->setRecordCount($this->arResult['COUNT']);
        } elseif ($obCache->startDataCache()) {
            $rsTask = App\HighloadBlock\Client\ClientTaskEoTable::query()
                ->registerRuntimeField(
                    new Reference(
                        'STATUS',
                        App\HighloadBlock\Client\ClientTaskStatusEoTable::class,
                        \Bitrix\Main\ORM\Query\Join::on('this.STATUS_ID', 'ref.ID')
                    )
                )
                ->registerRuntimeField(
                    new ExpressionField(
                        'SUM_DONE', 'SUM(IF(%1$s = "F", %2$s, 0))', ['STATUS.CODE', 'PRICE']
                    )
                )
                ->registerRuntimeField(
                    new ExpressionField(
                        'SUM_PROCESS', 'SUM(IF(%1$s = "P", %2$s, 0))', ['STATUS.CODE', 'PRICE']
                    )
                )
                ->registerRuntimeField(
                    new Reference(
                        'CLIENT',
                        App\HighloadBlock\Client\ClientEoTable::class,
                        \Bitrix\Main\ORM\Query\Join::on('this.CLIENT_ID', 'ref.ID')
                    )
                )
                ->registerRuntimeField(
                    new ExpressionField(
                        'COUNT_TASK', 'COUNT(%s)', ['CLIENT_ID']
                    )
                )
                ->setSelect(['CLIENT_ID', 'CLIENT_NAME' => 'CLIENT.NAME', 'SUM_DONE', 'SUM_PROCESS', 'COUNT_TASK'])
                ->countTotal(true)
                ->setOffset($this->obNavigation->getOffset())
                ->setLimit($this->obNavigation->getLimit())
                ->exec();
            // COUNT для работы пагинации в кеше
            $this->arResult['COUNT'] = $rsTask->getCount();
            $this->obNavigation->setRecordCount($this->arResult['COUNT']);
            $this->arResult['ITEMS'] = $rsTask->fetchAll();

            $obCache->EndDataCache($this->arResult);
        }

    }
}

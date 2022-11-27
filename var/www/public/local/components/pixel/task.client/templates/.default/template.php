<?php
/**
 * Bitrix vars
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 */
$this->addExternalCss("https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");
$this->addExternalJS("https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js");
?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID клиента</th>
            <th scope="col">Название клиента</th>
            <th scope="col">Сумма выполнено</th>
            <th scope="col">Сумма в процессе</th>
            <th scope="col">Общее количество задач</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($arResult['ITEMS'] as $arClient) { ?>
            <tr>
                <th scope="row"><?= $arClient['CLIENT_ID'] ?></th>
                <td><?= $arClient['CLIENT_NAME'] ?></td>
                <td><?= $arClient['SUM_DONE'] ?></td>
                <td><?= $arClient['SUM_PROCESS'] ?></td>
                <td><?= $arClient['COUNT_TASK'] ?></td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
<?= $arResult['PAGE_NAVIGATION']; ?>
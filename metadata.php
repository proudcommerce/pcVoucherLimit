<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @copyright (c) Tobias Merkl | 2025
 * @link https://www.proudcommerce.com
 * @package pcVoucherLimit
 **/

$sMetadataVersion = '2.1';

$psModuleId = 'pcVoucherLimit';
$psModuleName = 'pcVoucherLimit';
$psModuleVersion = '1.0.0';

$psModuleDesc = 'Begrenzung von Gutscheinen auf einen maximalen Einkaufswert.';

/**
 * Module information
 */
$aModule = [
    'id' => $psModuleId,
    'title' => [
        'de' => $psModuleName,
        'en' => $psModuleName,
    ],
    'description' => [
        'de' => $psModuleDesc,
        'en' => $psModuleDesc
    ],
    'thumbnail' => '',
    'version' => $psModuleVersion,
    'author' => 'Proud Commerce GmbH',
    'url' => 'https://github.com/proudcommerce',
    'email' => '',
    'extend' => [
        \OxidEsales\Eshop\Application\Model\Voucher::class => \ProudCommerce\VoucherLimit\Model\Voucher::class,
    ],
    'settings' => [],
    'blocks' => [
        [
            'template' => 'voucherserie_main.tpl',
            'block' => 'admin_voucherserie_main_form',
            'file' => 'views/blocks/admin_voucherserie_main_form.tpl'
        ]
    ],
    'events' => [
        'onActivate' => '\ProudCommerce\VoucherLimit\Core\Setup::onActivate',
        'onDeactivate' => '\ProudCommerce\VoucherLimit\Core\Setup::onDeactivate',
    ],
];


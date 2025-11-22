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

namespace ProudCommerce\VoucherLimit\Model;

class Voucher extends Voucher_parent
{
    protected function _isAvailablePrice($dPrice)
    {
        $return = parent::_isAvailablePrice($dPrice);

        $oSeries = $this->getSerie();
        $oCur = $this->getConfig()->getActShopCurrencyObject();
        $dMaximumValue = $oSeries->oxvoucherseries__tabslvoucherlimit_maximumvalue->value;
        
        if ($dMaximumValue > 0 && $dPrice > ($dMaximumValue * $oCur->rate)) {
            $oEx = oxNew(\OxidEsales\Eshop\Core\Exception\VoucherException::class);
            $oEx->setMessage('ERROR_MESSAGE_VOUCHER_NOVOUCHER');
            $oEx->setVoucherNr($this->oxvouchers__oxvouchernr->value);
            throw $oEx;
        }

        return $return;
    }
}


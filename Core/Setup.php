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

namespace ProudCommerce\VoucherLimit\Core;

class Setup extends \OxidEsales\Eshop\Core\Base
{
    public static $sModuleId = 'pcVoucherLimit';

    public static function onActivate()
    {
        $res = self::_dbEvent('install.sql', 'onActivate()', 'oxvoucherseries;tabslvoucherlimit_maximumvalue');
        return $res;
    }

    public static function onDeactivate()
    {
        self::_dbEvent('', 'onDeactivate()');
    }

    protected static function _dbEvent($sSqlFile = "", $sAction = "", $sDbCheck = "")
    {
        if ($sSqlFile != "") {
            try {
                $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

                if (!empty($sDbCheck)) {
                    $aDbCheck = explode(";", $sDbCheck);
                    if (count($aDbCheck) > 0 && self::dbColumnExist($aDbCheck[0], $aDbCheck[1])) {
                        return true;
                    }
                }

                $sSql = file_get_contents(dirname(__FILE__) . '/../setup/sql/' . (string)$sSqlFile);
                $aSql = (array)explode(';', $sSql);
                foreach ($aSql as $sQuery) {
                    if (!empty($sQuery)) {
                        $oDb->execute($sQuery);
                    }
                }
            } catch (Exception $ex) {
                error_log($sAction . " failed: " . $ex->getMessage());
            }

            $oDbHandler = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);
            $oDbHandler->updateViews();

            self::clearTmp();
        }
        return true;
    }

    public static function dbColumnExist($sTable, $sColumn)
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sDbName = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('dbName');
        try {
            $sSql = "SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?";
            $blRet = $oDb->getOne($sSql, [$sDbName, $sTable, $sColumn]);
        } catch (Exception $oEx) {
            $blRet = false;
        }
        return $blRet;
    }

    public static function clearTmp($sClearFolderPath = '')
    {
        $sFolderPath = self::_getFolderToClear($sClearFolderPath);
        $hDirHandler = opendir($sFolderPath);

        if (!empty($hDirHandler)) {
            while (false !== ($sFileName = readdir($hDirHandler))) {
                $sFilePath = $sFolderPath . DIRECTORY_SEPARATOR . $sFileName;
                self::_clear($sFileName, $sFilePath);
            }
            closedir($hDirHandler);
        }

        return true;
    }

    protected static function _getFolderToClear($sClearFolderPath = '')
    {
        $sTempFolderPath = (string)\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sCompileDir');
        if (!empty($sClearFolderPath) and (strpos($sClearFolderPath, $sTempFolderPath) !== false)) {
            $sFolderPath = $sClearFolderPath;
        } else {
            $sFolderPath = $sTempFolderPath;
        }
        return $sFolderPath;
    }

    protected static function _clear($sFileName, $sFilePath)
    {
        if (!in_array($sFileName, ['.', '..', '.gitkeep', '.htaccess'])) {
            if (is_file($sFilePath)) {
                @unlink($sFilePath);
            } else {
                self::clearTmp($sFilePath);
            }
        }
    }
}


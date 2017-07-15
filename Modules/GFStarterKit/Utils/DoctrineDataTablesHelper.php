<?php

namespace Modules\GFStarterKit\Utils;

class DoctrineDataTablesHelper
{
	private static $totalRows;
	private static $limitedRows;
	private static $orderValue;
	private static $orderType;

	public static function initializeRowsValues($em, $query)
	{
		$ret = array();

		try {
			self::$limitedRows = $query->getMaxResults();
			$conn = $em->getConnection();
			$query2 = clone $query;
			$query2->setMaxResults(null);
			$query2->setFirstResult(null);
			$sql = $query2->getSQL();
			$sql = "SELECT count(*) as TotalRows FROM (" . $sql . ") as dt1";
			$stmt = $conn->query($sql);
			if ($row = $stmt->fetch()) {
				if (isset($row['TotalRows'])) {
					self::$totalRows = $row['TotalRows'];
				}
			}
			unset($query2);
			unset($conn);
		} catch(Exception $e) {

		}

	}

	public static function getTotalRows()
	{
		if (isset(self::$totalRows)) {
			return self::$totalRows;
		}
	}

	public static function getLimitedRows()
	{
		if (isset(self::$limitedRows)) {
			return self::$limitedRows;
		}
	}

	public static function serQueryOrder($dataArray)
	{
		if (isset($dataArray['orderValue'])) {
			self::$orderValue = $dataArray['orderValue'];
			if (isset($dataArray['orderNumeric']) && $dataArray['orderNumeric']) {
				self::$orderValue = $dataArray['orderValue'] . " + 0 ";
			}
		}

		if (isset($dataArray['orderType'])) {
			self::$orderType = $dataArray['orderType'];
		}
	}

	public static function getQueryOrder()
	{
		$ret = '';

		if (isset(self::$orderValue)) {
			if (self::$orderValue != '') {
				$ret .= ' ORDER BY ' . self::$orderValue;
				if (isset(self::$orderType)) {
					$ret .= ' ' . self::$orderType;
				}
			}
		}

		return $ret;
	}
}

<?php

namespace Doctrine\Common;

class DoctrineHelper
{
	private static $_totalRows;
	private static $_limitedRows;
	private static $_orderValue;
	private static $_orderType;
	
	public static function stQuerySelectLimitedResult($em, $query)
	{
		$ret = array();
		
		try {
			self::$_limitedRows = $query->getMaxResults();
			$conn = $em->getConnection();
			// Se clona el objeto de la consulta para quitar los límites de la consulta
			$query2 = clone $query;
			$query2->setMaxResults(null);
			$query2->setFirstResult(null);
			$sql = $query2->getSQL();
			$sql = "SELECT count(*) as TotalRows FROM (" . $sql . ") as dt1";
			$stmt = $conn->query($sql);
			if ($row = $stmt->fetch()) {
				if (isset($row['TotalRows'])) {
					self::$_totalRows = $row['TotalRows'];
				}
			}
			unset($query2);
			unset($conn);
			$ret = $query->getResult();
		} catch(Exception $e) {
			
		}
		
		return $ret;
	}
	
	public static function stGetTotalRows()
	{
		if (isset(self::$_totalRows)) {
			return self::$_totalRows;
		}
	}
	
	public static function stGetLimitedRows()
	{
		if (isset(self::$_limitedRows)) {
			return self::$_limitedRows;
		}
	}
	
	public static function stLoadQueryOrder($dataArray)
	{
		if (isset($dataArray['orderValue'])) {
			self::$_orderValue = $dataArray['orderValue'];
			if (isset($dataArray['orderNumeric']) && $dataArray['orderNumeric']) {
				self::$_orderValue = $dataArray['orderValue'] . " + 0 ";
			}
		}
		
		if (isset($dataArray['orderType'])) {
			self::$_orderType = $dataArray['orderType'];
		}
	}
	
	public static function stGetQueryOrder()
	{
		$ret = '';
		
		if (isset(self::$_orderValue)) {
			if (self::$_orderValue != '') {
				$ret .= ' ORDER BY ' . self::$_orderValue;
				if (isset(self::$_orderType)) {
					$ret .= ' ' . self::$_orderType;
				}
			}
		}
		
		return $ret;
	}
}

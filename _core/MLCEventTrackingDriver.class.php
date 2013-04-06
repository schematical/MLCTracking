<?php
abstract class MLCEventTrackingDriver{
	
	public static function Track($strName, $mixValue = null, $blnSave = true){
		$strValue = null;
		if(is_string($mixValue)){
			$strValue = $mixValue;
		}else{
			$strValue = serialize($mixValue);
		}
		$objEvent = new TrackingEvent();
		$objEvent->Name = $strName;
		$objEvent->Value = $strValue;
		$objEvent->CreDate = MLCDateTime::Now();
		if(class_exists('MLCAuthDriver')){
			$objSession = MLCAuthDriver::LoadSession();
			if(!is_null($objSession)){
				$objEvent->IdSession = $objSession->IdSession;
			}			
			$objEvent->IdUser = MLCAuthDriver::IdUser();
			
		}
		if(array_key_exists('ref', $_GET)){
			$objEvent->Ref = $_GET['ref'];
		}
		$strCookie = MLCCookieDriver::GetCookie('__utma');
		if(!is_null($strCookie)){
			$objEvent->Utma =$strCookie;
		}
		if($blnSave){
			$objEvent->Save();
		}
		return $objEvent;
	}
	public static function GetCount($arrOptions = array(), $mixGroupBy = null){
		if(is_array($mixGroupBy)){
			$arrGroupBy = $mixGroupBy;
			$strGroupBy = $arrGroupBy[0];
		}else{
			$strGroupBy = $mixGroupBy;
			$arrGroupBy = array($strGroupBy);
		}
		$arrAndConditions = array();
		if(array_key_exists(MLCTrackingField::StartDate, $arrOptions)){
			$arrAndConditions[] = sprintf('creDate > "%s"', $arrOptions[MLCTrackingField::StartDate]);
		}
		if(array_key_exists(MLCTrackingField::EndDate, $arrOptions)){
			$arrAndConditions[] = sprintf('creDate < "%s"', $arrOptions[MLCTrackingField::EndDate]);
		}
		if(array_key_exists(MLCTrackingField::EventName, $arrOptions)){
			$arrAndConditions[] = sprintf('name = "%s"', $arrOptions[MLCTrackingField::EventName]);
		}
		if(array_key_exists(MLCTrackingField::App, $arrOptions)){
			$arrAndConditions[] = sprintf('app = "%s"', $arrOptions[MLCTrackingField::App]);
		}
		if(array_key_exists(MLCTrackingField::Event, $arrOptions)){
			$arrAndConditions[] = sprintf('event = "%s"', $arrOptions[MLCTrackingField::Event]);
		}
		if(array_key_exists(MLCTrackingField::ControlId, $arrOptions)){
			$arrAndConditions[] = sprintf('controlId = "%s"', $arrOptions[MLCTrackingField::ControlId]);
		}
		if(array_key_exists(MLCTrackingField::Utma, $arrOptions)){
			$arrAndConditions[] = sprintf('utma = "%s"', $arrOptions[MLCTrackingField::Utma]);
		}
		if(array_key_exists(MLCTrackingField::Text, $arrOptions)){
			$arrAndConditions[] = sprintf('text = "%s"', $arrOptions[MLCTrackingField::Text]);
		}
		if(array_key_exists(MLCTrackingField::Form, $arrOptions)){
			$arrAndConditions[] = sprintf('form = "%s"', $arrOptions[MLCTrackingField::Form]);
		}
		
		$strSql = sprintf('SELECT %s, count(%s) as `count` FROM TrackingEvent ', implode(',', $arrGroupBy), $strGroupBy);
		if(count($arrAndConditions) > 0){
			$strSql .= 'WHERE ' . implode(' AND ', $arrAndConditions);
		}
		if(!is_null($strGroupBy)){
			$strSql .= sprintf(
				' GROUP BY %s ',
				implode(', ', $arrGroupBy)
			);
		}
		//die($strSql);
		$resResult = MLCDBDriver::Query($strSql,'DB_0');
		$arrReturn = array();
		while($arrFields = mysql_fetch_assoc($resResult)){
			if(!array_key_exists($arrFields[$strGroupBy], $arrReturn)){
				$arrReturn[$arrFields[$strGroupBy]] = 0;
			}
			if(count($arrGroupBy) > 1){
				$arrReturn[$arrFields[$strGroupBy]] += 1;//$arrFields['count'];
			}else{
				$arrReturn[$arrFields[$strGroupBy]] += $arrFields['count'];
			}
		}
		
		return $arrReturn;
			
		
	}
	
} 
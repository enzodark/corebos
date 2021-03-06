<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once 'include/Webservices/Query.php';
include_once dirname(__FILE__) . '/Filter.php';
	
class crmtogo_WS_SearchFilterModel extends crmtogo_WS_FilterModel {
	protected $criterias;
	
	function __construct($moduleName) {
		$this->moduleName = $moduleName;
	}
	
	function query() {
		return false;
	}
	
	function queryParameters() {
		return false;
	}
	
	function setCriterias($criterias) {
		$this->criterias = $criterias;
	}
	
	function execute($fieldnames, $paging = false, $calwhere ='') {
		$selectClause = sprintf("SELECT %s", implode(',', $fieldnames));
		$fromClause = sprintf("FROM %s", $this->moduleName);
		if (($this->moduleName = 'Calendar' || $this->moduleName = 'Events') and $calwhere !='') {
			$whereClause = " WHERE date_start >= '".$calwhere['start']."' AND date_start <= '".$calwhere['end']."'";
		}
		else {
			$whereClause = "";
		}
		$orderClause = "";
		$groupClause = "";
		if ($paging) {
			$config = crmtogo_WS_Controller::getUserConfigSettings();
			$limitClause = "LIMIT 0,".$config['NavigationLimit'];
		}
		if (!empty($this->criterias)) {
			$_sortCriteria = $this->criterias['_sort'];
			if(!empty($_sortCriteria)) {
				$orderClause = $_sortCriteria;
			}
		}
		 
		$query = sprintf("%s %s %s %s %s %s;", $selectClause, $fromClause, $whereClause, $orderClause, $groupClause, $limitClause);
		return vtws_query($query, $this->getUser()); 
	}
	
	static function modelWithCriterias($moduleName, $criterias = false) {
		$model = new crmtogo_WS_SearchFilterModel($moduleName);
		$model->setCriterias($criterias);
		return $model;
	}
}
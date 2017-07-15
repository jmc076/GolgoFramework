<?php
namespace Modules\GFStarterKit\EntitiesLogic;


interface CRUDInterface {

	function preload();

	function create($dataArray);

	function update($dataArray);

	function delete($dataArray);

	function isPrivate();

	function getEntity();

	function assignParams($dataArray, &$model);


}
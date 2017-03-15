<?php
namespace Modules\GFStarterKit\EntitiesLogic;


interface CRUDInterface {

	protected function preload();

	protected function create($dataArray);

	protected function update($dataArray);

	protected function delete($dataArray);

	protected function isPrivate();

	protected function getEntity();

	protected function assignParams($dataArray, &$model);


}
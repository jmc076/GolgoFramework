<?php

namespace Modules\GFStarterKit\Entities;

use Doctrine\ORM\Mapping as ORM;
use Modules\GFStarterKit\Utils\DoctrineDataTablesHelper;


abstract Class BasicModel
{
	/** @ORM\Id @ORM\Column(name="id", type="integer") @ORM\GeneratedValue(strategy="AUTO") */
	public $id;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	* Obtains an object class name without namespaces
	*/
	function getModelName($obj = null) {
		return (new \ReflectionClass($this))->getShortName();
	}

	function getModelNameWithNamespace() {

		return get_class($this);
	}


	public function loadById($em, $id, $hydrated = false) {

		$model = null;
		try {
			$dql = 'SELECT t FROM ' . get_class($this) . ' t WHERE t.id = '. intval($id);
			$query = $em->createQuery($dql);
			if($hydrated) {
				$model = $query->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
			} else {
				$model = $query->getOneOrNullResult();
			}

		} catch (NoResultException $ex) {
			$model = null;
		} catch (Exception $ex) {
			$model = null;
		}
		return $model;
	}

	public function loadAll($em , $dataArray, $hydrated = false) {
		$models = null;

		try {
			$dql = 'SELECT t FROM ' . get_class($this) . ' t';

			$dql .= " WHERE 1 = 1";
			if (isset($dataArray['iDisplayStart']) || isset($dataArray['iDisplayLength'])) {
	    		$limit = 10;
	            if (isset($dataArray['iDisplayLength'])) {
	                $limit = intval($dataArray['iDisplayLength']);
	            }

	            $first = 0;
	            if (isset($dataArray['iDisplayStart'])) {
	                $first = $dataArray['iDisplayStart'];
	            }

	    		$query = $em->createQuery($dql);
	            $query->setMaxResults($limit);
	            $query->setFirstResult($first);
	            DoctrineDataTablesHelper::initializeRowsValues($em, $query);

    		} else {
    			$query = $em->createQuery($dql);
    		}

    		if($hydrated) {
    			$models = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    		} else {
    			$models = $query->getResult();
    		}

		} catch (NoResultException $ex) {
			$models =  null;
		} catch (Exception $ex) {
			$models =  null;
		}

		return $models;
	}

	public function getNextId($em)
	{
		$maxId = 1;

		try {
			$dql = 'SELECT MAX(u.id) as Maximo FROM ' . get_class($this) . ' u WHERE u INSTANCE OF ' . get_class($this);
			$query = $em->createQuery($dql);
			if (!$maxId = $query->getSingleScalarResult()) {
				$maxId = 1;
			} else {
				$maxId += 1;
			}
		} catch(NoResultException $ex) {
			$maxId = 1;
		} catch(Exception $ex) {
			$maxId = 1;
		}

		return $maxId;
	}

	/**
	 *
	 * Carga el numero de elementos de una entidad
	 * @param EM $em
	 */
	public function getTotalCount($em)
	{
		$count = 0;

		try {
			$dql = "SELECT COUNT(u.id) FROM " . get_class($this) . " u ";
			$query = $em->createQuery($dql);
			$count = $query->getSingleScalarResult();

		} catch(NoResultException $ex) {
			$count = 0;
		} catch(Exception $ex) {
			$count = 0;
		}

		return $count;
	}

}
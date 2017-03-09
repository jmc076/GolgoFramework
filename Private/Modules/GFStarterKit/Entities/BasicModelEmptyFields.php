<?php

namespace Modules\GFStarterKit\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\DoctrineHelper;
use Modules\GFStarterKit\GFSKEntityManager;


abstract Class BasicModelEmptyFields
{


	/**
	* Obtains an object class name without namespaces
	*/
	function getModelName($obj) {
	    $classname = get_class($obj);

	    if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
	        $classname = $matches[1];
	    }

	    return $classname;
	}

	function getEntityWithNamespace($obj) {

		$em = GFSKEntityManager::getEntityManager();
		$entityName = $em->getMetadataFactory()->getMetadataFor(get_class($obj))->getName();

		return $entityName;
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
	            $models = DoctrineHelper::stQuerySelectLimitedResult($em, $query);

    		} else {
    			$query = $em->createQuery($dql);
				if($hydrated) {
					$models = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
				} else {
					$models = $query->getResult();
				}
    		}

		} catch (NoResultException $ex) {
			$models =  null;
		} catch (Exception $ex) {
			$models =  null;
		}

		return $models;
	}

	public function getMaxId($em)
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
	public function loadCount($em)
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


	/**
     * Esta función se utiliza para capturar el error que ocurre cuando se intenta llamar a una entidad asociada que ha sido
     * eliminada y ya no existe. Si no se llama a este método, el software lanzará un FATAL ERROR y finalizará su ejecución.
     *
     * @param unknown_type $entity
     */
    public function callAssociatedEntity($entity)
    {
        try {
            $entity->exists();
            return $entity;
        } catch(\Doctrine\ORM\EntityNotFoundException $e) {
        } catch(\Exception $e) {
        }
    }

    public static function formatDateTime($fecha = null){
    	if($fecha == null) {
    		$fecha = date('d/m/Y H:i:s');
    	}
    	$date = str_replace('/', '-', $fecha);
    	$date = date('Y-m-d', strtotime($date));
    	$time = strtotime($date);
    	$date = new \DateTime();
    	if($fecha != null) {
    		$date->setTimestamp($time);
    	}
    	return $date;
    }

}
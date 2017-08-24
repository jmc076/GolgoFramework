<?php

namespace Modules\GFStarterKit\Entities\UserManagement;
use Doctrine\ORM\Mapping as ORM;
use Modules\GFStarterKit\Entities\BasicModel;
use Modules\GFStarterKit\Entities\UserManagement\Abstracts\BaseUserTrait;
use Modules\GFStarterKit\Utils\DoctrineDataTablesHelper;


/**
 * BaseUser
 *
 * @ORM\Table(name="gf_users")
 * @ORM\Entity
 */
class UserRegistered extends BasicModel {

    use BaseUserTrait;


    public function getPrivileges() {
        $permisos = array();

        foreach ($this->getPermissions()->getValues() as $permiso) {
            $permisos[] = $permiso->getValue();
        }

        $permisos[]="userregistered_read";
        $permisos[]="userregistered_read_loadall";

        return $permisos;

    }

    public function loadAllNoAdmins($em , $dataArray, $hydrated = false) {
        $models = null;

        try {
            $dql = 'SELECT t FROM ' . get_class($this) . ' t.userType = "' . USER_REGISTERED .'"';

            $dql .= " WHERE 1 = 1 AND t.";
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
}

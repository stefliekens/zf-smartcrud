<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;

/**
 * Class CreateService
 *
 * @package PhproSmartCrud\Service
 */
class CreateService extends AbstractCrudService
{

    /**
     * @return bool
     */
    public function create($data)
    {
        $em = $this->getEventManager();
        $em->trigger($this->createEvent(CrudEvent::BEFORE_CREATE));

        $gateway = $this->getGateway();
        $result = $gateway->create($this->getEntity(), $data);

        $em->trigger($this->createEvent(CrudEvent::AFTER_CREATE));
        return $result;
    }

}

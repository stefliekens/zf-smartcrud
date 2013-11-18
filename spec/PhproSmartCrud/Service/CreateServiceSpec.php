<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;
use Prophecy\Argument;

/**
 * Class CreateServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 */
class CreateServiceSpec extends AbstractCrudServiceSpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\CreateService');
    }

    public function it_should_extend_PhproSmartCrud_AbstractCrudService()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Service\AbstractCrudService');
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_before_create_event($eventManager)
    {
        $this->create($this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_CREATE))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_after_create_event($eventManager)
    {
        $this->create($this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_CREATE))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     */
    public function it_should_call_create_function_on_gateway($gateway)
    {
        $data = $this->getMockPostData();
        $this->create($data);
        $gateway->create(Argument::type('stdClass'), Argument::exact($data))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     */
    public function it_should_return_gateway_return_value($gateway)
    {
        $arguments = Argument::cetera();
        $data = $this->getMockPostData();
        $gateway->create($arguments, array())->willReturn(true);
        $this->create($data)->shouldReturn(true);

        $gateway->create($arguments, array())->willReturn(false);
        $this->create($data)->shouldReturn(false);
    }

    protected function getMockPostData()
    {
        return array('property' => 'value');
    }
}

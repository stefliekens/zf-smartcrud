<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
namespace PhproSmartCrud;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * Class Module
 *
 * @package PhproSmartCrud
 */
class Module implements
    AutoloaderProviderInterface,
    BootstrapListenerInterface,
    ConfigProviderInterface

{

    /**
     * @inheritdoc
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @inheritdoc
     */
    public function getConfig($env = null)
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $event
     *
     * @return array
     */
    public function onBootstrap(EventInterface $event)
    {
        $app = $event->getTarget();
        $sharedManager = $app->getEventManager()->getSharedManager();

        // Attach to helper set event and load the document manager helper.
        $sharedManager->attach('zf-smartcrud', 'loadCli.post', array($this, 'loadCli'));
    }

    /**
     * @param EventInterface $event
     */
    public function loadCli(EventInterface $event)
    {
        $cli = $event->getTarget();
        $cli->addCommands(array(
            new \PhproSmartCrud\Console\Command\Controller\Generate(),
        ));

        $serviceManager = $event->getParam('ServiceManager');

        $helperSet = $cli->getHelperSet();
        $helperSet->set(new \PhproSmartCrud\Console\Helper\ServiceManagerHelper($serviceManager));
        $helperSet->set(new \PhproSmartCrud\Console\Helper\ConfigHelper());
        $helperSet->set(new \PhproSmartCrud\Console\Helper\GatewayListHelper());
        $helperSet->set(new \PhproSmartCrud\Console\Helper\ModuleListHelper());
    }

}

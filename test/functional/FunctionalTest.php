<?php

require_once __DIR__ . '/fixtures/config/ProjectConfiguration.class.php';

class FunctionalTest extends PHPUnit_Framework_TestCase
{
    public function testDebug()
    {
        sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('app1', 'test', true));

        /** @var sfContainer $container */
        $container = sfContext::getInstance()->getServiceContainer();

        $this->assertEquals('app1DebugContainer', get_class($container));
        $this->assertTrue($container->getParameter('extended'));
        $this->assertEquals('App1', $container->getParameter('name'));
        $this->assertInstanceOf('TestClass', $container->get('test'));
        $this->assertEquals('A', $container->get('test')->a);
        $this->assertEquals('B', $container->get('test')->b);
        $this->assertEquals('C', $container->get('test')->c);
    }

    public function testNoDebug()
    {
        sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('app2', 'test', false));

        /** @var sfContainer $container */
        $container = sfContext::getInstance()->getServiceContainer();

        $this->assertEquals('app2Container', get_class($container));
        $this->assertEquals('App2', $container->getParameter('name'));
        $this->assertEquals('X', $container->get('test')->a);
        $this->assertEquals('Y', $container->get('test')->b);
        $this->assertEquals('Z', $container->get('test')->c);
    }

    public function tearDown()
    {
        sfContext::getInstance()->shutdown();
        $testDir = sfConfig::get('sf_cache_dir') . '/..';
        sfToolkit::clearDirectory($testDir);
        @rmdir($testDir);
    }
}

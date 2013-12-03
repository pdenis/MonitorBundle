<?php

namespace Snide\Bundle\MonitorBundle\Tests\DependencyInjection;

use Snide\Bundle\MonitorBundle\DependencyInjection\SnideMonitorExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

/**
 * Class SnideMonitorExtensionTest
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class SnideMonitorExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder */
    protected $configuration;

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testRepositoryLoadThrowsExceptionUnlessRepositorySet()
    {
        $loader = new SnideMonitorExtension();
        $config = $this->getConfig();
        unset($config['repository']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * Test timer parameter
     */
    public function testTimerLoad()
    {
        $loader = new SnideMonitorExtension();
        $config = $this->getConfig();
        unset($config['timer']);
        $container = new ContainerBuilder();
        $loader->load(array($config), $container);
        $this->assertEquals(60, $container->getParameter('snide_monitor.timer'));
        $loader = new SnideMonitorExtension();
        $config = $this->getConfig();
        $container = new ContainerBuilder();
        $loader->load(array($config), $container);
        $this->assertEquals(120, $container->getParameter('snide_monitor.timer'));
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testApplicationRepositoryLoadThrowsExceptionUnlessApplicationSet()
    {
        $loader = new SnideMonitorExtension();
        $config = $this->getConfig();
        unset($config['repository']['application']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testRepositoryApplicationLoadThrowsExceptionUnlessApplicationFilenameSet()
    {
        $loader = new SnideMonitorExtension();
        $config = $this->getConfig();
        unset($config['repository']['application']['filename']);
        $loader->load(array($config), new ContainerBuilder());
    }

    public function testLoadForm()
    {
        $this->loadConfiguration();
        $this->assertHasDefinition('snide_monitor.form.application_type');
    }

    public function testLoadExecutor()
    {
        $this->loadConfiguration();
        $this->assertHasDefinition('snide_monitor.test_executor');
        $this->assertInstanceOf('Snide\Monitoring\Executor\TestExecutorInterface', $this->configuration->get('snide_monitor.test_executor'));
    }

    public function testLoadLoader()
    {
        $this->loadConfiguration();
        $this->assertHasDefinition('snide_monitor.test_loader');
        $this->assertInstanceOf('Snide\Monitoring\Loader\TestLoaderInterface', $this->configuration->get('snide_monitor.test_loader'));
    }

    public function testLoadModel()
    {
        $this->loadConfiguration();
        $this->assertHasDefinition('snide_monitor.model.application');
        $this->assertInstanceOf('Snide\Monitoring\Model\Application', $this->configuration->get('snide_monitor.model.application'));
        $this->assertHasDefinition('snide_monitor.model.test');
    }

    public function testLoadRepository()
    {
        $this->loadConfiguration();
        $this->assertHasDefinition('snide_monitor.application_repository');
        $this->assertInstanceOf('Snide\Monitoring\Repository\ApplicationRepositoryInterface', $this->configuration->get('snide_monitor.application_repository'));
    }

    public function testLoadManager()
    {
        $this->loadConfiguration();
        $this->assertHasDefinition('snide_monitor.application_manager');
        $this->assertInstanceOf('Snide\Monitoring\Manager\ApplicationManagerInterface', $this->configuration->get('snide_monitor.application_manager'));

        $this->assertHasDefinition('snide_monitor.test_manager');
        $this->assertInstanceOf('Snide\Monitoring\Manager\TestManagerInterface', $this->configuration->get('snide_monitor.test_manager'));
    }

    /**
     * @param string $id
     */
    private function assertHasDefinition($id)
    {
        $this->assertTrue(($this->configuration->hasDefinition($id) ?: $this->configuration->hasAlias($id)));
    }

    protected function loadConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new SnideMonitorExtension();
        $config = $this->getConfig();
        $loader->load(array($config), $this->configuration);
    }
    /**
     * getConfig
     *
     * @return array
     */
    protected function getConfig()
    {
        $yaml = <<<EOF
timer: 120
repository:
    type: yaml
    application:
        filename: /var/tmp/applications.yml
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }
}
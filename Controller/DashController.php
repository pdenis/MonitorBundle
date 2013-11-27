<?php

namespace Snide\Bundle\MonitorBundle\Controller;

use Itkg\Monitoring;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DashController
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class DashController extends Controller
{

    public function indexAction()
    {
        $manager = new Monitoring\Test\Manager(new Monitoring\Test\Executor());
        $manager->addTest(new Monitoring\Test\Environment('test', 'test'));
        $manager->addTest(new Monitoring\Test\Redis('Redis localhost instance', 'localhost', 6379));

        $manager->executeTests();

        return $this->render(
            $this->getTemplatePath().'index.html.twig',
            array(
                'tests'               => $manager->getTests(),
                'failedTests'         => $manager->getFailedTests(),
                'successTests'        => $manager->getSuccessTests(),
                'criticalFailedTests' => $manager->getCriticalFailedTests()
            )
        );
    }

    /**
     * Get the template path for this controller
     *
     * @return string
     */
    protected function getTemplatePath()
    {
        return 'SnideMonitorBundle:Dash:';
    }
}
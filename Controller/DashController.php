<?php

namespace Snide\Bundle\MonitorBundle\Controller;

use Snide\Monitoring;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class DashController
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
class DashController extends Controller
{

    /**
     * Dashboard Action
     *
     * @return Response
     */
    public function indexAction()
    {

        $manager = $this->getTestManager();

        // Get applications
        $applications = $this->getApplicationManager()->findAll();

        // Aggregate application's tests
        foreach($applications as $application) {
            $manager->addTests($application->getTests());
        }

        // Execute tests
        $manager->executeTests();

        // Template depends on context
        if ($this->get('request')->isXmlHttpRequest()) {
            $template = 'content.html.twig';
        } else {
            $template = 'index.html.twig';
        }

        return $this->render(
            $this->getTemplatePath().$template,
            array(
                'tests'               => $manager->getTests(),
                'failedTests'         => $manager->getNotCriticalFailedTests(),
                'successTests'        => $manager->getSuccessTests(),
                'criticalFailedTests' => $manager->getCriticalFailedTests(),
                'categories'          => $manager->getCategories(),
                'applications'        => $applications,
                'lastUpdate'          => date("l jS \of F Y h:i:s A"),
                'timer'               => $this->get('service_container')->getParameter('snide_monitor.timer'),
            )
        );
    }

    /**
     * Return Tests as JSON content
     *
     * @param string $category Filter category
     *
     * @return Response
     */
    public function apiAction($category = null)
    {
        $manager = $this->getTestManager();
        // We use filtered category to get only tests for this category
        $manager->setFilteredCategory($category);
        // Execute tests
        $manager->executeTests();

        // JSON response
        $response = new Response();
        $response->setContent($manager->getTestsAsJson());
        $response->headers->set('Content-Type', 'application/json');

        return $response;
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

    /**
     * Get test manager
     *
     * @return TestManager
     */
    public function getTestManager()
    {
        return $this->get('snide_monitor.test_manager');
    }

    /**
     * Get application manager
     *
     * @return Monitoring\Manager\ApplicationManager
     */
    public function getApplicationManager()
    {
        return $this->get('snide_monitor.application_manager');
    }
}

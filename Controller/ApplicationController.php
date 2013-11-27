<?php


namespace Snide\Bundle\MonitorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ApplicationController
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ApplicationController extends Controller
{
    public function indexAction()
    {
        $applications = $this->getManager()->findAll();

        return $this->render(
            $this->getTemplatePath().'index.html.twig',
            array(
                'applications' => $applications
            )
        );
    }

    public function createAction()
    {
        $form = $this->getForm();

        $request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {

                $this->getManager()->create($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Application created successfully');

                return new RedirectResponse($this->generateUrl('snide_monitor_dashboard'));
            }else {
                $this->get('session')->getFlashBag()->add('error', 'Some errors have been found');

            }
        }

        return $this->render(
            $this->getTemplatePath().'new.html.twig',
            array(
                'form' => $form->createView(),
                'errors' => $form->getErrors()
            )
        );
    }

    public function editAction($id)
    {
        $application = $this->getManager()->find($id);
        if(!$application) {
            return new RedirectResponse($this->generateUrl('snide_monitor_dashboard'));
        }
        $form = $this->getForm($application);
        return $this->render(
            $this->getTemplatePath().'edit.html.twig',
            array(
                'form' => $form->createView(),
                'id'   => $id,
                'errors' => array()
            )
        );
    }

    public function newAction()
    {
        return $this->render(
            $this->getTemplatePath().'new.html.twig',
            array(
                'form' => $this->getForm()->createView(),
                'errors' => array()
            )
        );
    }

    public function updateAction($id)
    {
        $form = $this->getForm();
        // Get request
        $request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                // Save instance
                $this->getManager()->create($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Application updated successfully');

                return new RedirectResponse($this->generateUrl('snide_monitor_dashboard'));
            }else {
                $this->get('session')->getFlashBag()->add('error', 'Some errors found');
            }
        }

        return $this->render(
            $this->getTemplatePath().'edit.html.twig',
            array(
                'form' => $form->createView(),
                'errors' => $form->getErrors()
            )
        );
    }

    public function deleteAction($id)
    {
        $application = $this->getManager()->find($id);
        if($application) {
            $this->getManager()->delete($application);
            $this->get('session')->getFlashBag()->add('success', 'Application has been deleted successfully');
        }else {
            $this->get('session')->getFlashBag()->add('error', 'This application does not exist');
        }

        return new RedirectResponse($this->generateUrl('snide_monitor_dashboard'));
    }

    /**
     * Get template path for this controller
     *
     * @return string
     */
    protected function getTemplatePath()
    {
        return 'SnideMonitorBundle:Application:';
    }

    public function getForm($application = null)
    {
        if($application == null) {
            $application = $this->getManager()->createNew();
        }

        return $this->createForm(
            $this->container->get('snide_monitor.form.application_type'),
            $application
        );
    }

    /**
     * Get Application manager
     *
     * @return mixed
     */
    public function getManager()
    {
        return $this->get('snide_monitor.application_manager');
    }
}
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
    /**
     * List action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Create application action
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Edit application action
     *
     * @param $id application ID
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * New application action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Update application action
     *
     * @param $id
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($id)
    {
        $form = $this->getForm();
        // Get request
        $request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                // Save instance
                $this->getManager()->update($form->getData());
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

    /**
     * Delete application action
     *
     * @param $id
     * @return RedirectResponse
     */
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

    /**
     * Create application Form and bind it with application instance
     *
     * @param null $application
     * @return \Symfony\Component\Form\Form
     */
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
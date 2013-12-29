<?php

/*
 * This file is part of the SnideMonitor bundle.
 *
 * (c) Pascal DENIS <pascal.denis.75@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Snide\Bundle\MonitorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApplicationController
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
class ApplicationController extends Controller
{
    /**
     * List action
     *
     * @return array
     *
     * @Template
     */
    public function indexAction()
    {
        $applications = $this->getManager()->findAll();

        return array(
            'applications' => $applications
        );
    }

    /**
     * Create application action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Template("SnideMonitorBundle:application:new")
     */
    public function createAction(Request $request)
    {
        $form = $this->getForm();

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $this->getManager()->create($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Application created successfully');

                return new RedirectResponse($this->generateUrl('snide_monitor_dashboard'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Some errors have been found');

            }
        }

        return $this->render(
            $this->getTemplatePath() . 'new.html.twig',
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
     * @return array
     *
     * @Template
     */
    public function editAction($id)
    {
        $application = $this->getManager()->find($id);
        if (!$application) {
            return new RedirectResponse($this->generateUrl('snide_monitor_dashboard'));
        }
        $form = $this->getForm($application);
        return array(
            'form' => $form->createView(),
            'id' => $id,
            'errors' => array()
        );
    }

    /**
     * New application action
     *
     * @return array
     *
     * @Template
     */
    public function newAction()
    {
        return array(
            'form' => $this->getForm()->createView(),
            'errors' => array()
        );
    }

    /**
     * Update application action
     *
     * @param Request $request
     * @return array|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Template("SnideMonitorBundle:application:edit")
     */
    public function updateAction(Request $request)
    {
        $form = $this->getForm();
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                // Save instance
                $this->getManager()->update($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Application updated successfully');

                return new RedirectResponse($this->generateUrl('snide_monitor_dashboard'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Some errors found');
            }
        }

        return array(
            'form' => $form->createView(),
            'errors' => $form->getErrors()
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
        if ($application) {
            $this->getManager()->delete($application);
            $this->get('session')->getFlashBag()->add('success', 'Application has been deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'This application does not exist');
        }

        return new RedirectResponse($this->generateUrl('snide_monitor_dashboard'));
    }

    /**
     * Create application Form and submit it with application instance
     *
     * @param null $application
     * @return \Symfony\Component\Form\Form
     */
    public function getForm($application = null)
    {
        if ($application == null) {
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

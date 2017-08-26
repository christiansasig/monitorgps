<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Alert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Alert controller.
 *
 */
class AlertController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  section="AlertController",
     *  resource=true,
     *  description="Obtiene la vista principal",
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function indexAction() {
        $view = $this->view(
                        array(), 200)
                ->setTemplate('alert/index.html.twig');
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="AlertController",
     *  description="Obtiene todas las entidades",
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function findAllAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Alert')->findAll();

        $view = $this->view(array(
                    'entities' => $entities)
                        , 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="AlertController",
     *  description="Metodo para crear una nuevo registro",
     *  parameters={
     *      {"name"="$request", "dataType"="Request", "required"=true, "description"="Peticion"}
     *  },
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function newAction(Request $request) {
        $entity = new Alert();
        $form = $this->createForm('AppBundle\Form\AlertType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $view = $this->view(array(
                        'entity' => $entity,
                        'status' => 'ok'
                            ), 200)
                    ->setFormat('json');

            return $this->handleView($view);
        }

        $view = $this->view(array(
                    'entity' => $entity,
                    'form' => $form->createView()), 200)
                ->setTemplate('alert/new.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="AlertController",
     *  description="Metodo para obtener un registro",
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="Id"}
     *  },
     *  parameters={
     *      {"name"="$menu", "dataType"="Menu", "required"=true, "description"="Menu"}
     *  },
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function showAction(Alert $entity) {
        $view = $this->view(array(
                    'entity' => $entity), 200)
                ->setTemplate('alert/show.html.twig');

        return $this->handleView($view);
    }
    
    /**
     * @ApiDoc(
     *  section="AlertController",
     *  description="Metodo para obtener un registro",
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="Id"}
     *  },
     *  parameters={
     *      {"name"="$menu", "dataType"="Menu", "required"=true, "description"="Menu"}
     *  },
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function showNotificationAction(Alert $entity) {
        $view = $this->view(array(
                    'entity' => $entity), 200)
                ->setTemplate('alert/show_notification.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="AlertController",
     *  description="Metodo para editar un registro",
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="Id"}
     *  },
     *  parameters={
     *      {"name"="$request", "dataType"="Request", "required"=true, "description"="Peticion"},
     *      {"name"="$menu", "dataType"="Menu", "required"=true, "description"="Menu"}
     *  },
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function editAction(Request $request, Alert $entity) {
        //$deleteForm = $this->createDeleteForm($institution);
        $editForm = $this->createForm('AppBundle\Form\AlertType', $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $view = $this->view(array(
                        'entity' => $entity,
                        'status' => 'ok'
                            ), 200)
                    ->setFormat('json');

            return $this->handleView($view);
        }

        $view = $this->view(array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()), 200)
                ->setTemplate('alert/edit.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="AlertController",
     *  description="Metodo para eliminar un registro",
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="Id"}
     *  },
     *  parameters={{"name"="$menu", "dataType"="Menu", "required"=true, "description"="Menu"}},
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function deleteAction(Alert $entity) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        $view = $this->view(array(
                    'entity' => $entity,
                    'status' => 'ok'
                        ), 200)
                ->setFormat('json');
        return $this->handleView($view);
    }
}

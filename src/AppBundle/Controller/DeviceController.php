<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Device controller.
 *
 */
class DeviceController extends FOSRestController {

    /**
     * @ApiDoc(
     *  section="DeviceController",
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
                ->setTemplate('device/index.html.twig');
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="DeviceController",
     *  description="Obtiene todas las entidades",
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function findAllAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        //echo $user->getName();
        $entities = array();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') || $this->get('security.authorization_checker')->isGranted('ROLE_SUPERADMIN')) {
            $entities = $em->getRepository('AppBundle:Device')->findAll();
        }
        else
        {
            $entities = $em->getRepository('AppBundle:Device')->findBy(array('user' => $user));
        }  

        $view = $this->view(array(
                    'entities' => $entities)
                        , 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="DeviceController",
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
        $entity = new Device();
        $form = $this->createForm('AppBundle\Form\DeviceType', $entity);
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
                ->setTemplate('device/new.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="DeviceController",
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
    public function showAction(Device $entity) {
        $view = $this->view(array(
                    'entity' => $entity), 200)
                ->setTemplate('device/show.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="DeviceController",
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
    public function editAction(Request $request, Device $entity) {
        //$deleteForm = $this->createDeleteForm($institution);
        $editForm = $this->createForm('AppBundle\Form\DeviceType', $entity);
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
                ->setTemplate('device/edit.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="DeviceController",
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
    public function deleteAction(Device $entity) {
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

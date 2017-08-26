<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Role controller.
 *
 */
class RoleController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  section="RoleController",
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
                ->setTemplate('role/index.html.twig');
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="RoleController",
     *  description="Obtiene todas las entidades",
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function findAllAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Role')->findAll();

        $view = $this->view(array(
                    'entities' => $entities)
                        , 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="RoleController",
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
        $entity = new Role();
        $form = $this->createForm('AppBundle\Form\RoleType', $entity);
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
                ->setTemplate('role/new.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="RoleController",
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
    public function showAction(Role $entity) {
        $view = $this->view(array(
                    'entity' => $entity), 200)
                ->setTemplate('role/show.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="RoleController",
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
    public function editAction(Request $request, Role $entity) {
        //$deleteForm = $this->createDeleteForm($institution);
        $editForm = $this->createForm('AppBundle\Form\RoleType', $entity);
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
                ->setTemplate('role/edit.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="RoleController",
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
    public function deleteAction(Role $entity) {
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

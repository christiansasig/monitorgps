<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Polygon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Polygon controller.
 *
 */
class PolygonController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  section="PolygonController",
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
                ->setTemplate('polygon/index.html.twig');
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="PolygonController",
     *  description="Obtiene todas las entidades",
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function findAllAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Polygon')->findAll();

        $view = $this->view(array(
                    'entities' => $entities)
                        , 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

/**
     * @ApiDoc(
     *  section="PolygonController",
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
        $entity = new Polygon();
        $form = $this->createForm('AppBundle\Form\PolygonType', $entity);
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
                ->setTemplate('polygon/new.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="PolygonController",
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
    public function showAction(Polygon $entity) {
        
        $view = $this->view(array(
                    'entity' => $entity), 200)
                ->setTemplate('polygon/show.html.twig');

        return $this->handleView($view);
    }

     /**
     * @ApiDoc(
     *  section="PolygonController",
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
    public function editAction(Request $request, Polygon $entity) {
        //$deleteForm = $this->createDeleteForm($institution);
        $editForm = $this->createForm('AppBundle\Form\PolygonType', $entity);
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
                ->setTemplate('polygon/edit.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="PolygonController",
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
    public function deleteAction(Polygon $entity) {
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

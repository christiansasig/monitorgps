<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * User controller.
 *
 */
class UsersController extends FOSRestController {

    /**
     * @ApiDoc(
     *  section="UsersController",
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
                ->setTemplate('users/index.html.twig');
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="UsersController",
     *  description="Obtiene todas las entidades",
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function findAllAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Users')->findAll();

        $view = $this->view(array(
                    'entities' => $entities)
                        , 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="UsersController",
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
        $entity = new Users();
        $form = $this->createForm('AppBundle\Form\UsersType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->container->get('security.password_encoder');
            $password = $encoder->encodePassword($entity, $entity->getPlainPassword());
            $entity->setPassword($password);

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
                ->setTemplate('users/new.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="UsersController",
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
    public function showAction(Users $entity) {
        $view = $this->view(array(
                    'entity' => $entity), 200)
                ->setTemplate('users/show.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="UsersController",
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
    public function editAction(Request $request, Users $entity) {
        //$deleteForm = $this->createDeleteForm($institution);
        $editForm = $this->createForm('AppBundle\Form\UsersType', $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($entity->getPlainPassword()) {
                $encoder = $this->container->get('security.password_encoder');
                $password = $encoder->encodePassword($entity, $entity->getPlainPassword());
                $entity->setPassword($password);
            }

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
                ->setTemplate('users/edit.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="UsersController",
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
    public function deleteAction(Users $entity) {
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

    /**
     * @ApiDoc(
     *  section="UsersController",
     *  description="Obtiene todas las entidades",
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function authenticationAction(Request $request) {
        $params = array();
        $content = $request->getContent();
        if (!empty($content)) {
            $params = json_decode($content, true); // 2nd param to get as array
        }

        $em = $this->getDoctrine()->getManager();
        //$username = $request->request->get('username', 'admin');
        //$password = $request->request->get('password', '12345678');
        $username = $params['username'];
        $password = $params['password'];

        $user = $em->getRepository('AppBundle:Users')->findOneBy(array('username' => $username));
        //$user = $em->getRepository('AppBundle:Users')->findOneBy(array('username' => $username, 'password' => $password));

        if ($user) {
            $encoder = $this->container->get('security.password_encoder');
            $isValid = $encoder->isPasswordValid($user, $password);
            if ($isValid) {
                $view = $this->view(array(
                            'user' => $user)
                                , 200)
                        ->setFormat('json');
                return $this->handleView($view);
            }
        }

        $view = $this->view(array(
                    'user' => null)
                        , 200)
                ->setFormat('json');
        return $this->handleView($view);
    }
    
    public function testAction(Request $request) {
        /*$params = array();
        $content = $request->getContent();
        if (!empty($content)) {
            $params = json_decode($content, true); // 2nd param to get as array
        }*/

        $em = $this->getDoctrine()->getManager();
        $username = $request->request->get('username', 'admin');
        $password = $request->request->get('password', '12345678');

      

        $view = $this->view(array(
                    'user' => $password)
                        , 200)
                ->setFormat('json');
        return $this->handleView($view);
    }

}

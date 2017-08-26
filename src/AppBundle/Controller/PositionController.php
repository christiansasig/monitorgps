<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Position;
use AppBundle\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\Common\Collections;

/**
 * Position controller.
 *
 */
class PositionController extends FOSRestController {

    /**
     * @ApiDoc(
     *  section="PositionController",
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
                ->setTemplate('position/index.html.twig');
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="PositionController",
     *  description="Obtiene todas las entidades",
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function findAllAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Position')->findAll();

        $view = $this->view(array(
                    'entities' => $entities)
                        , 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="PositionController",
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
        $entity = new Position();
        $form = $this->createForm('AppBundle\Form\PositionType', $entity);
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
                ->setTemplate('position/new.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="PositionController",
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
    public function showAction(Position $entity) {
        $view = $this->view(array(
                    'entity' => $entity), 200)
                ->setTemplate('position/show.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="PositionController",
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
    public function editAction(Request $request, Position $entity) {
        //$deleteForm = $this->createDeleteForm($institution);
        $editForm = $this->createForm('AppBundle\Form\PositionType', $entity);
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
                ->setTemplate('position/edit.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="PositionController",
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
    public function deleteAction(Position $entity) {
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
     *  section="PositionController",
     *  description="Metodo para el mapa de las estaciones",
     *  statusCodes = {
     *     200 = "Regresa cuando tiene exito."
     *  }
     * )
     *
     */
    public function positionByDeviceAction(Request $request) {
        $device = array();
        $content = $request->getContent();
        if (!empty($content)) {
            $device = json_decode($content, true); // 2nd param to get as array
        }

        try {
            //Reduce errors
            error_reporting(~E_WARNING);
            // Ip address server
            $server = $device['ip'];
            // Port server
            $port = 5556;

            //Open connection with the server
            if (($socket = socket_create(AF_INET, SOCK_DGRAM, 0))) {
                socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 10, 'usec' => 0));
                socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 10, 'usec' => 0));
                //$timeout = array('sec'=>10,'usec'=>0);
                //socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,$timeout);

                $input = 'u';

                //Send the message to the server
                if (socket_sendto($socket, $input, strlen($input), 0, $server, $port)) {
                    if (socket_recv($socket, $reply, 2048, MSG_WAITALL) !== FALSE) {
                        $arrayTemp = explode(" ", $reply);
                        $pos = array(
                            "latitude" => explode("=", $arrayTemp[0])[1],
                            "longitude" => explode("=", $arrayTemp[1])[1],
                        );
                        $position = new Position();
                        //$position->setDevice($device);
                        $position->setLatitude($pos['latitude']);
                        $position->setLongitude($pos['longitude']);

                        $view = $this->view(array(
                                    'position' => $position)
                                        , 200)
                                ->setFormat('json');
                        return $this->handleView($view);
                    }
                }
            }
            socket_close($socket);
        } catch (Exception $e) {
            throw $e;
        }
        /* $em = $this->getDoctrine()->getManager();
          $user = $em->getRepository('AppBundle:Position')->find(4); */
        $view = $this->view(array(
                    'position' => null)
                        , 200)
                ->setFormat('json');
        return $this->handleView($view);
    }
 public function retriveByDeviceAction(Request $request) {
        $params = array();
        $content = $request->getContent();
        if (!empty($content)) {
            $params = json_decode($content, true); // 2nd param to get as array
        }

        $em = $this->getDoctrine()->getManager();

        $device = $params['device'];
        $startDate = $params['startDate'];
        $endDate = $params['endDate'];

        $qb = $em->createQueryBuilder();
        $qb->select('o');
        $qb->from('AppBundle:Position', 'o');
        $qb->where('o.createdAt >= :start');
        $qb->andWhere('o.createdAt <= :end');
        $qb->andWhere('o.device <= :device');
        
        $qb->setParameter('start', $startDate);
        $qb->setParameter('end', $endDate);
        $qb->setParameter('device', $device);

        $result = $qb->getQuery()->getResult();
        $entities = new Collections\ArrayCollection($result);
        
        $view = $this->view(array(
                    'positions' => $entities)
                        , 200)
                ->setFormat('json');
        return $this->handleView($view);
    }
}

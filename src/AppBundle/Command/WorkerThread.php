<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Command;

use AppBundle\Utils\PointLocation;
use AppBundle\Entity\Position;
use AppBundle\Entity\Device;
use AppBundle\Entity\Alert;
/**
 * Description of WorkerThread
 *
 * @author christiansasig
 */
class WorkerThread extends Thread {

    
    private $em;
    private $device;
    private $output;

    public function __construct($em, $device, $output) {
        $this->em = $em;
        $this->device = $device;
        $this->output = $output;
    }

    public function run() {
        try {
            $pointLocation = new PointLocation();
            //Reduce errors
            error_reporting(~E_WARNING);
            // Ip address server
            $server = $device->getIp();
            // Port server
            $port = 5556;
            $timeout = array('sec' => 5, 'usec' => 0);

            //Open connection with the server
            if (($socket = socket_create(AF_INET, SOCK_DGRAM, 0))) {
                socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, $timeout);
                $input = 'u';

                //Send the message to the server
                if (socket_sendto($socket, $input, strlen($input), 0, $server, $port)) {
                    if (socket_recv($socket, $reply, 2048, MSG_PEEK) !== FALSE) {
                        echo "Reply : $reply";

                        $arrayTemp = explode(" ", $reply);

                        $pos = array(
                            "latitude" => explode(":", $arrayTemp[0])[1],
                            "longitude" => explode(":", $arrayTemp[1])[1],
                            "ip" => explode(":", $arrayTemp[2])[1]
                        );

                        $devices = $this->em->getRepository('AppBundle:Device')->findBy(array('ip' => $pos['ip']));
                        $device = $devices[0];

                        $position = new Position();
                        $position->setDevice($device);
                        $position->setLatitude($pos['latitude']);
                        $position->setLongitude($pos['longitude']);
                        //$em->persist($position);
                        $this->em->flush();

                        $polygon = $device->getPolygon()->getPathPoints();
                        $point = $position->getLatitude() . " " . $position->getLongitude();

                        if ($point !== "0.0 0.0") {
                            if ($pointLocation->pointInPolygon($point, $polygon) === "outside") {
                                //Agregar alerta o enviar un mail porque el dispositivo se ha salido del poligono asignado
                                $alert = new Alert();
                                $alert->setDevice($device);
                                $alert->setTag("alerta");
                                $alert->setDescription("Dispositivo se encuentra fuera de la zona segura");
                                $alert->setLatitude($position->getLatitude());
                                $alert->setLongitude($position->getLongitude());
                                $alert->setNamePolygon($device->getPolygon()->getName());
                                $alert->setPathPolygon($device->getPolygon()->getPath());
                                //$em->persist($alert);
                                $this->em->flush();
                            }
                        }
                    }
                } else {
                    $this->output->writeln("<info>error al enviar info al socket</info>");
                }
            } else {
                $this->output->writeln("<info>error al crear socket</info>");
            }
            socket_close($socket);
        } catch (Exception $e) {
            throw $e;
        }
    }

}

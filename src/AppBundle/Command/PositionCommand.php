<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Position;
use AppBundle\Entity\Device;
use AppBundle\Entity\Alert;
use AppBundle\Utils\PointLocation;
use AppBundle\Utils\VARGLOBAL;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

//use AppBundle\Utils\VARGLOBAL;

class PositionCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('monitoreo:gps:position')
                ->setDescription('Obtiene las coordenadas gps');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $mailer = $this->getContainer()->get('mailer');
        $message = $mailer->createMessage();

        $context = $this->getContainer()->get('router')->getContext();
        $context->setHost("191.97.64.8");
        $context->setScheme('http');
        $context->setBaseUrl('/proyectos-web/monitoreogps/web/app_dev.php');
        $routing = $this->getContainer()->get('router');


        //$dateCurrent = new \DateTime("now");
        $pointLocation = new PointLocation();
        $em = $this->getContainer()->get('doctrine')->getManager();
        $devices = $em->getRepository('AppBundle:Device')->findBy(array('status' => true));
        foreach ($devices as $device) {
            $output->writeln("<info>Solicitando posicion del dispositivo: " . $device->getIp() . "</info>");
            try {
                //Reduce errors
                error_reporting(~E_WARNING);
                // Ip address server
                $server = $device->getIp();
                // Port server
                $port = 5556;

                //Open connection with the server
                if (($socket = socket_create(AF_INET, SOCK_DGRAM, 0))) {
                    //$timeout = array('sec'=>10,'usec'=>0);
                    //socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,$timeout);
                    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 10, 'usec' => 0));
                    socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 10, 'usec' => 0));

                    $input = 'u';

                    //Send the message to the server
                    if (socket_sendto($socket, $input, strlen($input), 0, $server, $port)) {

                        if (socket_recv($socket, $reply, 2048, MSG_WAITALL) !== FALSE) {

                            $output->writeln("<info>Reply: $reply</info>");
                            $arrayTemp = explode(" ", $reply);

                            $pos = array(
                                "latitude" => explode("=", $arrayTemp[0])[1],
                                "longitude" => explode("=", $arrayTemp[1])[1],
                            );

                            $devices = $em->getRepository('AppBundle:Device')->findBy(array('ip' => $device->getIp()));
                            $device = $devices[0];

                            $position = new Position();
                            $position->setDevice($device);
                            $position->setLatitude($pos['latitude']);
                            $position->setLongitude($pos['longitude']);
                            $em->persist($position);
                            $em->flush();

                            $polygon = $device->getPolygon()->getPathPoints();
                            $point = $position->getLatitude() . " " . $position->getLongitude();

                            if ($point !== "0.000000 0.000000") {
                                if ($pointLocation->pointInPolygon($point, $polygon) === "outside") {
                                    $dateCurrent = new \DateTime("now");
                                    $date = $dateCurrent->format('Y-m-d');
                                    //Agregar alerta o enviar un mail porque el dispositivo se ha salido del poligono asignado
                                    //$alerts = $em->getRepository('AppBundle:Alert')->findBy(array('namePolygon' => $device->getPolygon()->getName(), 'device' => $device, 'createdAt' => $date . "%"));
                                    //if (count($alerts) === 0) {
                                        $alert = new Alert();
                                        $alert->setDevice($device);
                                        $alert->setTag("alerta");
                                        $alert->setDescription("Dispositivo se encuentra fuera de la zona segura");
                                        $alert->setLatitude($position->getLatitude());
                                        $alert->setLongitude($position->getLongitude());
                                        $alert->setNamePolygon($device->getPolygon()->getName());
                                        $alert->setPathPolygon($device->getPolygon()->getPath());
                                        $em->persist($alert);
                                        $em->flush();

                                        $url = $routing->generate('alert_show_notification', array(
                                            'id' => $alert->getId()), UrlGeneratorInterface::ABSOLUTE_URL);

                                        $message->setSubject(VARGLOBAL::NOTIFICATION_ALERT);
                                        $message->setFrom(VARGLOBAL::EMAIL_FROM_GMAIL);
                                        $message->setTo($device->getUser()->getEmail());

                                        $message->setBody($this->getContainer()->get('templating')->render(
                                                        'emails/cron_notification.html.twig', array('title' => VARGLOBAL::NOTIFICATION_ALERT, 'body' => $alert->getDescription() . "\n" . $url)
                                                ), 'text/html')
                                        ;

                                        $mailer->send($message);
                                    //}
                                }
                            }
                        }
                    } else {
                        $output->writeln("<info>error al enviar info al socket</info>");
                    }
                } else {
                    $output->writeln("<info>error al crear socket</info>");
                }
                socket_close($socket);
            } catch (Exception $e) {
                throw $e;
            }
        }
    }

}

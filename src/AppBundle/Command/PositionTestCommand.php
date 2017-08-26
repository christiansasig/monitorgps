<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Position;
use AppBundle\Entity\Device;
use AppBundle\Entity\Alert;
use AppBundle\Utils\PointLocation;

//use AppBundle\Utils\VARGLOBAL;

class PositionTestCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('monitoreo:gps:test')
                ->setDescription('Obtiene las coordenadas gpsccc');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
//$dateCurrent = new \DateTime("now");
        $em = $this->getContainer()->get('doctrine')->getManager();
        $pointLocation = new PointLocation();


//echo "Reply : $reply";-2.900267068800395,-79.01890754699707
        $reply = "LAT=0.000000 LON=0.000000 SAT=0 PREC=0";

        $arrayTemp = explode(" ", $reply);

        $pos = array(
            "latitude" => explode("=", $arrayTemp[0])[1],
            "longitude" => explode("=", $arrayTemp[1])[1],
        );

        $devices = $em->getRepository('AppBundle:Device')->findBy(array('ip' => '192.168.200.2'));

        $device = $devices[0];

        $position = new Position();
        $position->setDevice($device);
        $position->setLatitude($pos['latitude']);
        $position->setLongitude($pos['longitude']);

        //$em->flush();
        $polygon = $device->getPolygon()->getPathPoints();
        $point = $position->getLatitude() . " " . $position->getLongitude();


        //echo print_r($device->getPolygon()->getPathPoints());
        //echo print_r($point);

        $output->writeln("<info>" . $pointLocation->pointInPolygon($point, $polygon) . "</info>");

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
                $em->persist($alert);
                $em->flush();
            }
        }
    }

}

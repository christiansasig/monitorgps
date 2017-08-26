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

class MailTestCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('monitoreo:gps:mail')
                ->setDescription('Obtiene las coordenadas gpsccc');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $context = $this->getContainer()->get('router')->getContext();
        $context->setHost("191.97.64.8");
        $context->setScheme('http');
        $context->setBaseUrl('/proyectos-web/monitoreogps/web/app_dev.php');
        
        $routing = $this->getContainer()->get('router');
        $url = $routing->generate('alert_show_notification', array(
            'id' => 3), UrlGeneratorInterface::ABSOLUTE_URL);


        $mailer = $this->getContainer()->get('mailer');
        $message = $mailer->createMessage();
        $message->setSubject(VARGLOBAL::NOTIFICATION_ALERT);
        $message->setFrom(VARGLOBAL::EMAIL_FROM_GMAIL);
        $message->setTo("williamsasig@gmail.com");

        $message->setBody($this->getContainer()->get('templating')->render(
                        'emails/cron_notification.html.twig', array('title' => VARGLOBAL::NOTIFICATION_ALERT, 'body' => $url)
                ), 'text/html')
        ;

        $mailer->send($message);
    }

}

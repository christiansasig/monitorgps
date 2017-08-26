<?php

namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddCountryFieldSubscriber
 *
 * @author christiansasig
 */
class AddPlainPasswordFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event) {
        $entity = $event->getData();
        $form = $event->getForm();
        if (!$entity || null === $entity->getId()) {
            $form->add('plainPassword', TextType::class, array('required' => true));
             
        }
        else
        {
           $form->add('plainPassword', TextType::class, array('required' => false));
        }
    }

}

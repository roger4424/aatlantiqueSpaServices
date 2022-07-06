<?php

namespace App\EventSubscriber;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    public function __construct(private EvenementRepository $evenementRepository, private UrlGeneratorInterface $urlGenerator)
    {
    }


    public static function getSubscribedEvents(): array
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData'
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar){
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        /** @var Evenement[] $evenements */   //tableau objet recupere
        $evenements = $this->evenementRepository
            ->createQueryBuilder('evenement')
            ->where('evenement.beginAt BETWEEN :start AND :end OR evenement.endAt BETWEEN :start AND :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($evenements as $evenement){
            $event = new Event(
                $evenement->getTitle(),
                $evenement->getBeginAt(),
                $evenement->getEndAt()
            );

            if ($evenement->getDevi() && !$evenement->getDevi()->getIsInterventionValidated()){
                $event->setOptions([
                 'backgroundColor'=> 'orange',
                    'borderColor'=> 'orange'
                 ]);
            } else{
                $event->setOptions([

                    'id'=> $evenement->getId(),
                ]);
            }



            $event->addOption(
                'url',
                $this->urlGenerator->generate('admin_calendrier_event_show', [
                    'id'=> $evenement->getId()
                ])
            );

            $calendar->addEvent($event);
        }

//        $calendar->addEvent(
//            new Event(
//                'Evenement 1'
//            )
//        );

    }
}
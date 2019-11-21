<?php

namespace App\Controller;

use App\Entity\MessageScope;
use App\Message\DismissedMessagesHelper;
use App\Repository\MessageRepositoryInterface;
use App\Repository\RoomRepositoryInterface;
use App\Repository\RoomTagRepositoryInterface;
use App\Rooms\RoomQueryBuilder;
use App\Sorting\RoomNameStrategy;
use App\Sorting\Sorter;
use App\Utils\RefererHelper;
use SchoolIT\CommonBundle\Helper\DateHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractControllerWithMessages {

    private $sorter;

    public function __construct(MessageRepositoryInterface $messageRepository, DismissedMessagesHelper $dismissedMessagesHelper,
                                DateHelper $dateHelper, Sorter $sorter, RefererHelper $refererHelper) {
        parent::__construct($messageRepository, $dismissedMessagesHelper, $dateHelper, $refererHelper);

        $this->sorter = $sorter;
    }

    /**
     * @Route("/rooms", name="rooms")
     */
    public function index(Request $request, RoomQueryBuilder $queryBuilder, RoomRepositoryInterface $roomRepository, RoomTagRepositoryInterface $roomTagRepository) {
        $query = $queryBuilder->buildFromRequest($request);

        if($query->hasConditions() || $query->hasName()) {
            $rooms = $roomRepository->findAllByQuery($query);
        } else {
            $rooms = $roomRepository->findAll();
        }

        $this->sorter->sort($rooms, RoomNameStrategy::class);

        return $this->render('rooms/index.html.twig', [
            'rooms' => $rooms,
            'query' => $query,
            'tags' => $roomTagRepository->findAll()
        ]);
    }

    protected function getMessageScope(): MessageScope {
        return MessageScope::Lists();
    }
}
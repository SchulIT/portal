<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\UserWebPushSubscription;

interface UserWebPushSubscriptionRepositoryInterface {

    public function findAllForUsers(array $users): array;

    public function findAllForExam(): array;

    public function findAllForSubstitutions(): array;

    public function persist(UserWebPushSubscription $subscription);

    public function remove(UserWebPushSubscription $subscription);
}
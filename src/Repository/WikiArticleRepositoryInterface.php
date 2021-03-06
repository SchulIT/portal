<?php

namespace App\Repository;

use App\Entity\WikiArticle;

interface WikiArticleRepositoryInterface {

    /**
     * @param int $id
     * @return WikiArticle|null
     */
    public function findOneById(int $id): ?WikiArticle;

    /**
     * @param string $uuid
     * @return WikiArticle|null
     */
    public function findOneByUuid(string $uuid): ?WikiArticle;

    /**
     * @return WikiArticle[]
     */
    public function findAll(): array;

    /**
     * @param string $q
     * @return WikiArticle[]
     */
    public function findAllByQuery(string $q): array;

    /**
     * @param WikiArticle $article
     */
    public function persist(WikiArticle $article): void;

    /**
     * @param WikiArticle $article
     */
    public function remove(WikiArticle $article): void;
}
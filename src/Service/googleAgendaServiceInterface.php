<?php

namespace GoogleAgendaApi\Service;

interface googleAgendaServiceInterface {

    /**
     * Should return a set of all blog posts that we can iterate over. Single entries of the array are supposed to be
     * implementing \Blog\Model\PostInterface
     *
     * @return array|PostInterface[]
     */
    public function getGoogleAgenda();

}

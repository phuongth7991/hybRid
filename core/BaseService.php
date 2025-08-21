<?php

namespace Core;

use Core\Exceptions\NotFoundResourceException;
use Core\Interfaces\Repository;
use Core\Interfaces\Service;

class BaseService implements Service
{
    /**
     * @throws \Exception
     */
    public function getRepository(): Repository
    {
        if ($this->repository) {
            return $this->repository;
        }
        throw new \Exception('Repository not found');
    }

    public function find(int $id): \Illuminate\Database\Eloquent\Model
    {
        $item = $this->repository->find($id);
        if (!$item) {
            throw new NotFoundResourceException("Item not exits");
        }

        return $item;
    }

    public function all($select = "*", array $filter = [], $orderBy = ['id', 'desc'])
    {
        return $this->getRepository()->all($select, $filter, $orderBy);
    }

    public function paginate($select, $filter, $orderBy, $limit)
    {
        return $this->getRepository()->paginate($select, $filter, $orderBy, $limit);
    }

    public function chunkDateRangeList($from, $to, string $mode = 'W')
    {
        switch ($mode) {
            case 'W':
                $interval = $this->chunkWeek($from, $to);
                break;
            case 'M':
                //$interval = $this->chunkMonth($from, $to);
                break;
        }
        return $interval;
    }

    private function chunkWeek($startDate, $endDate)
    {
        $range = [];
        for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
            $startOfWeek = $date->copy()->startOfWeek();
            $endOfWeek = $date->copy()->endOfWeek();
            $range[] = [
                'from' => $startOfWeek,
                'to' => $endOfWeek
            ];
        }
        return $range;
    }
}

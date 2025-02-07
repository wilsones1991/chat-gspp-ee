<?php

namespace EricWilson\ChatGSPP\Service;

class LastUpdatedDate
{

    protected $editDates = [];
    protected $lastUpdatedDate = null;
    protected $filter = null;


    public function addEditDate($editDate, $filter = null)
    {
        $this->editDates[] = [
            'edit_date' => $editDate,
            'filter' => $filter
        ];
    }

    public function getLastUpdatedDate()
    {

        if ($this->lastUpdatedDate !== null) {
            return $this->lastUpdatedDate;
        }

        $filteredDates = $this->editDates;
        if ($this->filter) {
            $filteredDates = array_filter($this->editDates, function($editDate) {
                if ($this->filter === 'exclude') {
                    return $editDate['filter'] !== $this->filter;
                } else {
                    return $editDate['filter'] === $this->filter;
                }
            });
        }

        foreach ($filteredDates as $editDate) {
            if ($this->lastUpdatedDate === null || $editDate['edit_date'] > $this->lastUpdatedDate) {
                $this->lastUpdatedDate = $editDate['edit_date'];
            }
        }

        return $this->lastUpdatedDate;
    }

    public function getDates()
    {
        return $this->editDates;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function setLastUpdatedDate($lastUpdatedDate)
    {
        $this->lastUpdatedDate = $lastUpdatedDate;
    }
    
}
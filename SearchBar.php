<?php

namespace App\Http\Livewire;


use Livewire\Component;
use Livewire\WithPagination;


class SearchBar extends Component
{

    use WithPagination;

    public $search;
    public $searchDate;
    public $model;
    public $relationTables;
    public $sort;
    public $columnsInclude;
    public $columnsNames;
    public $searchable;
    public $searchableDates;
    public $showButtons;

    public function mount($model, $relationTables, $sort, $columnsInclude, $columnsNames, $searchable, $searchableDates, $showButtons)
    {
        $this->model = $model;
        $this->relationTables = explode(',', $relationTables);
        $this->sort = $sort;
        $this->columnsInclude = $columnsInclude;
        $this->columnsNames = explode(',', $columnsNames);
        $this->searchable = $searchable;
        $this->searchableDates = $searchableDates;
        $this->showButtons = $showButtons;
        array_push($this->columnsNames, $this->showButtons);
    }

    public function render()
    {
        return view('livewire.search-bar', [
            'dataTable' => $this->getData(),
            'columnsNames' => $this->columnsNames,
            'showButtons' => $this->showButtons,
        ]);
    }

    private function getData()
    {
        $query = $this->model::query();
        if ($this->relationTables[0] != "") {
            for ($i = 0; $i < count($this->relationTables); $i += 3) {
                $query->leftJoin($this->relationTables[$i], $this->relationTables[$i + 1], '=', $this->relationTables[$i + 2]);
            }
        }
        $sortData = explode('|', $this->sort);
        $query->orderBy($sortData[0], $sortData[1]);
        $query->select(explode(',', $this->columnsInclude));

        if ($this->searchable && $this->search) {
            $searchTerms = explode(',', $this->searchable);
            $query->where(function ($innerQuery) use ($searchTerms) {
                if ($this->searchableDates) {
                    if (substr_count($this->search, " ") === 1) {
                        $partesSpace = explode(" ", $this->search);
                        if (substr_count($partesSpace[0], "/") === 1) {
                            $partes = explode("/", $partesSpace[0]);
                            $this->searchDate = $partes[1] . "%-" . $partes[0] . "% " . $partesSpace[1];
                        } elseif (substr_count($partesSpace[0], "/") === 2) {
                            $partes = explode("/", $partesSpace[0]);
                            $this->searchDate = $partes[2] . "%-" . $partes[1] . "-" . $partes[0] . "% " . $partesSpace[1];
                        } else {
                            $this->searchDate = $this->search;
                        }
                    } else {
                        if (substr_count($this->search, "/") === 1) {
                            $partes = explode("/", $this->search);
                            $this->searchDate = $partes[1] . "%-" . $partes[0];
                        } elseif (substr_count($this->search, "/") === 2) {
                            $partes = explode("/", $this->search);
                            $this->searchDate = $partes[2] . "%-" . $partes[1] . "-" . $partes[0];
                        } else {
                            $this->searchDate = $this->search;
                        }
                    }

                    $searchDates = explode(',', $this->searchableDates);
                    foreach ($searchDates as $termDates) {
                        $formattedSearch = '%' . $this->searchDate . '%';
                        $innerQuery->orWhere($termDates, 'LIKE', $formattedSearch);
                    }
                }
                foreach ($searchTerms as $term) {
                    $innerQuery->orWhere($term, 'like', '%' . $this->search . '%');
                }
            });
        }

        return $query->paginate(10);
    }

    public function getStatus($id)
    {
        return $this->model::where('id', $id)->first()->status;
    }

    public function showView($id)
    {
        $this->emitUp('showView', $id);
    }

    public function showModal($id)
    {
        $this->emitUp('showModal', $id);
    }

    public function showModalEdit($id)
    {
        $this->emitUp('showModalEdit', $id);
    }
}

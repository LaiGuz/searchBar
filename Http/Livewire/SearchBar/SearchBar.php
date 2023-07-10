<?php

namespace App\Http\Livewire\SearchBar;


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

    public $paginate;

    public $alertSession = false;

    protected $listeners =
    [
        'openAlert'
    ];

    public function mount(
                        $model,
                        $relationTables,
                        $paginate,
                        $sort,
                        $columnsInclude,
                        $columnsNames,
                        $searchable,
                        $searchableDates,
                        $showButtons
                    )
    {
        $this->model = $model;
        $this->relationTables = explode(',', $relationTables);
        $this->sort = $sort;
        $this->columnsInclude = $columnsInclude;
        $this->columnsNames = explode(',', $columnsNames);
        $this->searchable = $searchable;
        $this->searchableDates = $searchableDates;
        $this->showButtons = $showButtons;
        ($paginate != null ? $this->paginate = $paginate : $this->paginate = 10);

        array_push($this->columnsNames, $this->showButtons);
    }

    public function render()
    {
        return view('livewire.search-bar.search-bar', [
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

        return $query->paginate($this->paginate);
    }

    //CREATE
    public function showModalCreate()
    {
        $this->emitUp('showModalCreate');
    }
    //READ
    public function showModalRead($id)
    {
        $this->emitUp('showModalRead', $id);
    }
    //UPDATE
    public function showModalUpdate($id)
    {
        $this->emitUp('showModalUpdate', $id);
    }
    //DELETE
    public function showModalDelete($id)
    {
        $this->emitUp('showModalDelete', $id);
    }
    //OPEN MESSAGE
    public function openAlert($status, $msg)
    {
        session()->flash($status, $msg);
        $this->alertSession = true;
    }
    //CLOSE MESSAGE
    public function closeAlert()
    {
        $this->alertSession = false;
    }
}

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
    public $modelId;
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
                        $modelId,
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
        $this->modelId = $modelId;
        if(isset($relationTables)){
            $this->relationTables = $relationTables;
        }
        if(isset($sort)){
            $this->sort = $sort;
        }

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
        $query->select(explode(',', $this->columnsInclude),$this->modelId);

        if($this->relationTables != ""){
            $query = $this->relationTables($query);
        }
        if($this->sort != ""){
            $query = $this->sort($query);
        }

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
    #EXTRA FUNCTIONS
        //SORT
        public function sort($query)
        {
            $this->sort = str_replace(' ', '', $this->sort);
            $sortData = explode('|', $this->sort);
            $c = count($sortData);
            for ($i=0; $i < $c; $i++) {
                $s = explode(',', $sortData[$i]);
                if (count($s) === 2) {
                    $query->orderBy($s[0], $s[1]);
                }
            }
            return $query;
        }
        //RELATIONSHIPS
        public function relationTables($query)
        {
            $this->relationTables = str_replace(' ', '', $this->relationTables);
                $relationTables = explode('|', $this->relationTables);
                $crt = count($relationTables);
                for ($i=0; $i < $crt; $i++) {
                    $rt = explode(',', $relationTables[$i]);
                    if (count($rt) === 3) {
                        $query->leftJoin($rt[0], $rt[1], '=', $rt[2]);
                    }
                }
                return $query;
        }
    #END EXTRA FUNCTIONS
    #FUNCTIONS BUTTONS AND MESSAGE
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
    #END FUNCTIONS BUTTONS AND MESSAGE
}

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
    public $showId;
    public $relationTables;
    public $sort;
    public $searchable;
    public $columnsInclude;
    public $columnsNames;
    public $customSearch;
    public $showButtons;
    public $activeButton;

    public $paginate;

    public $alertSession = false;

    protected $listeners =
    [
        'openAlert'
    ];

    public function mount(
                        $model,
                        $modelId,
                        $showId,
                        $relationTables,
                        $paginate,
                        $sort,
                        $columnsInclude,
                        $columnsNames,
                        $searchable,
                        $customSearch,
                        $activeButton,
                        $showButtons
                    )
    {
        $this->model = $model;
        $this->modelId = $modelId;
        $this->showId = strtolower(trim($showId));
        if(isset($relationTables)){
            $this->relationTables = $relationTables;
        }
        if(isset($sort)){
            $this->sort = $sort;
        }

        $this->columnsInclude = $columnsInclude;
        $this->columnsNames = explode(',', $columnsNames);
        $this->searchable = $searchable;
        $this->customSearch = $customSearch;
        $this->showButtons = $showButtons;
        $this->activeButton = $activeButton;
        ($paginate != null ? $this->paginate = $paginate : $this->paginate = 10);

        if ($this->activeButton) {
            array_push($this->columnsNames,'Status');
        }
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
        $selects = array($this->modelId.' as id');
        if($this->columnsInclude){
            foreach (explode(',', $this->columnsInclude) as $key => $value) {
                array_push($selects,$value);
            }
        }else{
            $selects = '*';
        }
        if ($this->activeButton) {
            array_push($selects,$this->activeButton);
        }
        $query->select($selects);

        if($this->relationTables != ""){
            $query = $this->relationTables($query);
        }
        if($this->sort != ""){
            $query = $this->sort($query);
        }
        if ($this->searchable && $this->search) {
            $this->search($query);
        }
        return $query->paginate($this->paginate);
    }
    #PRICIPAL FUNCTIONS
        public function search($query){
            $searchTerms = explode(',', $this->searchable);
                $query->where(function ($innerQuery) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        if ($this->customSearch) {
                            $fields = explode('|', $this->customSearch);
                            if(in_array($term,$fields)){
                                $search = array($term=>$this->search);
                                $formattedSearch = $this->model::filterFields($search);
                                if($formattedSearch['converted'] != '%0%'){
                                    $innerQuery->orWhere($term, $formattedSearch['f'], $formattedSearch['converted']);
                                }else{
                                    $innerQuery->orWhere($term, 'LIKE', '%' . $this->search . '%');
                                }
                            }else{
                                $innerQuery->orWhere($term, 'LIKE', '%' . $this->search . '%');
                            }
                        }else{
                            $innerQuery->orWhere($term, 'LIKE', '%' . $this->search . '%');
                        }
                    }
                });
                // dd($query);
        }
    #END PRICIPAL FUNCTIONS
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

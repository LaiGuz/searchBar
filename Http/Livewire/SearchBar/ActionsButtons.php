<?php

namespace App\Http\Livewire\SearchBar;

use Livewire\Component;

class ActionsButtons extends Component
{
    public $search_id;

    public function mount($search_id)
    {
        $this->search_id = $search_id;
    }

    public function render()
    {
        return view('livewire.search-bar.actions-buttons');
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
}

<?php

namespace App\Http\Livewire\SearchBar;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ExtraButtons extends Component
{
    public $buttons = array();
    public Model $model;

    public function mount($buttons)
    {
        if ($buttons) {
            $buttons = str_replace(' ', '', $buttons);
            $buttonsData = explode('|', $buttons);
            $c = count($buttonsData);
            for ($i=0; $i < $c; $i++) {
                $s = explode(',', $buttonsData[$i]);
                if (count($s) === 3) {
                    $b[]=[
                        'route' =>$s[0],
                        'id'    =>$this->getId($s[1]),
                        'title' =>$s[2],
                    ];
                }
                $this->buttons = $b;
            }
        }
    }

    public function render()
    {
        return view('livewire.search-bar.extra-buttons');
    }

    public function getId($item)
    {
        $id = $this->model->getAttribute('id');
        $m = $this->model::select($item)->find($id);
        return $m->$item;
    }

}

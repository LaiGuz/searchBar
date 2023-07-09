<div class="w-100">
    <livewire:search-bar.search-bar
            model="App\Models\Model\Times"
            relationTables="athletes,athletes.id,times.athlete_id,modalities,modalities.id,times.modality_id"
            columnsInclude="times.id,day,athletes.name,modalities.title,record,pool,distance,type_time"
            columnsNames="Data,Atleta,Modalidade,Tempo,Piscina,Distância,Tipo"
            showButtons="Opções"
            componenButtons=""
            searchable="type_time,athletes.name,modalities.title"
            searchableDates="day"
            sort="day|asc"
            paginate="15"
        />
</div>

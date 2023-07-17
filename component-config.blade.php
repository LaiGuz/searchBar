<div class="bg-white shadow-md dark:bg-gray-800 pt-3 sm:rounded-lg">
        <livewire:search-bar.search-bar
            {{-- REQUIRED --}}  model="App\Models\Model\Times" {{-- Model principal --}}
            {{-- REQUIRED --}}  modelId="times.id" {{-- Ex: 'table.id' or 'id' --}}
            {{-- REQUIRED --}}  showId="true" {{-- 'true' or 'false' --}}
            {{-- REQUIRED --}}  columnsInclude="day,athletes.name,modalities.title,record,pool,distance,type_time" {{-- Colunas incluidas --}}
            {{-- REQUIRED --}}  columnsNames="Data,Atleta,Modalidade,Tempo,Piscina,Distância,Tipo" {{-- Cabeçalho da tabela --}}
            {{-- REQUIRED --}}  searchable="type_time,athletes.name,modalities.title,pool,distance,day,record" {{-- Colunas pesquisadas no banco de dados --}}
            {{-- OK --}} customSearch="day|record" {{-- Colunas personalizadas, customizar no model --}}
            {{-- OK --}} relationTables="athletes,athletes.id,times.athlete_id | modalities,modalities.id,times.modality_id " {{-- Relacionamentos ( table , key , foreingKey ) --}}
            {{-- OK --}} showButtons="Ações" {{-- Botões --}}
            {{-- OK --}} sort="times.day , asc | times.record , asc" {{-- Ordenação da tabela --}}
            {{-- OK --}} paginate="15" {{-- Qtd de registros por página --}}
        />
    </div>

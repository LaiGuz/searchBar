# searchBar
Barra de pesquisa

## Componente de configuração
    <div class="bg-white shadow-md dark:bg-gray-800 pt-3 sm:rounded-lg">
        <livewire:search-bar.search-bar
            {{-- REQUIRED --}}  model="App\Models\Model\Times" {{-- Model principal --}}
            {{-- REQUIRED --}}  modelId="times.id" {{-- Ex: 'table.id' or 'id' --}}
            {{-- REQUIRED --}}  showId="false" {{-- 'true' or 'false' --}}
            {{-- REQUIRED --}}  columnsInclude="day,athletes.name,modalities.title,record,pool,distance,type_time" {{-- Colunas incluidas --}}
            {{-- REQUIRED --}}  columnsNames="Data,Atleta,Modalidade,Tempo,Piscina,Distância,Tipo" {{-- Cabeçalho da tabela --}}
            {{-- REQUIRED --}}  searchable="type_time,athletes.name,modalities.title,pool,distance,day,record" {{-- Colunas pesquisadas no banco de dados --}}
            {{-- OK --}} customSearch="day|record|id" {{-- Colunas personalizadas, customizar no model --}}
            {{-- OK --}} activeButton="" {{-- Toogle de ativar e desativear registro --}}
            {{-- OK --}} relationTables="athletes,athletes.id,times.athlete_id | modalities,modalities.id,times.modality_id " {{-- Relacionamentos ( table , key , foreingKey ) --}}
            {{-- OK --}} showButtons="Ações" {{-- Botões --}}
            {{-- OK --}} sort="times.day , asc | times.record , asc" {{-- Ordenação da tabela --}}
            {{-- OK --}} paginate="15" {{-- Qtd de registros por página --}}
        />
    </div>

## Exemplo de conversão no model (apresentação na tabela) 
    public function getRecordAttribute($value)
    {
        $time = explode('.', $value);
        if ($time[0] > 0) {
            $seconds = intval($time[0]); //Converte para inteiro

            $mins = floor($seconds / 60);
            $secs = floor($seconds % 60);

            if (isset($time[1])) {
                $sign = sprintf('%02d:%02d', $mins, $secs) . ',' . $time[1];
            } else {
                $sign = sprintf('%02d:%02d', $mins, $secs) . ',00';
            }
        } else {
            $sign = '00:00,' . $time[1];
        }

        return $sign;
    }
    public function getDayAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value)
            ->format('d/m/Y');
    }
  ## Exemplo de conversão no model (Pesquisa conforme o usuário visualiza)
      public function scopeFilterFields($query, $filters)
    {
        foreach ($filters as $key => $value) {
            if ($key == 'record') {
                $tempo = preg_replace('/[^0-9:,.]/', '', $value);
                $tempo = str_replace(',', '.', $tempo);
                $partesTempo = explode(':', $tempo);
                $partesTempo = array_map('floatval', $partesTempo);
                $totalPartes = count($partesTempo);
                if ($totalPartes === 1) {
                    $partesTempo = [0, $partesTempo[0]];
                    $totalPartes = 2;
                }
                if ($totalPartes === 2 && $partesTempo[0] === 0) {
                    $converted = $partesTempo[1];
                    return array('f'=>'LIKE','converted'=>'%' . $converted . '%');
                } elseif ($totalPartes === 2) {
                    $converted = ($partesTempo[0] * 60) + $partesTempo[1];
                    return array('f'=>'LIKE','converted'=>'%' . $converted . '%');
                } elseif ($totalPartes >= 3) {
                    $converted = ($partesTempo[0] * 60) + $partesTempo[1] + ($partesTempo[2] / 100);
                    return array('f'=>'REGEXP','converted'=>'^' . $converted . '$');
                }
            }
            if($key == 'day'){
                if (substr_count($value, " ") === 1) {
                    $partesSpace = explode(" ", $value);
                    if (substr_count($partesSpace[0], "/") === 1) {
                        $partes = explode("/", $partesSpace[0]);
                        $converted = $partes[1] . "%-" . $partes[0] . "% " . $partesSpace[1];
                    } elseif (substr_count($partesSpace[0], "/") === 2) {
                        $partes = explode("/", $partesSpace[0]);
                        $converted = $partes[2] . "%-" . $partes[1] . "-" . $partes[0] . "% " . $partesSpace[1];
                    } else {
                        $converted = $value;
                    }
                } else {
                    if (substr_count($value, "/") === 1) {
                        $partes = explode("/", $value);
                        $converted = $partes[1] . "%-" . $partes[0];
                    } elseif (substr_count($value, "/") === 2) {
                        $partes = explode("/", $value);
                        $converted = $partes[2] . "%-" . $partes[1] . "-" . $partes[0];
                    } else {
                        $converted = $value;
                    }
                }
                return array('f'=>'LIKE','converted'=>'%' . $converted . '%');
            }
        }
    }

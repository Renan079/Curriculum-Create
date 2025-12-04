<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Currículo Clássico</title>
    <style>
        @page { margin: 40px 50px; } /* Margens de documento Word */
        
        body {
            font-family: 'Times New Roman', Times, serif; /* Fonte Clássica */
            color: #000;
            line-height: 1.4;
            background: #fff;
        }

        /* --- CABEÇALHO CENTRALIZADO --- */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .name {
            font-size: 28px;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .role {
            font-size: 16px;
            font-style: italic;
            margin-bottom: 10px;
        }
        .contact-line {
            font-size: 11px;
        }
        .separator {
            margin: 0 5px;
            color: #666;
        }

        /* --- SEÇÕES --- */
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            margin-bottom: 15px;
            padding-bottom: 2px;
            letter-spacing: 1px;
        }

        /* --- ITENS --- */
        .item { margin-bottom: 15px; }
        
        /* Tabela para alinhar Esquerda/Direita na mesma linha */
        .item-table { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
        .item-left { text-align: left; font-weight: bold; font-size: 14px; }
        .item-right { text-align: right; font-style: italic; font-size: 12px; }

        .item-subtitle {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .item-desc {
            font-size: 12px;
            text-align: justify;
        }

        /* Lista de Habilidades */
        .skills-list {
            font-size: 12px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    @php
        // PREPARAÇÃO DOS DADOS (Mesma lógica do Moderno)
        $personalSection = $resume->sections->firstWhere('type', 'personal');
        $personal = $personalSection ? $personalSection->content : [];
        $others = $resume->sections->where('type', '!=', 'personal');

        $nomeFinal  = $personal['full_name'] ?? $resume->user->name ?? 'Sem Nome';
        $cargoFinal = $personal['headline'] ?? $resume->title ?? '';
    @endphp

    <div class="header">
        <div class="name">{{ $nomeFinal }}</div>
        @if($cargoFinal)
            <div class="role">{{ $cargoFinal }}</div>
        @endif

        <div class="contact-line">
            @if(isset($personal['email'])) 
                {{ $personal['email'] }} <span class="separator">•</span>
            @endif
            @if(isset($personal['phone'])) 
                {{ $personal['phone'] }} <span class="separator">•</span>
            @endif
            @if(isset($personal['city'])) 
                {{ $personal['city'] }}
            @endif
            @if(isset($personal['linkedin'])) 
                <span class="separator">•</span> {{ str_replace(['https://', 'www.'], '', $personal['linkedin']) }}
            @endif
        </div>
    </div>

    @if(isset($personal['summary']) && !empty($personal['summary']))
        <div class="section">
            <div class="section-title">Resumo Profissional</div>
            <div class="item-desc">{!! nl2br(e($personal['summary'])) !!}</div>
        </div>
    @endif

    @foreach($others as $section)
        <div class="section">
            <div class="section-title">{{ $section->title }}</div>

            @php
                // CORREÇÃO MÁGICA PARA ITEM ÚNICO
                $items = $section->content;
                if (is_array($items) && count(array_filter(array_keys($items), 'is_string')) > 0) {
                    $items = [$items];
                }
            @endphp

            @if($section->type == 'skills')
                <div class="skills-list">
                    @php
                        if (!is_array($items)) $items = explode(',', $items);
                    @endphp
                    {{ implode(' • ', $items) }}
                </div>
            
            @elseif(is_array($items))
                @foreach($items as $item)
                    @if(is_array($item))
                        <div class="item">
                            <table class="item-table">
                                <tr>
                                    <td class="item-left">
                                        {{ $item['company'] ?? $item['institution'] ?? $item['school'] ?? '' }}
                                    </td>
                                    <td class="item-right">
                                        {{ $item['date_start'] ?? '' }} 
                                        @if(isset($item['date_end']) && $item['date_end'] != $item['date_start']) 
                                            - {{ $item['date_end'] }} 
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            
                            <div class="item-subtitle">
                                {{ $item['role'] ?? $item['degree'] ?? $item['course'] ?? '' }}
                            </div>

                            @if(isset($item['description']))
                                <div class="item-desc">{!! nl2br(e($item['description'])) !!}</div>
                            @endif
                        </div>
                    @endif
                @endforeach
            @else
                <div class="item-desc">{!! nl2br(e($items)) !!}</div>
            @endif
        </div>
    @endforeach

</body>
</html>
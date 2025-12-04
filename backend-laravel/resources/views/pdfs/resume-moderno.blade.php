<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Currículo</title>
    <style>
        @page { margin: 0px; }
        
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
            line-height: 1.5;
            background-color: #ffffff;
        }

        /* --- FAIXA LATERAL FIXA (O AZUL INFINITO) --- */
        .sidebar-background {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 34%;
            background-color: #1e293b; /* Azul Sidebar */
            z-index: -1;
        }

        /* --- LAYOUT --- */
        .container-table {
            width: 100%;
            border-collapse: collapse;
            z-index: 2;
        }
        
        .column-sidebar {
            width: 34%;
            color: #f1f5f9;
            vertical-align: top;
            padding: 40px 25px;
        }

        .column-main {
            width: 66%;
            vertical-align: top;
            padding: 50px 40px;
            background-color: #ffffff;
        }

        /* --- ESTILOS VISUAIS --- */
        .profile-pic {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.2);
            object-fit: cover;
            background-color: #334155;
            display: block;
            margin: 0 auto 30px auto;
        }

        .sidebar-header {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 5px;
            margin-bottom: 15px;
            margin-top: 25px;
            font-weight: bold;
        }

        .contact-group { margin-bottom: 12px; }
        .contact-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: bold;
            display: block;
        }
        .contact-value { font-size: 11px; color: #f1f5f9; word-wrap: break-word; }
        
        .skill-tag {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            margin-bottom: 5px;
            margin-right: 3px;
            color: #fff;
        }

        .header-name {
            font-size: 34px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
            margin: 0;
            line-height: 1;
        }
        .header-role {
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #3b82f6;
            font-weight: 700;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        .summary-text {
            font-size: 13px;
            color: #475569;
            text-align: justify;
            margin-bottom: 30px;
            white-space: pre-line;
        }

        .section-title {
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            color: #1e293b;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 20px;
            margin-top: 10px;
            letter-spacing: 1px;
        }

        .job-item { margin-bottom: 25px; page-break-inside: avoid; }
        .job-header-table { width: 100%; margin-bottom: 2px; }
        .job-role-cell { text-align: left; font-weight: 700; font-size: 14px; color: #0f172a; }
        .job-date-cell { text-align: right; font-size: 11px; color: #64748b; font-weight: 600; width: 120px; }
        .job-company { font-size: 12px; color: #3b82f6; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; }
        .job-desc { font-size: 12px; color: #475569; text-align: justify; white-space: pre-line; }
    </style>
</head>
<body>

    <div class="sidebar-background"></div>

    @php
        // PREPARAÇÃO DOS DADOS
        $personalSection = $resume->sections->firstWhere('type', 'personal');
        $personal = $personalSection ? $personalSection->content : [];
        $others = $resume->sections->where('type', '!=', 'personal');

        $nomeFinal  = $personal['full_name'] ?? $resume->user->name ?? 'Sem Nome';
        $cargoFinal = $personal['headline'] ?? $resume->title ?? '';

        $photoPath = null;
        if (isset($personal['photo']) && !empty($personal['photo'])) {
            if (str_starts_with($personal['photo'], 'http')) {
                $photoPath = $personal['photo'];
            } else {
                $cleanPath = ltrim($personal['photo'], '/');
                $photoPath = public_path($cleanPath);
            }
        }
        $avatarPadrao = 'https://ui-avatars.com/api/?background=334155&color=fff&name=' . urlencode($nomeFinal);
    @endphp

    <table class="container-table">
        <tr>
            <td class="column-sidebar">
                <img src="{{ $photoPath ?? $avatarPadrao }}" class="profile-pic">

                <div class="sidebar-header">Contato</div>
                @if(isset($personal['email']))
                    <div class="contact-group">
                        <span class="contact-label">Email</span>
                        <div class="contact-value">{{ $personal['email'] }}</div>
                    </div>
                @endif
                @if(isset($personal['phone']))
                    <div class="contact-group">
                        <span class="contact-label">Telefone</span>
                        <div class="contact-value">{{ $personal['phone'] }}</div>
                    </div>
                @endif
                @if(isset($personal['city']))
                    <div class="contact-group">
                        <span class="contact-label">Localização</span>
                        <div class="contact-value">{{ $personal['city'] }}</div>
                    </div>
                @endif
                @if(isset($personal['linkedin']))
                    <div class="contact-group">
                        <span class="contact-label">Social</span>
                        <div class="contact-value">{{ str_replace(['https://', 'www.'], '', $personal['linkedin']) }}</div>
                    </div>
                @endif

                @php $skillSection = $resume->sections->firstWhere('type', 'skills'); @endphp
                @if($skillSection)
                    @php
                        // Normaliza Skills para sempre ser array
                        $skills = $skillSection->content;
                        if (!is_array($skills)) $skills = explode(',', $skills); 
                    @endphp
                    <div class="sidebar-header">Competências</div>
                    <div>
                        @foreach($skills as $skill)
                            <span class="skill-tag">{{ trim($skill) }}</span>
                        @endforeach
                    </div>
                @endif
            </td>

            <td class="column-main">
                <h1 class="header-name">{{ $nomeFinal }}</h1>
                @if($cargoFinal)
                    <div class="header-role">{{ $cargoFinal }}</div>
                @endif

                @if(isset($personal['summary']) && !empty($personal['summary']))
                    <div class="summary-text">{!! nl2br(e($personal['summary'])) !!}</div>
                @endif

                @foreach($others as $section)
                    @if($section->type == 'skills') @continue @endif

                    <div class="section">
                        <div class="section-title">{{ $section->title }}</div>

                        @php
                            // AQUI ESTÁ A CORREÇÃO MÁGICA:
                            // Se 'content' for um array associativo (único emprego), transformamos em lista de arrays
                            $items = $section->content;
                            
                            // Verifica se é array e se as chaves são strings (ex: 'company', 'role')
                            if (is_array($items) && count(array_filter(array_keys($items), 'is_string')) > 0) {
                                $items = [$items]; // Embrulha em um array pai: [ {job} ]
                            }
                        @endphp

                        @if(is_array($items))
                            @foreach($items as $item)
                                @if(is_array($item))
                                    <div class="job-item">
                                        <table class="job-header-table">
                                            <tr>
                                                <td class="job-role-cell">
                                                    {{ $item['role'] ?? $item['degree'] ?? $item['course'] ?? '' }}
                                                </td>
                                                <td class="job-date-cell">
                                                    {{ $item['date_start'] ?? '' }} 
                                                    @if(isset($item['date_end']) && $item['date_end'] != $item['date_start']) 
                                                        - {{ $item['date_end'] }} 
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="job-company">
                                            {{ $item['company'] ?? $item['institution'] ?? $item['school'] ?? '' }}
                                        </div>
                                        @if(isset($item['description']))
                                            <div class="job-desc">{!! nl2br(e($item['description'])) !!}</div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        @elseif(is_string($items))
                            {{-- Caso seja só texto simples --}}
                            <div class="job-desc">{!! nl2br(e($items)) !!}</div>
                        @endif
                    </div>
                @endforeach
            </td>
        </tr>
    </table>

</body>
</html>
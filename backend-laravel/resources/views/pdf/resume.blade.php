<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currículo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: {{ $resume->font_family }}, sans-serif; }
        @page { margin: 0; size: A4; }
    </style>
</head>
<body class="bg-white">
    <div class="w-[21cm] min-h-[29.7cm] mx-auto p-10">
        @foreach($resume->sections as $section)
            @if($section->type === 'personal')
                <div class="text-center border-b-2 border-gray-200 pb-6 mb-6">
                    <h1 class="text-4xl font-bold uppercase" style="color: {{ $resume->primary_color }}">
                        {{ $section->content['full_name'] ?? '' }}
                    </h1>
                    <p class="text-xl text-gray-600 mt-2">{{ $section->content['headline'] ?? '' }}</p>
                    <div class="mt-4 text-sm text-gray-500 flex justify-center gap-3">
                        <span>📧 {{ $section->content['email'] ?? '' }}</span> | 
                        <span>📱 {{ $section->content['phone'] ?? '' }}</span> | 
                        <span>📍 {{ $section->content['city'] ?? '' }}</span>
                    </div>
                </div>
            @endif

            @if($section->type === 'experience')
                <div class="mb-6">
                    <h2 class="text-lg font-bold uppercase mb-4 border-b-2 pb-1" 
                        style="color: {{ $resume->primary_color }}; border-color: {{ $resume->primary_color }}">
                        {{ $section->title }}
                    </h2>
                    <div class="mb-4">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-bold text-gray-800 text-lg">{{ $section->content['role'] ?? '' }}</h3>
                            <span class="text-sm font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                {{ $section->content['date_start'] ?? '' }} - {{ $section->content['date_end'] ?? '' }}
                            </span>
                        </div>
                        <div class="text-indigo-600 font-medium italic">{{ $section->content['company'] ?? '' }}</div>
                        <p class="text-gray-700 text-sm whitespace-pre-line mt-2 text-justify">
                            {{ $section->content['description'] ?? '' }}
                        </p>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</body>
</html>
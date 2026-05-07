@props([
    'headers' => [],
    'rows' => [],
    'striped' => true,
    'hover' => true,
    'bordered' => false,
    'compact' => false,
    'class' => ''
])

@php
    $tableClasses = [
        'min-w-full divide-y divide-gray-200',
        $bordered ? 'border border-gray-200' : '',
        $class
    ];
    
    $tdClasses = [
        'whitespace-nowrap text-sm text-gray-900',
        $compact ? 'px-3 py-2' : 'px-6 py-4'
    ];
    
    $thClasses = [
        'text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50',
        $compact ? 'px-3 py-2' : 'px-6 py-3'
    ];
@endphp

<div class="overflow-x-auto">
    <table class="{{ implode(' ', array_filter($tableClasses)) }}">
        @if(!empty($headers))
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $header)
                        <th scope="col" class="{{ implode(' ', $thClasses) }}">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @elseif(isset($thead))
            {{ $thead }}
        @endif
        
        <tbody class="bg-white divide-y divide-gray-200">
            @if(!empty($rows))
                @foreach($rows as $row)
                    <tr class="{{ $striped ? 'even:bg-gray-50' : '' }} {{ $hover ? 'hover:bg-gray-50' : '' }}">
                        @foreach($row as $cell)
                            <td class="{{ implode(' ', $tdClasses) }}">
                                {{ $cell }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </tbody>
        
        @if(isset($tfoot))
            <tfoot class="bg-gray-50">
                {{ $tfoot }}
            </tfoot>
        @endif
    </table>
</div>
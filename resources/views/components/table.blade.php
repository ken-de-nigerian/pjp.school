@props(['striped' => true])

<div class="overflow-hidden rounded-lg border border-gray-200">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200']) }}>
        {{ $slot }}
    </table>
</div>

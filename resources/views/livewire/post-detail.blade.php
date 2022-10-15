<div class="w-100">
    <div class="col-12 mb-3 mt-5">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
        <p>{{ $content }}</p>
    </div>
    <div class="col-12">
        <livewire:comments :model="$post" />
    </div>
</div>

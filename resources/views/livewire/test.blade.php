<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow">
    @if (empty($results))
        <form wire:submit.prevent="submit">
            @foreach($questions as $q)
                <div class="mb-6">
                    <h3 class="font-semibold mb-2">{{ $q->question_text }}</h3>
                    @foreach($q->options as $opt)
                        <label class="inline-flex items-center mb-1">
                            <input 
                                type="radio" 
                                wire:model="answers.{{ $q->id }}" 
                                value="{{ $opt->id }}" 
                                class="form-radio"
                            >
                            <span class="ml-2">{{ $opt->option_text }}</span>
                        </label><br>
                    @endforeach
                    @error("answers.{$q->id}") 
                        <span class="text-red-600 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
            @endforeach

            <button 
                type="submit" 
                class="w-full py-2 px-4 bg-green-600 text-white rounded"
            >
                Testi Tamamla
            </button>
        </form>
    @else
        <div class="text-center">
            <h2 class="text-xl font-bold mb-4">Önerilen Hayvan(lar)</h2>
            <ul class="list-disc list-inside">
                @foreach(\App\Models\SubCategory::whereIn('id', $results)->get() as $sub)
                    <li>{{ $sub->name }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>


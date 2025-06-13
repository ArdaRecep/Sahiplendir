<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow d-flex justify-content-center">
    @if (empty($results))
        <form wire:submit.prevent="submit" style="width: fit-content;">
            @foreach ($questions as $key => $q)
                <div class="card" style="border: 1px solid black; color: black; margin-bottom: 30px; margin-top: 30px;">
                    <h7>{{ $key + 1 }}. Soru</h7>
                    <h3 class="font-semibold mb-2">{{ $q->question_text }}</h3>
                    @foreach ($q->options as $opt)
                        <label class="inline-flex items-center mb-1">
                            <input type="radio" wire:model="answers.{{ $q->id }}" value="{{ $opt->id }}"
                                class="form-radio">
                            <span class="ml-2">{{ $opt->option_text }}</span>
                        </label><br>
                    @endforeach
                    @error("answers.{$q->id}")
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach
            <div style="margin-bottom: 30px; justify-content: center; align-items: center; display: flex;">
                <div style="width: fit-content">
                    <button type="submit" class="btn btn-primary">
                        Testi Tamamla
                    </button>
                </div>
            </div>
        </form>
    @else
        <div class="text-center" style="min-height: 53.6vh; display: flex; justify-content: center; align-items: center;">
            <div style="height: fit-content">
            <h2 class="text-xl mb-4" style="font-size: 45px;">Sizin İçin Önerilen Hayvanlar</h2>
            <ol class="list-disc list-inside card"  style="border: 1px solid black; color: black; display: flex; justify-content: center; align-items: center;">
                @foreach (\App\Models\SubCategory::whereIn('id', $results)->get() as $sub)
                    <li>{{ $sub->name }}</li>
                @endforeach
            </ol>
            </div>
        </div>
    @endif
</div>

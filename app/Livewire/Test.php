<?php

namespace App\Livewire;

use App\Models\Language;
use Livewire\Component;
use App\Models\TestQuestion;
use App\Models\OptionAnimalScore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Test extends Component
{
    public int $language_id;
    public Collection $questions;
    public array $answers = [];     // [ question_id => option_id ]
    public array $scores  = [];     // [ sub_category_id => total_score ]
    public array $results = [];     // final sub_category_id list

    // threshold farkı (örneğin 5 puan)
    public int $threshold = 5;

    public function mount(int $language_id)
    {
        $this->language_id = $language_id;

        $this->questions = TestQuestion::with('options.scores')
            ->where('language_id', $this->language_id)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function submit()
    {
        $this->validate([
            'answers' => 'required|array',
            // her soru için bir seçim olmuş mu
            'answers.*' => 'required|exists:test_options,id',
        ]);

        // puanları hesapla
        foreach ($this->answers as $questionId => $optionId) {
            $scores = OptionAnimalScore::where('test_option_id', $optionId)->get();
            foreach ($scores as $score) {
                $this->scores[$score->sub_category_id] = 
                    ($this->scores[$score->sub_category_id] ?? 0) + $score->score;
            }
        }

        if (empty($this->scores)) {
            $this->addError('answers', 'Hiç puan bulunamadı.');
            return;
        }

        // en yüksek puanı bul
        $max = max($this->scores);

        // threshold farkı içinde kalan sub_category_id'leri al
        $this->results = collect($this->scores)
            ->filter(fn($v) => $max - $v <= $this->threshold)
            ->keys()
            ->all();
    }
    public function render()
    {
        $language = Language::findOrFail($this->language_id);
        return view('livewire.test',['language' => $language]);
    }
}

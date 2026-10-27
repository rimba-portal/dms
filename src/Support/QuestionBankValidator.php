<?php

declare(strict_types=1);

namespace Rimba\Document\Support;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class QuestionBankValidator
{
    /**
     * @param  array<int, array<string, mixed>>  $questions
     * @return array<int, array<string, mixed>>
     *
     * @throws ValidationException
     */
    public function validate(array $questions): array
    {
        $minimum = (int) config('document.question_bank.minimum_answers', 2);
        $maximum = (int) config('document.question_bank.maximum_answers', 10);

        $validated = Validator::make(
            ['questions' => $questions],
            [
                'questions' => ['array'],
                'questions.*.id' => ['required', 'string', 'distinct', 'max:100'],
                'questions.*.question' => ['required', 'string'],
                'questions.*.answers' => ['required', 'array', "min:{$minimum}", "max:{$maximum}"],
                'questions.*.answers.*' => ['required', 'string'],
                'questions.*.correct' => ['required', 'array', 'min:1'],
                'questions.*.correct.*' => ['required', 'string', 'distinct'],
                'questions.*.explanation' => ['nullable', 'string'],
            ],
        )->validate()['questions'];

        foreach ($validated as $index => $question) {
            $answerKeys = array_keys($question['answers']);
            $invalid = array_diff($question['correct'], $answerKeys);

            if ($invalid !== []) {
                throw ValidationException::withMessages([
                    "questions.{$index}.correct" => 'Every correct answer key must exist in answers.',
                ]);
            }
        }

        return array_values($validated);
    }
}

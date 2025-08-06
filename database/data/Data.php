<?php

namespace Database\Data;

use App\Enums\Difficulty;
use App\Models\Concerns\HasHashids;
use App\Models\Question;
use App\Services\PrettyIdGenerator;
use Illuminate\Support\Facades\DB;

class Data
{
    use HasHashids;

    public static $questions = [
        [
            'question' => 'What is PHP?',
            'difficulty' => Difficulty::EASY,
            'answers' => [
                'PHP is an open-source programming language',
                'PHP is used to develop dynamic and interactive websites',
                'PHP is a server-side scripting language',
                'All of the mentioned'
            ],
            'correctAnswer' => 4,
        ],
        [
            'question' => 'Who is the father of PHP?',
            'difficulty' => Difficulty::EASY,
            'answers' => [
                'Drek Kolkevi',
                'Rasmus Lerdorf',
                'Willam Makepiece',
                'List Barely'
            ],
            'correctAnswer' => 2,
        ],
        [
            'question' => 'What does PHP stand for?',
            'difficulty' => Difficulty::EASY,
            'answers' => [
                'PHP stands for Preprocessor Home Page',
                'PHP stands for Pretext Hypertext Processor',
                'PHP stands for Hypertext Preprocessor',
                'PHP stands for Personal Hyper Processor'
            ],
            'correctAnswer' => 3,
        ],
        [
            'question' => 'Which of the following is the correct syntax to write a PHP code?',
            'difficulty' => Difficulty::EASY,
            'answers' => [
                '<?php ?>',
                '< php >',
                '< ? php ?>',
                '<? ?>'
            ],
            'correctAnswer' => 4,
        ],
        [
            'question' => 'What is PHP?',
            'difficulty' => Difficulty::EASY,
            'answers' => [
                'PHP is an open-source programming language',
                'PHP is used to develop dynamic and interactive websites',
                'PHP is a server-side scripting language',
                'All of the mentioned'
            ],
            'correctAnswer' => 4,
        ],
        [
            'question' => 'Which of the following is the correct way to add a comment in PHP code?',
            'difficulty' => Difficulty::EASY,
            'answers' => [
                '#',
                '//',
                '/* */',
                'All of the mentioned'
            ],
            'correctAnswer' => 4,
        ],
        [
            'question' => 'Which of the following is the default file extension of PHP files?',
            'difficulty' => Difficulty::EASY,
            'answers' => [
                '.php',
                '.ph',
                '.xml',
                '.html'
            ],
            'correctAnswer' => 1,
        ],
        [
            'question' => 'How to define a function in PHP?',
            'difficulty' => Difficulty::EASY,
            'answers' => [
                'functionName(parameters) {function body}',
                'function {function body}',
                'function functionName(parameters) {function body}',
                'data type functionName(parameters) {function body}'
            ],
            'correctAnswer' => 3,
        ],
        [
            'question' => 'Which is the right way of declaring a variable in PHP?',
            'difficulty' => Difficulty::EASY,
            'answers' => [
                '$3hello',
                '$_hello',
                '$this',
                '$5_Hello'
            ],
            'correctAnswer' => 2,
        ],
        [
            'question' => 'Which of the following PHP functions can be used for generating unique ids?',
            'difficulty' => Difficulty::EASY,
            'answers' => [
                'md5()',
                'uniqueid()',
                'mdid()',
                'id()'
            ],
            'correctAnswer' => 2,
        ],
        [
            'question' => 'Which of the following web servers are required to run the PHP script?',
            'difficulty' => Difficulty::EASY,
            'answers' => [
                'Apache and PHP',
                'IIS',
                'XAMPP',
                'Any of the mentioned'
            ],
            'correctAnswer' => 2,
        ],
    ];

    public static function seedQuestions(): void
    {
        $questions = [];
        $answers = [];
        $questionAnswers = [];
        $categorizables = [];

        $lastAnsId = 0;

        foreach (self::$questions as $index => $question) {
            $q = [];
            $q['is_answers_type_single'] = count($question['answers']) > 2;
            $q['text'] = $question['question'];
            $q['difficulty'] = $question['difficulty']->value;
            $q['no_of_answers'] = count($question['answers']);
            $q['pretty_id'] = PrettyIdGenerator::generate('questions', 'quest_'. $index, 13);
            $questions[] = $q;

            $category = [];
            $category['category_id'] = 1;
            $category['categorizable_id'] = $index + 1;
            $category['categorizable_type'] = (new Question())->getMorphClass();
            $categorizables[] = $category;

            foreach ($question['answers'] as $aIndex => $answer) {
                $qa = [];
                $a = [];
                $a['pretty_id'] = PrettyIdGenerator::generate('questions', 'ans_'. $lastAnsId, 12);
                $a['text'] = $answer;
                $answers[] = $a;

                $qa['question_id'] = $index + 1;
                $qa['answer_id'] = ++$lastAnsId;
                $qa['correct_answer'] = $aIndex + 1 === $question['correctAnswer'];
                $questionAnswers[] = $qa;
            }
        }

        DB::table('categories')->insert(['name' => 'PHP' . uniqid()]);
        DB::table('categorizables')->insert($categorizables);
        DB::table('questions')->insert($questions);
        DB::table('answers')->insert($answers);
        DB::table('question_answer')->insert($questionAnswers);
    }
}

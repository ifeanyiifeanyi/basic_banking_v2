<?php

namespace App\Services;

use App\Models\KycQuestion;
use Illuminate\Support\Facades\Storage;

class KycQuestionService
{
    // public function getAllQuestions()
    // {
    //     return KycQuestion::orderBy('order')->get();
    // }

    // public function createQuestion(array $data)
    // {
    //     return KycQuestion::create($data);
    // }

    // // Implement other methods for updating, deleting questions, etc.

    // public function getQuestionById($id){
    //     return KycQuestion::find($id);
    //     // Or use Eloquent's findOrFail method to throw an exception if not found.

    // }

    // // public function updateQuestion($question, array $data){
    // //     $question->update($data);
    // // }

    // public function updateQuestion($question, array $data)
    // {
    //     if (isset($data['options']) && is_array($data['options'])) {
    //         $data['options'] = json_encode($data['options']);
    //     }
    //     $question->update($data);
    // }

    // public function deleteQuestion($question){
    //     $question->delete();
    // }

    // // Implement other methods for handling file uploads for questions, etc.

    // public function uploadQuestionFile($question, $file){
    //     $path = $file->store('kyc_questions');
    //     $question->update(['file_path' => $path]);
    // }

    // public function deleteQuestionFile($question){
    //     Storage::delete($question->file_path);
    //     $question->update(['file_path' => null]);

    // }



    // // Implement other methods for handling question order, etc.

    // public function updateQuestionOrder($questions){
    //     foreach ($questions as $order => $questionId) {
    //         KycQuestion::where('id', $questionId)->update(['order' => $order]);
    //     }
    // }


    // public function updateQuestionOptions($question, array $options)
    // {
    //     $question->update(['options' => json_encode($options)]);
    // }

    public function getAllQuestions()
    {
        return KycQuestion::orderBy('order')->get();
    }

    public function createQuestion(array $data)
    {
        return KycQuestion::create($data);
    }

    public function getQuestionById($id)
    {
        return KycQuestion::findOrFail($id);
    }

    public function updateQuestion($question, array $data)
    {
        if (isset($data['options']) && is_array($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }
        $question->update($data);
    }

    public function deleteQuestion($question)
    {
        $question->delete();
    }

    public function uploadQuestionFile($question, $file)
    {
        $path = $file->store('kyc_questions');
        $question->update(['file_path' => $path]);
    }

    public function deleteQuestionFile($question)
    {
        if ($question->file_path) {
            Storage::delete($question->file_path);
            $question->update(['file_path' => null]);
        }
    }

    public function updateQuestionOrder($questions)
    {
        foreach ($questions as $order => $questionId) {
            KycQuestion::where('id', $questionId)->update(['order' => $order]);
        }
    }

    public function updateQuestionOptions($question, array $options)
    {
        $question->update(['options' => json_encode($options)]);
    }
}

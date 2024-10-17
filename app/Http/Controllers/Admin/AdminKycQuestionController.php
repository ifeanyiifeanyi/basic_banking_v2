<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\KycQuestionService;
use App\Http\Requests\StoreKycQuestionRequest;
use App\Http\Requests\UpdateKycQuestionRequest;

class AdminKycQuestionController extends Controller
{
    protected $kycQuestionService;
    public function __construct(KycQuestionService $kycQuestionService)
    {
        $this->kycQuestionService = $kycQuestionService;
    }

    public function index()
    {
        $questions = $this->kycQuestionService->getAllQuestions();
        return view('admin.kyc_questions.index', compact('questions'));
    }

    public function create()
    {
        return view('admin.kyc_questions.create');
    }

    public function store(StoreKycQuestionRequest $request)
    {
        $data = $request->validated();

        // Convert options array to JSON if present
        if (isset($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }

        $this->kycQuestionService->createQuestion($data);
        return redirect()->route('admin.kyc_questions.index')->with('success', 'Question created successfully.');
    }

    public function edit($id)
    {
        $question = $this->kycQuestionService->getQuestionById($id);
        return view('admin.kyc_questions.edit', compact('question'));
    }

    public function update(UpdateKycQuestionRequest $request, $id)
    {
        $question = $this->kycQuestionService->getQuestionById($id);
        $data = $request->validated();

        // Convert options array to JSON if present
        if (isset($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }

        $this->kycQuestionService->updateQuestion($question, $data);
        return redirect()->route('admin.kyc_questions.index')->with('success', 'Question updated successfully.');
    }

    public function destroy($id){
        $question = $this->kycQuestionService->getQuestionById($id);
        $this->kycQuestionService->deleteQuestion($question);
        return redirect()->route('admin.kyc_questions.index')->with('success', 'Question deleted successfully.');
    }
}
